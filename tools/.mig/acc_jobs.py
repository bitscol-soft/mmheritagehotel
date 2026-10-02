import subprocess,sys
sys.path.insert(0,'tools/.mig')
from acc import migrate
LIST='mm-report mm-acc mm-rst mm-rst-inv'
SETUP='mm-hotel-setup mm-acc mm-rst mm-rst-inv'
FORM='mm-acc mm-rst mm-rst-inv mm-rst-form'
import re
def opening(p):
    t=open(p).read()
    a=t.index('        <!-- LIST -->')
    b=t.index('            @if (request()->company_id')
    new='''            <form action="" method="GET" class="mm-setup-filter mm-report-form">
                <div class="input-group">
                    <label class="input-group-addon">Company <strong class="text-danger">*</strong></label>
                    <select class="form-control chosen-select-100-percent required" required name="company_id">
                        <option></option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ $id == request('company_id') ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach 
                    </select>
                </div>

                <div class="input-group">
                    <label class="input-group-addon">Account Group</label>
                    <select class="form-control chosen-select-100-percent" id="account_group_id" name="account_group_id">
                        <option></option>
                        @foreach($accountGroups as $id => $name)
                            <option value="{{ $id }}" {{ $id == request('account_group_id') ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach 
                    </select>
                </div>

                <div class="input-group">
                    <label class="input-group-addon">Account Control</label>
                    <select class="form-control chosen-select-100-percent" id="account_control_id" name="account_control_id">
                        <option></option>
                        @foreach($accountControls as $id => $name)
                            <option value="{{ $id }}" {{ $id == request('account_control_id') ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach 
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="mm-button"><i class="fa fa-check-circle"></i> Get Data</button>
                </div>
            </form>
'''
    # take the old filter block out of the panel and put it in its own panel before it
    t=t[:a]+'        <!-- LIST -->\n        <div class="row" style="width: 100%; margin: 0 !important;">\n\n'+t[b:]
    t=t.replace('    <x-mm.panel class="tw-p-4">','    <x-mm.panel class="mm-report-filter">\n'+new+'    </x-mm.panel>\n    <x-mm.panel class="tw-p-4">',1)
    open(p,'w').write(t)
POST={'opening':opening}
J=[
 ('setup/account-controls/index','Account controls grouped by account group.',LIST,{'patch':[('</div> --}}\n                                        </td>','--}}\n                                            </div>\n                                        </td>')]}),
 ('setup/account-controls/create','Add an account control.',FORM),
 ('setup/account-controls/edit','Update the account control.',FORM),
 ('setup/account-groups/index','Account groups and their account types.',LIST),
 ('setup/account-opening-balances/create','Enter opening balances for the ledger accounts.','mm-report '+FORM,{'patch':[("hasPermission('accounts.create', $slugs)))","hasPermission('accounts.create', $slugs))")],'post':'opening'}),
 ('setup/account-subsidiaries/index','Subsidiary ledgers under each account.',LIST),
 ('setup/account-subsidiaries/create','Add a subsidiary ledger.',FORM),
 ('setup/account-subsidiaries/edit','Update the subsidiary ledger.',FORM),
 ('setup/accounts/index','Chart of accounts.',LIST),
 ('setup/accounts/create','Add an account to the chart of accounts.',FORM),
 ('setup/accounts/edit','Update the account.',FORM),
 ('party/customers/index','Customers and their contact details.',LIST),
 ('party/customers/create','Add a customer.',FORM),
 ('party/customers/edit','Update the customer.',FORM),
 ('party/suppliers/index','Suppliers and their contact details.',LIST),
 ('party/suppliers/create','Add a supplier.',FORM),
 ('party/suppliers/edit','Update the supplier.',FORM),
 ('product/categories/index','Product categories.',LIST),
 ('product/categories/create','Add a product category.',FORM),
 ('product/categories/edit','Update the product category.',FORM),
 ('product/units/index','Product units.',LIST),
 ('product/units/create','Add a product unit.',FORM),
 ('product/units/edit','Update the product unit.',FORM),
 ('product/products/index','Products with price and stock settings.',LIST),
 ('product/products/create','Add a product.',FORM),
 ('product/products/edit','Update the product.',FORM),
]
if __name__=='__main__':
    for j in J:
        subprocess.run(['git','checkout','-q','HEAD','--',  'module/Account/views/'+j[0]+'.blade.php'],check=True)
        try:
            kw=dict(j[3]) if len(j)>3 else {}
            post=kw.pop('post',None)
            migrate(j[0],j[1],j[2],**kw)
            if post: POST[post]('module/Account/views/'+j[0]+'.blade.php')
        except Exception as e: print('ERR',j[0],repr(e))
