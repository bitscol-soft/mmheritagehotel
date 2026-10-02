import re, textwrap
V='module/Bar/views/'
FILES=['bar/sales/index','bar/sales/return/index','bar/reports/cash-flow/index','bar/reports/sales/index','bar/reports/today-activities/index']
SHORT={'Invoice Number':'Invoice','Guest/Customer':'Guest'}
BLOCK=re.compile(r'''(?P<ind>[ ]*)<x-mm\.panel class="tw-p-4">\n(?P<alert>[ ]*(?:<x-alert-message />|@include\('partials\._alert_message'\))\n)?\s*<!-- Search -->\n\s*<div class="row">\s*<div class="col-sm-\d+ col-sm-offset-\d+">\s*(?P<form><form[^>]*>)\s*<x-mm\.table-scroll[^>]*>\s*<table[^>]*>\s*(?P<head><thead>.*?</thead>)?\s*<tbody>\s*<tr>(?P<tds>.*?)</tr>\s*</tbody>\s*</table>\s*</x-mm\.table-scroll>\s*</form>\s*</div>\s*</div>\n''',re.S)
BTN='''<div class="btn-group">
    <button class="mm-button" type="submit">
        <i class="fa fa-search"></i> Search
    </button>
    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
        <i class="fa fa-refresh"></i>
    </a>
</div>'''
def conv(m):
    ind=m.group('ind')
    labels=[]
    if m.group('head'):
        labels=[re.sub(r'<[^>]+>','',x).strip() for x in re.findall(r'<th[^>]*>(.*?)</th>',m.group('head'),re.S)]
    cells=re.findall(r'<td[^>]*>(.*?)</td>',m.group('tds'),re.S)
    out=[]
    for i,c in enumerate(cells):
        c=textwrap.dedent(c.strip('\n')).strip()
        if 'x-widget.date-filter' in c:
            out.append('<div class="mm-report-field">'+c+'</div>')
        elif 'btn-group' in c:
            out.append(BTN)
        elif 'input-group' not in c and i<len(labels):
            lab=SHORT.get(labels[i],labels[i])
            out.append('<div class="input-group">\n    <span class="input-group-addon">%s</span>\n%s\n</div>'%(lab,textwrap.indent(c,'    ')))
        else:
            out.append(c)
    form=re.sub(r'<form\b','<form class="mm-setup-filter mm-report-form"',m.group('form'),1)
    body='\n\n'.join(out)
    s=''
    if m.group('alert'): s+=ind+m.group('alert').strip()+'\n'
    s+=ind+'<x-mm.panel class="mm-report-filter">\n'+ind+'    '+form+'\n'+textwrap.indent(body,ind+'        ')+'\n'+ind+'    </form>\n'+ind+'</x-mm.panel>\n'
    s+=ind+'<x-mm.panel class="tw-p-4">\n'
    return s
for f in FILES:
    p=V+f+'.blade.php'
    t=open(p).read()
    n,k=BLOCK.subn(conv,t,1)
    assert k==1,f
    open(p,'w').write(n)
print('ok')

# one-attribute view fix: the Bar manufacturers list included the Restaurant edit modal
p=V+'bar/inventory/manufacturers/index.blade.php'
t=open(p).read()
t=t.replace("@include('inventory.manufacturers.edit-modal')","@include('bar.inventory.manufacturers.edit-modal')")
open(p,'w').write(t)

# night audit list: date filter
p=V+'bar-night-audits/index.blade.php'
t=open(p).read()
a=t.index('            <div class="row mb-2">')
b=t.index('        @endif',a)
new="""            <x-mm.panel class="mm-report-filter">
                <form action="" method="GET" class="mm-setup-filter mm-report-form">
                    <div class="input-group">
                        <label class="input-group-addon">From</label>
                        <input type="text" class="date-picker form-control text-center"
                            autocomplete="off" name="from_date" value="{{ request('from_date') }}"
                            placeholder="From Date">
                    </div>

                    <div class="input-group">
                        <label class="input-group-addon">To</label>
                        <input type="text" class="form-control date-picker text-center"
                            autocomplete="off" name="to_date" value="{{ request('to_date') }}"
                            placeholder="To Date">
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="mm-button">
                            <i class="fa fa-search-plus"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </form>
            </x-mm.panel>
"""
t=t[:a]+new+t[b:]
open(p,'w').write(t)

t=open(p).read() if False else open(V+'bar-night-audits/index.blade.php').read()
t=t.replace("""    <x-mm.panel class="tw-p-4">
        @if ($checkNUll && count($nightaudits[0]->details) > 0)
            <x-mm.panel class="mm-report-filter">""","""    @if ($checkNUll && count($nightaudits[0]->details) > 0)
            <x-mm.panel class="mm-report-filter">""",1)
t=t.replace("""            </x-mm.panel>
        @endif

        <div class="row">
""","""            </x-mm.panel>
    @endif
    <x-mm.panel class="tw-p-4">

        <div class="row">
""",1)
open(V+'bar-night-audits/index.blade.php','w').write(t)

# inventory reports: shared filter partial in its own filter panel (as the Restaurant inventory report)
import re as _re
for f in ['bar/inventory/inventory-report','bar/reports/inventory/index']:
    p=V+f+'.blade.php'
    t=open(p).read()
    pat=_re.compile(r'    <x-mm\.panel class="tw-p-4">\n(?P<alert>\s*(?:<x-alert-message />|@include\(\'partials\._alert_message\'\))\n)\s*<div class="my-2">\n\s*@include\(\'bar\.inventory\.includes\.filter\'\)\n\s*</div>\n')
    t,k=pat.subn(lambda m:'    <x-mm.panel class="mm-report-filter">\n        @include(\'bar.inventory.includes.filter\')\n    </x-mm.panel>\n    <x-mm.panel class="tw-p-4">\n'+m.group('alert'),t,1)
    assert k==1,f
    t=t.replace('class="mm-report mm-bar mm-rst mm-rst-inv" title="Product inventory"','class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-inventory" title="Product inventory"')
    open(p,'w').write(t)

# product list: filter partial in the row pattern and its own filter panel (as the Restaurant product list)
open(V+'bar/inventory/product/_inc/filter.blade.php','w').write("""<div class="col-sm-12">
    <form action="">
        <div class="row">
            <div class="col-md-4">
                <x-widget.text-input-group name="name" title="Product Name" :value="request('name')"/>
            </div>

            <div class="col-md-3">
                <x-widget.text-input-group name="barcode" title="Barcode" :value="request('barcode')"/>
            </div>

            <div class="col-md-3">
                <x-widget.select-input-group name="category_id" title="Category" :collections="$categories" :selected="request('category_id')"/>
            </div>

            <div class="col-md-2">
                <div class="btn-group">
                    <a href="{{ request()->url() }}" class="btn btn-danger btn-sm" aria-label="Reset">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <button class="btn-outline-success btn-sm" aria-label="Search">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
""")
p=V+'bar/inventory/product/index.blade.php'
t=open(p).read()
old="""    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        @include('bar.inventory.product._inc.filter')

"""
assert old in t
t=t.replace(old,"""    <x-mm.panel class="mm-report-filter">
        @include('bar.inventory.product._inc.filter')
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

""",1)
import re as _r
t=_r.sub(r'(<x-mm\.page class="mm-report mm-bar mm-rst)( mm-rst-inv)',r'\1 mm-rst-inventory\2',t,1) if 'mm-rst-inventory' not in t else t
open(p,'w').write(t)
