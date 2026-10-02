import re,sys,os
V='module/Bar/views/'
def dd(l): return len(re.findall(r'<div\b',l))-len(re.findall(r'</div>',l))
def block_end(lines,start,d0=0):
    d=d0
    for i in range(start,len(lines)):
        d+=dd(lines[i])
        if d<=0 and i>=start: return i
    raise Exception('unbalanced from %d'%start)
def modal_mask(lines):
    mask=[False]*len(lines); i=0
    while i<len(lines):
        m=re.search(r'<div[^>]*class="([^"]*)"',lines[i])
        if m and 'modal' in m.group(1).split():
            e=block_end(lines,i)
            for k in range(i,e+1): mask[k]=True
            i=e+1
        else: i+=1
    return mask
def wrap_tables(lines,label,only=None):
    mask=modal_mask(lines)
    pos=[i for i,l in enumerate(lines) if re.search(r'<table\b',l) and not mask[i]]
    res=[]
    for s in pos:
        if any(a<s<=b for a,b in res): continue
        d=0
        for e in range(s,len(lines)):
            d+=len(re.findall(r'<table\b',lines[e]))-len(re.findall(r'</table>',lines[e]))
            if d==0: break
        res.append((s,e))
    for k,(s,e) in reversed(list(enumerate(res))):
        if only and (k+1) not in only: continue
        ind=re.match(r'\s*',lines[s]).group()
        blk=['    '+l if l.strip() else l for l in lines[s:e+1]]
        lines[s:e+1]=[ind+'<x-mm.table-scroll label="%s">'%label]+blk+[ind+'</x-mm.table-scroll>']
    return lines
def dedent(lines,to):
    nb=[l for l in lines if l.strip()]
    if not nb: return lines
    m=min(len(l)-len(l.lstrip()) for l in nb)
    return [(' '*to+l[m:]) if l.strip() else '' for l in lines]
def toolbar_actions(hdr):
    inner=hdr[1:-1]
    out=[];skip_h4=False;first=True;incm=False
    for l in inner:
        if incm or '{{--' in l:
            out.append(l); incm='--}}' not in l; continue
        if '<h4' in l: skip_h4='</h4>' not in l; continue
        if skip_h4:
            if '</h4>' in l: skip_h4=False
            continue
        if re.search(r'<span[^>]*widget-toolbar',l) or l.strip()=='</span>': continue
        if not l.strip(): continue
        if re.search(r'<a\b',l):
            cls='mm-button' if first else 'mm-button mm-button-secondary'
            first=False
            if re.search(r'<a\b[^>]*?\bclass="',l): l=re.sub(r'(<a\b[^>]*?)\bclass="[^"]*"',r'\1class="%s"'%cls,l,count=1)
            else: l=l.replace('<a ','<a class="%s" '%cls,1)
        out.append(l)
    return out
def migrate(f,title,desc,cls,label="Table",only=None,panel_cls="tw-p-4",drop_n=4):
    p=V+f+'.blade.php'
    t=open(p).read(); lines=t.split('\n')
    ci=[i for i,l in enumerate(lines) if l.strip()=="@section('content')"][0]
    ce=[i for i,l in enumerate(lines) if i>ci and l.strip() in ('@endsection','@stop')][0]
    body=lines[ci+1:ce]
    w=[i for i,l in enumerate(body) if 'widget-box' in l][0]
    pre=[l.strip() for l in body[:w] if l.strip().startswith('@include(')]
    hs=[i for i,l in enumerate(body) if 'widget-header' in l and i>w][0]
    he=block_end(body,hs)
    hdr=body[hs:he+1]
    ms=[i for i,l in enumerate(body) if 'widget-main' in l and i>he][0]
    me=block_end(body,ms)
    gap=[l for l in body[he+1:ms] if l.strip() and not re.match(r'\s*(<div[^>]*widget-body[^>]*>|<!--.*-->)\s*$',l)]
    inner=gap+body[ms+1:me] if gap else body[ms+1:me]
    tail=body[me+1:]
    rest=[];drop=0
    for l in tail:
        if drop<drop_n and l.strip()=='</div>': drop+=1; continue
        rest.append(l)
    while inner and not inner[0].strip(): inner.pop(0)
    while inner and not inner[-1].strip(): inner.pop()
    inner=dedent(inner,8)
    inner=wrap_tables(inner,label,only)
    actions=toolbar_actions(hdr)
    out=lines[:ci+1]+['','<x-mm.styles />']+pre+['<x-mm.page class="%s" title="%s" description="%s">'%(cls,title,desc)]
    if actions: out+=['    <x-slot name="actions">']+dedent(actions,8)+['    </x-slot>']
    out+=['    <x-mm.panel class="%s">'%panel_cls]+inner+['    </x-mm.panel>']
    if any(l.strip() for l in rest): out+=dedent(rest,4)
    out+=['</x-mm.page>','']+lines[ce:]
    bal=sum(dd(l) for l in inner+rest)
    if bal!=0: print('!! div balance',f,bal)
    open(p,'w').write('\n'.join(out))
LIST='mm-report mm-bar mm-rst mm-rst-inv'
SETUP='mm-hotel-setup mm-bar mm-rst mm-rst-inv'
FORM='mm-bar mm-rst mm-rst-inv mm-rst-form'
