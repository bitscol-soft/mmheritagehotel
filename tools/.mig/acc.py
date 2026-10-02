import re, subprocess, textwrap
V='module/Account/views/'
def mask(t):
    t=re.sub(r'\{\{--.*?--\}\}',lambda m:re.sub(r'[^\n]',' ',m.group(0)),t,flags=re.S)
    t=re.sub(r'<!--.*?-->',lambda m:re.sub(r'[^\n]',' ',m.group(0)),t,flags=re.S)
    return t
def cnt(l):
    return len(re.findall(r'<div\b',l))-len(re.findall(r'</div>',l))
def find_close(m,i):
    d=0
    for j in range(i,len(m)):
        d+=cnt(m[j])
        if d<=0 and (j>i or '</div>' in m[j]): return j
    raise Exception('unbalanced from %d'%i)
WRAP=re.compile(r'^\s*<div class="(?:widget-toolbar[^"]*|pull-right tableTools-container|dt-buttons[^"]*|btn-group|pull-right|tableTools-container)"[^>]*>\s*$')
def conv_anchor(m):
    attrs,inner=m.group(1),m.group(2)
    if 'mm-button' in attrs: return m.group(0)
    title=re.search(r'title="([^"]*)"',attrs)
    icon=re.search(r'<i class="([^"]*)"',inner)
    keep=[]
    for a in re.finditer(r'(\S+?)=("[^"]*"|\'[^\']*\')',attrs):
        if a.group(1) in ('class','title','tabindex','aria-controls','data-toggle','data-original-title','data-placement'): continue
        keep.append(a.group(0))
    ic=re.sub(r'\s*bigger-\d+','',icon.group(1)) if icon else 'fa fa-list'
    txt=re.sub(r'\s+',' ',re.sub(r'<[^>]+>','',inner)).strip()
    label=txt or (title.group(1) if title else '')
    sec = 'mm-button-secondary' if re.search(r'Refresh',label) else ''
    cls=('mm-button '+sec).strip()
    return '<a class="%s" %s><i class="%s"></i>%s</a>'%(cls,' '.join(keep),ic,(' '+label) if label else '')
def migrate(f,desc,cls,label=None,title=None,kind=None,dump=False,patch=()):
    p=V+f+'.blade.php'
    t=subprocess.check_output(['git','show','HEAD:'+p],text=True)
    for a_,b_ in patch:
        assert a_ in t,(f,'patch')
        t=t.replace(a_,b_)
    lines=t.split('\n'); m=mask(t).split('\n')
    # title
    if title is None:
        mm=re.search(r"@section\('page-header'\)(.*?)@(?:stop|endsection)",t,re.S)
        title=re.sub(r'\s+',' ',re.sub(r'<[^>]+>','',mm.group(1))).strip()
    cs=next(i for i,l in enumerate(lines) if "@section('content')" in l)
    ce=next(i for i in range(len(lines)-1,-1,-1) if re.match(r'\s*@(endsection|stop)\b',lines[i]) and i>cs)
    wb=next(i for i in range(cs,ce) if 'widget-box' in m[i] and '<div' in m[i])
    wbe=find_close(m,wb)
    pre=lines[cs+1:wb]; premask=m[cs+1:wb]
    post=lines[wbe+1:ce]; postmask=m[wbe+1:ce]
    # pre: drop wrapper divs
    k=0; keep_pre=[]; skip=False
    for l,ml in zip(pre,premask):
        s_=ml.strip()
        if skip:
            if s_=='</div>': skip=False
            continue
        if s_=='<div class="no-print">': skip=True; continue
        if re.match(r'^<div class="(?:row|col-sm-\d+[^"]*)"[^>]*>$',s_) and k<2: k+=1; continue
        if not l.strip() or re.match(r'^\s*<!--.*-->\s*$',l): continue
        keep_pre.append(l)
    # post: remove k closing divs
    keep_post=[]; r=k
    for l,ml in zip(post,postmask):
        if r and ml.strip()=='</div>': r-=1; continue
        keep_post.append(l)
    assert r==0,(f,'post closers')
    # header
    hs=next(i for i in range(wb+1,wbe) if 'widget-header' in m[i] and '<div' in m[i])
    he=find_close(m,hs)
    hl=lines[hs+1:he]; hm=m[hs+1:he]
    # remove h3 block
    out=[];inh=False;tm=[]
    for l,ml in zip(hl,hm):
        if '<h3' in ml: inh=True
        if inh:
            if '</h3>' in ml: inh=False
            continue
        out.append(l); tm.append(ml)
    # remove toolbar wrappers (outside comments)
    removed=0; o2=[];tm2=[]
    for l,ml in zip(out,tm):
        if ml.strip() and WRAP.match(ml): removed+=1; continue
        o2.append(l); tm2.append(ml)
    o3=[]
    r=removed
    for l,ml in zip(reversed(o2),reversed(tm2)):
        if r and ml.strip()=='</div>': r-=1; continue
        o3.append(l)
    o3=list(reversed(o3)); assert r==0,(f,'toolbar closers')
    tb='\n'.join(o3)
    # comment spans stay; convert anchors outside comments only
    parts=re.split(r'(\{\{--.*?--\}\})',tb,flags=re.S)
    parts=[x if x.startswith('{{--') else re.sub(r'<a\b((?:[^>{]|\{\{.*?\}\}|\{)*)>(.*?)</a>',conv_anchor,x,flags=re.S) for x in parts]
    tb='\n'.join(l.rstrip() for l in ''.join(parts).split('\n') if l.strip())
    tb=textwrap.dedent(tb)
    body=lines[he+1:wbe]
    body=[l for l in body if l.strip()!='<div class="space"></div>']
    body='\n'.join(body).strip('\n')
    body=textwrap.dedent(body)
    # wrap tables
    def wrap_tables(b):
        bm=mask(b); res=[];pos=0
        for mt in re.finditer(r'<table\b',bm):
            if mt.start()<pos: continue
            e=bm.index('</table>',mt.start())+8
            depth=bm.count('<table',mt.start(),e)-bm.count('</table>',mt.start(),e)
            while depth>0:
                e2=bm.index('</table>',e)+8; depth+=bm.count('<table',e,e2)-1; e=e2
            ls=b.rfind('\n',0,mt.start())+1
            ind=re.match(r'\s*',b[ls:]).group(0)
            res.append((ls,e,ind))
            pos=e
        for ls,e,ind in reversed(res):
            chunk=b[ls:e]
            b=b[:ls]+ind+'<x-mm.table-scroll label="%s">\n'%(label or title)+textwrap.indent(chunk,'    ')+'\n'+ind+'</x-mm.table-scroll>'+b[e:]
        return b
    if '<table' in body: body=wrap_tables(body)
    pretext='\n'.join(keep_pre).strip('\n')
    alert=[l for l in keep_pre if 'alert' in l.lower() and 'include' in l]
    other=[l for l in keep_pre if l not in alert]
    ot='\n'.join(x for x in other if x.strip()).strip('\n')
    out=[]
    out.append('\n'.join(lines[:cs+1]))
    if ot: out.append(textwrap.dedent(ot))
    out.append('')
    out.append('<x-mm.styles />')
    out.append('<x-mm.page class="%s" title="%s" description="%s">'%(cls,title.replace('"','&quot;'),desc))
    if tb:
        out.append('    <x-slot name="actions">\n'+textwrap.indent(tb,'        ')+'\n    </x-slot>')
    out.append('    <x-mm.panel class="tw-p-4">')
    for a in alert: out.append('        '+a.strip())
    out.append(textwrap.indent(body,'        '))
    out.append('    </x-mm.panel>')
    out.append('</x-mm.page>')
    pt='\n'.join(keep_post).strip('\n')
    if pt.strip(): out.append(''); out.append(textwrap.dedent(pt))
    out.append('')
    out.append('\n'.join(lines[ce:]))
    res='\n'.join(out)
    # balance
    mm=mask(res)
    assert mm.count('<div')==mm.count('</div>'),(f,'div balance',mm.count('<div'),mm.count('</div>'))
    open(p,'w').write(res)
    return res
