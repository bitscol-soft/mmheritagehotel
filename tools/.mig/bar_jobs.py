import sys,subprocess; sys.path.insert(0,'tools/.mig')
from bar import *
INV='mm-invoice-page mm-bar mm-rst'
J=[
 ('bar/inventory/categories/index','Product categories','Categories used to group bar products.',SETUP,'Categories',{}),
 ('bar/inventory/units/index','Units','Measurement units for bar products.',SETUP,'Units',{}),
 ('bar/inventory/manufacturers/index','Manufacturers','Manufacturers and suppliers of bar products.',SETUP,'Manufacturers',{}),
 ('bar/inventory/supplier/index','Suppliers','Bar suppliers.',SETUP,'Suppliers',{}),
 ('bar/inventory/product/create','Add product','Create a bar product, or upload several from a file.',FORM,'Product',{}),
 ('bar/inventory/product/edit','Edit product','Update the product details.',FORM,'Product',{}),
 ('bar/inventory/product/index','Products','Bar products with stock type, price and status.',LIST,'Products',{}),
 ('bar/inventory/inventory-report','Product inventory','Stock on hand for the selected product filters.',LIST,'Product inventory',{'drop_n':6}),
 ('bar/inventory/product/uploads/edit','Edit uploaded product','Review the uploaded product before confirming it.',FORM,'Product',{}),
 ('bar/inventory/product/package/index','Product packages','Packages made of several bar products.',LIST,'Packages',{}),
 ('bar/inventory/product/package/create','Create package','Combine bar products into a package.',FORM,'Package',{}),
 ('bar/tables/index','Tables','Bar tables and their seating.',SETUP,'Tables',{}),
 ('bar/purchase/index','Purchase list','Bar purchases with required and received quantities.',LIST+' mm-rst-purchase','Purchases',{}),
 ('bar/sales/index','Sale list','Bar invoices with payment status.',LIST+' mm-rst-sales','Sales',{}),
 ('bar/sales/return/index','Sale return list','Returns raised against bar invoices.',LIST+' mm-rst-sales','Sale returns',{}),
 ('bar/sales-v2/index','Sale list','Bar invoices with payment status.',LIST+' mm-rst-sales','Sales',{}),
 ('bar/reports/sales/index','Sales report','Bar invoices for the selected filters.',LIST,'Sales report',{}),
 ('bar/reports/cash-flow/index','Cash flow','Cash received and paid out for the selected invoice and dates.',LIST,'Cash flow',{}),
 ('bar/reports/inventory/index','Product inventory','Stock on hand for the selected product filters.',LIST+' mm-rst-inventory','Product inventory',{'drop_n':6}),
 ('bar/reports/today-activities/index',"Today's activities",'Transactions recorded for the selected invoice and date.',LIST,'Today activities',{}),
 ('bar-night-audits/index','Bar night audit','Bar night audits closed in the selected period.',LIST,'Night audits',{}),
 ('bar-night-audits/create-v2','Generate bar night audit',"Pick the audit period, review the day's bar collections and dues, then generate the audit.",'mm-night-audit mm-bar mm-rst','Night audit',{}),
 ('bar/sales/create','New sale','Pick the guest, add the drinks sold and confirm the invoice.',FORM+' mm-rst-sale','Sale',{}),
 ('bar/sales/return/create','New sale return','Find the original invoice, enter the quantities returned and confirm.',FORM+' mm-rst-sale','Sale return',{}),
 ('bar/purchase/show','Purchase details','Printable purchase record. Printing outputs the document only.',INV,'Purchase',{}),
 ('bar/sales/show','Sale invoice','Bar invoice. Printing outputs the document only.',INV,'Invoice',{}),
 ('bar/sales/return/show','Sale return details','Items returned against a bar invoice.',INV,'Sale return',{}),
 ('bar/purchase-v2/create','Purchase create','Choose the supplier and account, add products and submit the purchase.',FORM+' mm-rst-purchase mm-rst-purchase-create','Purchase',{}),
]
for f,t,d,c,l,kw in J:
    p=V+f+'.blade.php'
    subprocess.check_call(['git','checkout','-q','HEAD','--',p])
    try: migrate(f,t,d,c,l,**kw)
    except Exception as e: print('ERR',f,e)
