const http=require('http'),fs=require('fs'),path=require('path');
const root=path.resolve(__dirname,'..');
const {compose}=require(path.join(root,'tools/browser/compose-fixture.cjs'));
const types={'.html':'text/html; charset=utf-8','.css':'text/css','.js':'text/javascript','.woff2':'font/woff2','.woff':'font/woff','.ttf':'font/ttf','.png':'image/png','.svg':'image/svg+xml'};
const note='<div role="note" style="padding:12px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a"><strong>Visual preview \u00b7 sample data only</strong><br>Admin shell, header, footer and room board design. Not connected to Laravel, bookings or the hotel database. Menu destinations are demonstration links.</div>';

const fix=n=>fs.readFileSync(path.join(root,'tools/fixtures',n),'utf8');
const pages={
 '/preview/checkout':{file:'preview/checkout.html',title:'Checkout and payment',crumbs:['Hotel','Booking','Checkout']},
 '/preview/setup-amenities':{file:'preview/setup-aminities-index.html',title:'Amenities',crumbs:['Hotel', 'Setup', 'Amenities']},
 '/preview/setup-amenity-create':{file:'preview/setup-aminities-create.html',title:'Add amenity',crumbs:['Hotel', 'Setup', 'Amenities', 'Add']},
 '/preview/setup-account-types':{file:'preview/setup-account_type-index.html',title:'Account types',crumbs:['Hotel', 'Setup', 'Account types']},
 '/preview/setup-vat':{file:'preview/setup-vat-index.html',title:'VAT and services',crumbs:['Hotel', 'Setup', 'VAT']},
 '/preview/setup-currency':{file:'preview/setup-currency-conversions-index.html',title:'Currency conversions',crumbs:['Hotel', 'Setup', 'Currency conversions']},
 '/preview/setup-registration-terms':{file:'preview/setup-guest-registration-terms-index.html',title:'Registration terms',crumbs:['Hotel', 'Setup', 'Registration terms']},
 '/preview/report-cash-flow':{file:'preview/report-cash-flow.html',title:'Cash flow',crumbs:['Hotel', 'Reports', 'Cash flow']},
 '/preview/report-expected-arrival':{file:'preview/report-expected-arrival.html',title:'Expected arrival list',crumbs:['Hotel', 'Reports', 'Expected arrival list']},
 '/preview/report-room-logs':{file:'preview/report-room-logs.html',title:'Room logs',crumbs:['Hotel', 'Reports', 'Room logs']},
 '/preview/report-today-activities':{file:'preview/report-today-activities.html',title:'Today report',crumbs:['Hotel', 'Reports', 'Today report']},
 '/preview/report-night-closing':{file:'preview/report-night-closing.html',title:'Booking night audit report',crumbs:['Hotel', 'Reports', 'Booking night audit report']},
 '/preview/hotel-sms':{file:'preview/hotel-sms.html',title:'Guest SMS',crumbs:['Hotel','Guests','SMS']},
 '/preview/hotel-night-audit-show':{file:'preview/hotel-night-audit-show.html',title:'Night audit detail',crumbs:['Hotel','Night audit','Detail']},
 '/preview/hotel-monthly':{file:'preview/hotel-monthly.html',title:'Hotel monthly report',crumbs:['Hotel','Reports','Monthly']},
 '/preview/hotel-booking-adjust':{file:'preview/hotel-booking-adjust.html',title:'Booking migration',crumbs:['Hotel','Booking','Migration']},
 '/preview/hservice-services':{file:'preview/hservice-services.html',title:'Hotel services',crumbs:['Hotel service','Services']},
 '/preview/hservice-sales':{file:'preview/hservice-sales.html',title:'Hotel service sales',crumbs:['Hotel service','Sales']},
 '/preview/hservice-sale-create':{file:'preview/hservice-sale-create.html',title:'New hotel service sale',crumbs:['Hotel service','Sales','New']},
 '/preview/hservice-sale-show':{file:'preview/hservice-sale-show.html',title:'Hotel service invoice',crumbs:['Hotel service','Sales','Invoice']},
 '/preview/hservice-audits':{file:'preview/hservice-audits.html',title:'Hotel service night audit',crumbs:['Hotel service','Night audit']},
 '/preview/perm-module':{file:'preview/perm-module.html',title:'Modules',crumbs:['Permission','Modules']},
 '/preview/perm-submodule':{file:'preview/perm-submodule.html',title:'Sub modules',crumbs:['Permission','Sub modules']},
 '/preview/perm-parent-permission':{file:'preview/perm-parent-permission.html',title:'Parent permissions',crumbs:['Permission','Parent permissions']},
 '/preview/perm-permission-index':{file:'preview/perm-permission-index.html',title:'User permissions',crumbs:['Permission','User permissions']},
 '/preview/perm-permission-create':{file:'preview/perm-permission-create.html',title:'Create permission',crumbs:['Permission','Create permission']},
 '/preview/perm-permission-edit':{file:'preview/perm-permission-edit.html',title:'Edit permission',crumbs:['Permission','Edit permission']},
 '/preview/perm-users-index':{file:'preview/perm-users-index.html',title:'Permitted users',crumbs:['Permission','Permitted users']},
 '/preview/perm-users-create':{file:'preview/perm-users-create.html',title:'Add new user',crumbs:['Permission','Add new user']},
 '/preview/perm-change-password':{file:'preview/perm-change-password.html',title:'Change password',crumbs:['Permission','Change password']},
 '/preview/perm-change-password-admin':{file:'preview/perm-change-password-admin.html',title:'Change user password',crumbs:['Permission','Change user password']},
 '/preview/perm-access-create':{file:'preview/perm-access-create.html',title:'User role and permissions',crumbs:['Permission','User role and permissions']},
 '/preview/perm-access-edit':{file:'preview/perm-access-edit.html',title:'Edit user permissions',crumbs:['Permission','Edit user permissions']},
 '/preview/perm-employee-permission':{file:'preview/perm-employee-permission.html',title:'Employee permissions',crumbs:['Permission','Employee permissions']},
 '/preview/rst-tables':{file:'preview/rst-tables.html',title:'Tables',crumbs:['Restaurant','Tables']},
 '/preview/rst-kitchen-list':{file:'preview/rst-kitchen-list.html',title:'Kitchen orders',crumbs:['Restaurant','Kitchen orders']},
 '/preview/rst-kitchen-board':{file:'preview/rst-kitchen-board.html',title:'Kitchen board',crumbs:['Restaurant','Kitchen board']},
 '/preview/rst-kitchen-show':{file:'preview/rst-kitchen-show.html',title:'Order details',crumbs:['Restaurant','Order details']},
 '/preview/rst-audit-list':{file:'preview/rst-audit-list.html',title:'Restaurant night audit',crumbs:['Restaurant','Night audit']},
 '/preview/rst-audit-generate':{file:'preview/rst-audit-generate.html',title:'Generate restaurant night audit',crumbs:['Restaurant','Generate night audit']},
 '/preview/rst-payment-collection':{file:'preview/rst-payment-collection.html',title:'Restaurant payment collection',crumbs:['Restaurant','Payment collection']},
 '/preview/rst-report-cash-flow':{file:'preview/rst-report-cash-flow.html',title:'Cash flow',crumbs:['Restaurant','Cash flow']},
 '/preview/rst-report-sales':{file:'preview/rst-report-sales.html',title:'Sales report',crumbs:['Restaurant','Sales report']},
 '/preview/rst-report-today':{file:'preview/rst-report-today.html',title:'Today\'s activities',crumbs:['Restaurant','Today\'s activities']},
 '/preview/rst-report-inventory':{file:'preview/rst-report-inventory.html',title:'Product inventory',crumbs:['Restaurant','Product inventory']},
 '/preview/rst-report-ledger':{file:'preview/rst-report-ledger.html',title:'Stock ledger',crumbs:['Restaurant','Stock ledger']},
 '/preview/rst-sales-list':{file:'preview/rst-sales-list.html',title:'Sale list',crumbs:['Restaurant','Sale list']},
 '/preview/rst-sales-show':{file:'preview/rst-sales-show.html',title:'Sale invoice',crumbs:['Restaurant','Sale invoice']},
 '/preview/rst-sales-create':{file:'preview/rst-sales-create.html',title:'New sale',crumbs:['Restaurant','New sale']},
 '/preview/rst-return-list':{file:'preview/rst-return-list.html',title:'Sale return list',crumbs:['Restaurant','Sale return list']},
 '/preview/rst-return-show':{file:'preview/rst-return-show.html',title:'Sale return details',crumbs:['Restaurant','Sale return details']},
 '/preview/rst-return-create':{file:'preview/rst-return-create.html',title:'New sale return',crumbs:['Restaurant','New sale return']},
 '/preview/rst-purchase-list':{file:'preview/rst-purchase-list.html',title:'Purchase list',crumbs:['Restaurant','Purchase list']},
 '/preview/rst-purchase-show':{file:'preview/rst-purchase-show.html',title:'Purchase requisition',crumbs:['Restaurant','Purchase requisition']},
 '/preview/rst-purchase-approve':{file:'preview/rst-purchase-approve.html',title:'Purchase approve',crumbs:['Restaurant','Purchase approve']},
 '/preview/rst-purchase-create':{file:'preview/rst-purchase-create.html',title:'Purchase create',crumbs:['Restaurant','Purchase create']},
 '/preview/rsi-adjustment-view':{file:'preview/rsi-adjustment-view.html',title:'Stock adjustment view',crumbs:['Restaurant inventory','Stock adjustment view']},
 '/preview/rsi-adjustments':{file:'preview/rsi-adjustments.html',title:'Stock adjustments',crumbs:['Restaurant inventory','Stock adjustments']},
 '/preview/rsi-categories':{file:'preview/rsi-categories.html',title:'Categories',crumbs:['Restaurant inventory','Categories']},
 '/preview/rsi-form-adjustment-create':{file:'preview/rsi-form-adjustment-create.html',title:'Adjustment create',crumbs:['Restaurant inventory','Adjustment create']},
 '/preview/rsi-form-adjustment-edit':{file:'preview/rsi-form-adjustment-edit.html',title:'Adjustment edit',crumbs:['Restaurant inventory','Adjustment edit']},
 '/preview/rsi-form-item-create':{file:'preview/rsi-form-item-create.html',title:'Item create',crumbs:['Restaurant inventory','Item create']},
 '/preview/rsi-form-item-edit':{file:'preview/rsi-form-item-edit.html',title:'Item edit',crumbs:['Restaurant inventory','Item edit']},
 '/preview/rsi-form-item-unit-create':{file:'preview/rsi-form-item-unit-create.html',title:'Item unit create',crumbs:['Restaurant inventory','Item unit create']},
 '/preview/rsi-form-item-unit-edit':{file:'preview/rsi-form-item-unit-edit.html',title:'Item unit edit',crumbs:['Restaurant inventory','Item unit edit']},
 '/preview/rsi-form-mat-create':{file:'preview/rsi-form-mat-create.html',title:'Mat create',crumbs:['Restaurant inventory','Mat create']},
 '/preview/rsi-form-mat-edit':{file:'preview/rsi-form-mat-edit.html',title:'Mat edit',crumbs:['Restaurant inventory','Mat edit']},
 '/preview/rsi-form-product-create':{file:'preview/rsi-form-product-create.html',title:'Product create',crumbs:['Restaurant inventory','Product create']},
 '/preview/rsi-form-product-edit':{file:'preview/rsi-form-product-edit.html',title:'Product edit',crumbs:['Restaurant inventory','Product edit']},
 '/preview/rsi-form-product-upload':{file:'preview/rsi-form-product-upload.html',title:'Product upload',crumbs:['Restaurant inventory','Product upload']},
 '/preview/rsi-form-purchase-approve':{file:'preview/rsi-form-purchase-approve.html',title:'Purchase approve',crumbs:['Restaurant inventory','Purchase approve']},
 '/preview/rsi-form-purchase-create':{file:'preview/rsi-form-purchase-create.html',title:'Purchase create',crumbs:['Restaurant inventory','Purchase create']},
 '/preview/rsi-form-purchase-edit':{file:'preview/rsi-form-purchase-edit.html',title:'Purchase edit',crumbs:['Restaurant inventory','Purchase edit']},
 '/preview/rsi-form-requisition-create':{file:'preview/rsi-form-requisition-create.html',title:'Requisition create',crumbs:['Restaurant inventory','Requisition create']},
 '/preview/rsi-form-upload-edit':{file:'preview/rsi-form-upload-edit.html',title:'Upload edit',crumbs:['Restaurant inventory','Upload edit']},
 '/preview/rsi-inventory-report':{file:'preview/rsi-inventory-report.html',title:'Inventory report',crumbs:['Restaurant inventory','Inventory report']},
 '/preview/rsi-manufacturers':{file:'preview/rsi-manufacturers.html',title:'Manufacturers',crumbs:['Restaurant inventory','Manufacturers']},
 '/preview/rsi-mat-products':{file:'preview/rsi-mat-products.html',title:'Mat products',crumbs:['Restaurant inventory','Mat products']},
 '/preview/rsi-product-uploads':{file:'preview/rsi-product-uploads.html',title:'Product uploads',crumbs:['Restaurant inventory','Product uploads']},
 '/preview/rsi-production-item-units':{file:'preview/rsi-production-item-units.html',title:'Production item units',crumbs:['Restaurant inventory','Production item units']},
 '/preview/rsi-production-items':{file:'preview/rsi-production-items.html',title:'Production items',crumbs:['Restaurant inventory','Production items']},
 '/preview/rsi-production-purchases':{file:'preview/rsi-production-purchases.html',title:'Production purchases',crumbs:['Restaurant inventory','Production purchases']},
 '/preview/rsi-production-requisitions':{file:'preview/rsi-production-requisitions.html',title:'Production requisitions',crumbs:['Restaurant inventory','Production requisitions']},
 '/preview/rsi-products':{file:'preview/rsi-products.html',title:'Products',crumbs:['Restaurant inventory','Products']},
 '/preview/rsi-purchase-show':{file:'preview/rsi-purchase-show.html',title:'Purchase show',crumbs:['Restaurant inventory','Purchase show']},
 '/preview/rsi-suppliers':{file:'preview/rsi-suppliers.html',title:'Suppliers',crumbs:['Restaurant inventory','Suppliers']},
 '/preview/rsi-units':{file:'preview/rsi-units.html',title:'Units',crumbs:['Restaurant inventory','Units']},
 '/preview/night-audit':{file:'preview/night-audit-index.html',title:'Night audit',crumbs:['Hotel','Night audit']},
 '/preview/night-audit-generate':{file:'preview/night-audit-create.html',title:'Generate night audit',crumbs:['Hotel','Night audit','Generate']},
 '/preview/payment-collection':{file:'preview/payment-collection.html',title:'Payment collection',crumbs:['Hotel','Booking','Payment collection']},
 '/preview/invoice':{file:'preview/invoice.html',title:'Booking invoice',crumbs:['Hotel','Booking','Invoice']},
 '/preview/gs-item-units':{file:'preview/gs-item-units.html',title:'Item units',crumbs:['General Store','Item units']},
 '/preview/gs-form-item-unit-create':{file:'preview/gs-form-item-unit-create.html',title:'Add item unit',crumbs:['General Store','Add item unit']},
 '/preview/gs-form-item-unit-edit':{file:'preview/gs-form-item-unit-edit.html',title:'Edit item unit',crumbs:['General Store','Edit item unit']},
 '/preview/gs-items':{file:'preview/gs-items.html',title:'Item list',crumbs:['General Store','Item list']},
 '/preview/gs-form-item-create':{file:'preview/gs-form-item-create.html',title:'Add item',crumbs:['General Store','Add item']},
 '/preview/gs-form-item-edit':{file:'preview/gs-form-item-edit.html',title:'Edit item',crumbs:['General Store','Edit item']},
 '/preview/gs-form-item-upload':{file:'preview/gs-form-item-upload.html',title:'Upload items',crumbs:['General Store','Upload items']},
 '/preview/gs-suppliers':{file:'preview/gs-suppliers.html',title:'Suppliers',crumbs:['General Store','Suppliers']},
 '/preview/gs-form-supplier-create':{file:'preview/gs-form-supplier-create.html',title:'Add supplier',crumbs:['General Store','Add supplier']},
 '/preview/gs-form-supplier-edit':{file:'preview/gs-form-supplier-edit.html',title:'Edit supplier',crumbs:['General Store','Edit supplier']},
 '/preview/gs-supplier-types':{file:'preview/gs-supplier-types.html',title:'Supplier types',crumbs:['General Store','Supplier types']},
 '/preview/category-create':{file:'preview/category-create.html',title:'Add a room category',crumbs:['Hotel','Category','Create']},
};
const between=(h,a,b)=>{const i=h.indexOf(a);return h.slice(i+a.length,h.indexOf(b,i+a.length));};
function shellWith(content,head,js,title,crumbs){
 let raw=fix('dashboard.html');
 const a='<!--SHELL-TOOLBAR-->',b='</div></div></div><!--SHELL-FOOTER-->';
 const i=raw.indexOf(a)+a.length,j=raw.indexOf(b);
 raw=raw.slice(0,i)+'\n<!--MM-PAGE-->'+raw.slice(j);
 let page=compose('dashboard.html',raw);
 const banner='<div role="note" style="padding:10px 14px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a"><strong>Visual preview · sample data only.</strong> '+title+' — nothing here is saved. <a href="/preview">All preview screens</a> · <a href="/">Dashboard</a></div>';
 crumbs=crumbs||['Preview','Screens'];
 page=page.replace(/<li><span>Hotel<\/span><\/li>\s*<li><span>Booking<\/span><\/li>\s*<li><span aria-current="page">Create<\/span><\/li>/,()=>crumbs.slice(0,-1).map(c=>'<li><span>'+c+'</span></li>').join('')+'<li><span aria-current="page">'+crumbs[crumbs.length-1]+'</span></li>');
 page=page.replace('<!--MM-PAGE-->',()=>banner+content);
 page=page.replace('</head>',()=>head+'</head>').replace('</body>',()=>js+'</body>');
 return page;
}
function screen(name){
 const html=fix(pages[name].file);
 return shellWith(between(html,'<!--MM-BODY-->','<!--/MM-BODY-->'),between(html,'<!--MM-HEAD-->','<!--/MM-HEAD-->'),between(html,'<!--MM-JS-->','<!--/MM-JS-->').replace(/window\.addEventListener\('load'[\s\S]*?\}\);\s*(?=<\/script>)/,'/* auto-print disabled in the preview */'),pages[name].title,pages[name].crumbs);
}
function listPage(extra){
 const items=[['/','Dashboard and room booking board','Room board with the stay date range, quick chips and summary cards.'],...Object.entries(pages).map(([p,v])=>[p,v.title,p==='/preview/checkout'?'Night +/- recalculates totals, discount, paid amount and full payment (real calculation script).':p==='/preview/payment-collection'?'Search a guest, review unpaid invoices, enter a payment or tick full payment (real script).':p.startsWith('/preview/report-')?'Hotel report rendered from the real Blade view and export table with sample rows; the filter bar sits above the results.':p.startsWith('/preview/setup-')?'Hotel setup screen rendered from the real Blade view with sample records.':p.startsWith('/preview/perm-')?'Permission screen rendered from the real Blade view with sample data; the access matrices keep their checkbox scripts.':p.startsWith('/preview/hservice-')?'Hotel service screen rendered from the real Blade view with sample data; the sale form keeps its calculation script.':p==='/preview/night-audit'?'Closed audit days with totals and the shared export table.':p==='/preview/night-audit-generate'?'Review the day\'s collections and generate the audit (10-second delay kept from the legacy form).':p==='/preview/invoice'?'Printable booking invoice; the Print button prints the document only.':'Real category form with sample amenities.'])];
 const body=(extra?'<div class="mm-panel tw-p-4" style="margin-bottom:16px"><strong>'+extra+'</strong></div>':'')+'<section class="mm-ui"><div class="mm-panel tw-p-5"><h2 style="margin-top:0">Screens available in this preview</h2><p>This preview is a static sample, not the Laravel app, so only the screens below are wired up. Other menu items are demonstration links.</p><ul style="line-height:2">'+items.map(([p,t,d])=>'<li><a href="'+p+'"><strong>'+t+'</strong></a> — '+d+'</li>').join('')+'</ul></div></section>';
 return shellWith(body,'','','Screens');
}
function menuLinks(page){
 const nav='<li><a href="/preview"><i class="menu-icon fa fa-eye"></i><span class="menu-text">Preview screens</span></a><b class="arrow"></b></li><li><a href="/preview/checkout"><i class="menu-icon fa fa-credit-card"></i><span class="menu-text">Checkout and payment</span></a><b class="arrow"></b></li><li><a href="/preview/payment-collection"><i class="menu-icon fa fa-money"></i><span class="menu-text">Payment collection</span></a><b class="arrow"></b></li><li><a href="/preview/setup-amenities"><i class="menu-icon fa fa-gears"></i><span class="menu-text">Amenities</span></a><b class="arrow"></b></li><li><a href="/preview/setup-amenity-create"><i class="menu-icon fa fa-plus"></i><span class="menu-text">Add amenity</span></a><b class="arrow"></b></li><li><a href="/preview/setup-account-types"><i class="menu-icon fa fa-credit-card"></i><span class="menu-text">Account types</span></a><b class="arrow"></b></li><li><a href="/preview/setup-vat"><i class="menu-icon fa fa-percent"></i><span class="menu-text">VAT and services</span></a><b class="arrow"></b></li><li><a href="/preview/setup-currency"><i class="menu-icon fa fa-exchange"></i><span class="menu-text">Currency conversions</span></a><b class="arrow"></b></li><li><a href="/preview/setup-registration-terms"><i class="menu-icon fa fa-file-text-o"></i><span class="menu-text">Registration terms</span></a><b class="arrow"></b></li><li><a href="/preview/report-cash-flow"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text">Cash flow</span></a><b class="arrow"></b></li><li><a href="/preview/report-expected-arrival"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text">Expected arrival list</span></a><b class="arrow"></b></li><li><a href="/preview/report-room-logs"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text">Room logs</span></a><b class="arrow"></b></li><li><a href="/preview/report-today-activities"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text">Today report</span></a><b class="arrow"></b></li><li><a href="/preview/report-night-closing"><i class="menu-icon fa fa-bar-chart"></i><span class="menu-text">Booking night audit report</span></a><b class="arrow"></b></li><li><a href="/preview/night-audit"><i class="menu-icon fa fa-moon-o"></i><span class="menu-text">Night audit</span></a><b class="arrow"></b></li><li><a href="/preview/night-audit-generate"><i class="menu-icon fa fa-check-square-o"></i><span class="menu-text">Generate night audit</span></a><b class="arrow"></b></li><li><a href="/preview/invoice"><i class="menu-icon fa fa-print"></i><span class="menu-text">Booking invoice</span></a><b class="arrow"></b></li><li><a href="/preview/category-create"><i class="menu-icon fa fa-bed"></i><span class="menu-text">Add room category</span></a><b class="arrow"></b></li>';
 return page.replace(/(id="mm-primary-menu"[^>]*>)/,(m)=>m+nav);
}
http.createServer((req,res)=>{
 const url=new URL(req.url,'http://preview');
 if(!url.pathname.startsWith('/assets/')){
  try{
   res.writeHead(200,{'Content-Type':types['.html'],'Cache-Control':'no-store'});
   const p=url.pathname.replace(/\/+$/,'')||'/';
   let page;
   if(req.method!=='GET'&&req.method!=='HEAD') page=listPage('Form submitted. This is a sample preview, so nothing was saved.');
   else if(p==='/'||p==='/home'){page=compose('dashboard.html').replace(/<li><span>Hotel<\/span><\/li>\s*<li><span>Booking<\/span><\/li>\s*<li><span aria-current="page">Create<\/span><\/li>/,'<li><span aria-current="page">Dashboard</span></li>');const picked=url.searchParams.get('booking_date');if(picked&&/^\d\d\/\d\d\/\d{4} - \d\d\/\d\d\/\d{4}$/.test(picked)){page=page.replace(/(name="booking_date" id="available_date" value=")[^"]*"/,'$1'+picked+'"').replace(/(name="booking_availabe" value=")[^"]*"/,'$1'+picked+'"');}}
   else if(pages[p]) page=screen(p);
   else if(p==='/preview') page=listPage();
   else page=listPage('“'+p.replace(/[<>&"]/g,'')+'” is not part of this visual preview.');
   return res.end(menuLinks(page));
  }catch(e){res.writeHead(500);return res.end(String(e));}
 }
 const file=path.resolve(root,'public','.'+decodeURIComponent(url.pathname));
 if(!file.startsWith(root+'/public/assets/')){res.writeHead(403);return res.end();}
 fs.readFile(file,(err,data)=>{
  if(err){res.writeHead(404);return res.end('Not found');}
  res.writeHead(200,{'Content-Type':types[path.extname(file)]||'application/octet-stream','Cache-Control':'no-store'});res.end(data);
 });
}).listen(3000,'0.0.0.0');
