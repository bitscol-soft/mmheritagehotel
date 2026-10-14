const http=require('http'),fs=require('fs'),path=require('path');
const root=path.resolve(__dirname,'..');
const {compose}=require(path.join(root,'tools/browser/compose-fixture.cjs'));
const types={'.html':'text/html; charset=utf-8','.css':'text/css','.js':'text/javascript','.woff2':'font/woff2','.woff':'font/woff','.ttf':'font/ttf','.png':'image/png','.svg':'image/svg+xml','.pdf':'application/pdf'};
const note='<div role="note" style="padding:12px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a"><strong>Visual preview \u00b7 sample data only</strong><br>Admin shell, header, footer and room board design. Not connected to Laravel, bookings or the hotel database. Menu destinations are demonstration links.</div>';

const fix=n=>fs.readFileSync(path.join(root,'tools/fixtures',n),'utf8');
const pages={
 '/preview/booking-next':{file:'preview/booking-next.html',title:'New room booking (3-step wizard)',crumbs:['Hotel','Booking','Create']},
 '/preview/checkout':{file:'preview/checkout.html',title:'Checkout and payment',crumbs:['Hotel','Booking','Checkout']},
 '/preview/setup-amenities':{file:'preview/setup-aminities-index.html',title:'Amenities',crumbs:['Hotel', 'Setup', 'Amenities']},
 '/preview/setup-amenity-create':{file:'preview/setup-aminities-create.html',title:'Add amenity',crumbs:['Hotel', 'Setup', 'Amenities', 'Add']},
 '/preview/setup-account-types':{file:'preview/setup-account_type-index.html',title:'Account types',crumbs:['Hotel', 'Setup', 'Account types']},
 '/preview/setup-vat':{file:'preview/setup-vat-index.html',title:'VAT and services',crumbs:['Hotel', 'Setup', 'VAT']},
 '/preview/setup-currency':{file:'preview/setup-currency-conversions-index.html',title:'Currency conversions',crumbs:['Hotel', 'Setup', 'Currency conversions']},
 '/preview/setup-registration-terms':{file:'preview/setup-guest-registration-terms-index.html',title:'Registration terms',crumbs:['Hotel', 'Setup', 'Registration terms']},
 '/preview/report-cash-flow':{file:'preview/report-cash-flow.html',title:'Cash flow',crumbs:['Hotel', 'Reports', 'Cash flow']},
 '/preview/report-all-reports':{file:'hotel-reports/all-reports.html',title:'All booking reports',crumbs:['Hotel', 'Reports', 'All booking reports']},
 '/preview/report-services':{file:'hotel-reports/services.html',title:'Service wise report',crumbs:['Hotel', 'Reports', 'Service wise report']},
 '/preview/report-today-check-in':{file:'hotel-reports/today-check-in.html',title:'Today check in list',crumbs:['Hotel', 'Reports', 'Today check in list']},
 '/preview/report-today-check-out':{file:'hotel-reports/today-check-out.html',title:'Today check out list',crumbs:['Hotel', 'Reports', 'Today check out list']},
 '/preview/report-today-in-house':{file:'hotel-reports/today-in-house.html',title:'Today in house list',crumbs:['Hotel', 'Reports', 'Today in house list']},
 '/preview/report-vat-day':{file:'hotel-reports/vat-report-day.html',title:'Daily VAT report',crumbs:['Hotel', 'Reports', 'Daily VAT report']},
 '/preview/report-vat-monthly':{file:'hotel-reports/vat-report-monthly.html',title:'Monthly VAT report',crumbs:['Hotel', 'Reports', 'Monthly VAT report']},
 '/preview/report-expected-arrival':{file:'preview/report-expected-arrival.html',title:'Expected arrival list',crumbs:['Hotel', 'Reports', 'Expected arrival list']},
 '/preview/report-expected-departure':{file:'hotel-reports/expected-departure.html',title:'Expected departure list',crumbs:['Hotel', 'Reports', 'Expected departure list']},
 '/preview/report-in-house-guest':{file:'hotel-reports/in-house-guest.html',title:'In-house guest list',crumbs:['Hotel', 'Reports', 'In-house guest list']},
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
 '/preview/perm-users':{file:'preview/perm-users-index.html',title:'Permitted users',crumbs:['Permission','Permitted users']},
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
 '/preview/rst-pos':{file:'preview/rst-pos-create.html',title:'Restaurant POS (sales-v2)',crumbs:['Restaurant','POS']},
 '/preview/rst-pos-create':{file:'preview/rst-pos-create.html',title:'Restaurant POS (sales-v2)',crumbs:['Restaurant','POS']},
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
 '/preview/gs-purchases':{file:'preview/gs-purchases.html',title:'Purchase list',crumbs:['General Store','Purchase list']},
 '/preview/gs-purchase-show':{file:'preview/gs-purchase-show.html',title:'Purchase requisition',crumbs:['General Store','Purchase requisition']},
 '/preview/gs-form-purchase-approve':{file:'preview/gs-form-purchase-approve.html',title:'Purchase approve',crumbs:['General Store','Purchase approve']},
 '/preview/gs-form-purchase-create':{file:'preview/gs-form-purchase-create.html',title:'Create purchase',crumbs:['General Store','Create purchase']},
 '/preview/gs-form-purchase-edit':{file:'preview/gs-form-purchase-edit.html',title:'Edit purchase',crumbs:['General Store','Edit purchase']},
 '/preview/gs-grn-list':{file:'preview/gs-grn-list.html',title:'GRN list',crumbs:['General Store','GRN list']},
 '/preview/gs-receive-list':{file:'preview/gs-receive-list.html',title:'Purchase receive list',crumbs:['General Store','Purchase receive list']},
 '/preview/gs-form-receive-create':{file:'preview/gs-form-receive-create.html',title:'Purchase receive',crumbs:['General Store','Purchase receive']},
 '/preview/gs-gr-list':{file:'preview/gs-gr-list.html',title:'Goods requisition list',crumbs:['General Store','Goods requisition list']},
 '/preview/gs-gin-list':{file:'preview/gs-gin-list.html',title:'GIN list',crumbs:['General Store','GIN list']},
 '/preview/gs-form-gr-approve':{file:'preview/gs-form-gr-approve.html',title:'Approve goods requisition',crumbs:['General Store','Approve goods requisition']},
 '/preview/gs-form-gr-create':{file:'preview/gs-form-gr-create.html',title:'Create goods requisition',crumbs:['General Store','Create goods requisition']},
 '/preview/gs-form-gr-edit':{file:'preview/gs-form-gr-edit.html',title:'Edit goods requisition',crumbs:['General Store','Edit goods requisition']},
 '/preview/gs-weekly-movement':{file:'preview/gs-weekly-movement.html',title:'Weekly movement',crumbs:['General Store','Weekly movement']},
 '/preview/gs-stock-in-hand':{file:'preview/gs-stock-in-hand.html',title:'Stock in hand',crumbs:['General Store','Stock in hand']},
 '/preview/gs-item-ledger':{file:'preview/gs-item-ledger.html',title:'Item ledger',crumbs:['General Store','Item ledger']},
 '/preview/bar-audit-generate':{file:'preview/bar-audit-generate.html',title:'Generate bar night audit',crumbs:['Bar','Generate bar night audit']},
 '/preview/bar-audit-list':{file:'preview/bar-audit-list.html',title:'Bar night audit',crumbs:['Bar','Bar night audit']},
 '/preview/bar-categories':{file:'preview/bar-categories.html',title:'Product categories',crumbs:['Bar','Product categories']},
 '/preview/bar-inventory-report':{file:'preview/bar-inventory-report.html',title:'Product inventory',crumbs:['Bar','Product inventory']},
 '/preview/bar-manufacturers':{file:'preview/bar-manufacturers.html',title:'Manufacturers',crumbs:['Bar','Manufacturers']},
 '/preview/bar-product-create':{file:'preview/bar-product-create.html',title:'Add product',crumbs:['Bar','Add product']},
 '/preview/bar-product-edit':{file:'preview/bar-product-edit.html',title:'Edit product',crumbs:['Bar','Edit product']},
 '/preview/bar-products':{file:'preview/bar-products.html',title:'Products',crumbs:['Bar','Products']},
 '/preview/bar-purchase-list':{file:'preview/bar-purchase-list.html',title:'Purchase list',crumbs:['Bar','Purchase list']},
 '/preview/bar-purchase-show':{file:'preview/bar-purchase-show.html',title:'Purchase details',crumbs:['Bar','Purchase details']},
 '/preview/bar-report-cash-flow':{file:'preview/bar-report-cash-flow.html',title:'Cash flow',crumbs:['Bar','Cash flow']},
 '/preview/bar-report-inventory':{file:'preview/bar-report-inventory.html',title:'Product inventory',crumbs:['Bar','Product inventory']},
 '/preview/bar-report-sales':{file:'preview/bar-report-sales.html',title:'Sales report',crumbs:['Bar','Sales report']},
 '/preview/bar-report-today':{file:'preview/bar-report-today.html',title:'Today activities',crumbs:['Bar','Today activities']},
 '/preview/bar-return-create':{file:'preview/bar-return-create.html',title:'New sale return',crumbs:['Bar','New sale return']},
 '/preview/bar-return-list':{file:'preview/bar-return-list.html',title:'Sale return list',crumbs:['Bar','Sale return list']},
 '/preview/bar-return-show':{file:'preview/bar-return-show.html',title:'Sale return details',crumbs:['Bar','Sale return details']},
 '/preview/bar-sales-create':{file:'preview/bar-sales-create.html',title:'New sale',crumbs:['Bar','New sale']},
 '/preview/bar-pos':{file:'preview/bar-pos-create.html',title:'Bar POS (sales-v2)',crumbs:['Bar','POS']},
 '/preview/bar-pos-create':{file:'preview/bar-pos-create.html',title:'Bar POS (sales-v2)',crumbs:['Bar','POS']},
 '/preview/bar-sales-list':{file:'preview/bar-sales-list.html',title:'Sale list',crumbs:['Bar','Sale list']},
 '/preview/bar-sales-show':{file:'preview/bar-sales-show.html',title:'Sale invoice',crumbs:['Bar','Sale invoice']},
 '/preview/bar-suppliers':{file:'preview/bar-suppliers.html',title:'Suppliers',crumbs:['Bar','Suppliers']},
 '/preview/bar-tables':{file:'preview/bar-tables.html',title:'Tables',crumbs:['Bar','Tables']},
 '/preview/bar-units':{file:'preview/bar-units.html',title:'Units',crumbs:['Bar','Units']},
 '/preview/acc-account-controls':{file:'preview/acc-account-controls.html',title:'Account Controls',crumbs:['Account','Account Controls']},
 '/preview/acc-account-groups':{file:'preview/acc-account-groups.html',title:'Account Group',crumbs:['Account','Account Group']},
 '/preview/acc-accounts':{file:'preview/acc-accounts.html',title:'Chart Of Accounts',crumbs:['Account','Chart Of Accounts']},
 '/preview/acc-categories':{file:'preview/acc-categories.html',title:'Category List',crumbs:['Account','Category List']},
 '/preview/acc-customers':{file:'preview/acc-customers.html',title:'Customers',crumbs:['Account','Customers']},
 '/preview/acc-form-account-create':{file:'preview/acc-form-account-create.html',title:'Account',crumbs:['Account','Account']},
 '/preview/acc-form-category-create':{file:'preview/acc-form-category-create.html',title:'Category Create',crumbs:['Account','Category Create']},
 '/preview/acc-form-category-edit':{file:'preview/acc-form-category-edit.html',title:'Category Edit',crumbs:['Account','Category Edit']},
 '/preview/acc-form-control-create':{file:'preview/acc-form-control-create.html',title:'Account Controls',crumbs:['Account','Account Controls']},
 '/preview/acc-form-control-edit':{file:'preview/acc-form-control-edit.html',title:'Account Control Edit',crumbs:['Account','Account Control Edit']},
 '/preview/acc-form-customer-create':{file:'preview/acc-form-customer-create.html',title:'Customer Create',crumbs:['Account','Customer Create']},
 '/preview/acc-form-customer-edit':{file:'preview/acc-form-customer-edit.html',title:'Customer Edit',crumbs:['Account','Customer Edit']},
 '/preview/acc-form-product-create':{file:'preview/acc-form-product-create.html',title:'Product Create',crumbs:['Account','Product Create']},
 '/preview/acc-form-product-edit':{file:'preview/acc-form-product-edit.html',title:'Product Edit',crumbs:['Account','Product Edit']},
 '/preview/acc-form-subsidiary-create':{file:'preview/acc-form-subsidiary-create.html',title:'Account Subsidiary',crumbs:['Account','Account Subsidiary']},
 '/preview/acc-form-supplier-create':{file:'preview/acc-form-supplier-create.html',title:'Supplier Create',crumbs:['Account','Supplier Create']},
 '/preview/acc-form-supplier-edit':{file:'preview/acc-form-supplier-edit.html',title:'Supplier Edit',crumbs:['Account','Supplier Edit']},
 '/preview/acc-form-unit-create':{file:'preview/acc-form-unit-create.html',title:'Unit Create',crumbs:['Account','Unit Create']},
 '/preview/acc-opening-balances':{file:'preview/acc-opening-balances.html',title:'Account Opening Balance',crumbs:['Account','Account Opening Balance']},
 '/preview/acc-products':{file:'preview/acc-products.html',title:'Product List',crumbs:['Account','Product List']},
 '/preview/acc-subsidiaries':{file:'preview/acc-subsidiaries.html',title:'Account Subsidiaries',crumbs:['Account','Account Subsidiaries']},
 '/preview/acc-suppliers':{file:'preview/acc-suppliers.html',title:'Suppliers',crumbs:['Account','Suppliers']},
 '/preview/acc-units':{file:'preview/acc-units.html',title:'Units',crumbs:['Account','Units']},
 '/preview/acc-opening-balances-data':{file:'preview/acc-opening-balances-data.html',title:'Account Opening Balance',crumbs:['Account','Account Opening Balance']},
 '/preview/acc-contras-show':{file:'preview/acc-contras-show.html',title:'Contra Voucher Details',crumbs:['Account','Contra Voucher Details']},
 '/preview/acc-purchases':{file:'preview/acc-purchases.html',title:'Purchase List',crumbs:['Account','Purchase List']},
 '/preview/acc-form-purchases-create':{file:'preview/acc-form-purchases-create.html',title:'Purchase Create',crumbs:['Account','Purchase Create']},
 '/preview/acc-form-purchases-edit':{file:'preview/acc-form-purchases-edit.html',title:'Edit Purchase',crumbs:['Account','Edit Purchase']},
 '/preview/acc-purchase-returns':{file:'preview/acc-purchase-returns.html',title:'Purchase Return List',crumbs:['Account','Purchase Return List']},
 '/preview/acc-form-purchase-returns-create':{file:'preview/acc-form-purchase-returns-create.html',title:'Purchase Return Create',crumbs:['Account','Purchase Return Create']},
 '/preview/acc-purchase-payments':{file:'preview/acc-purchase-payments.html',title:'Payment Lists',crumbs:['Account','Payment Lists']},
 '/preview/acc-sales':{file:'preview/acc-sales.html',title:'Sale List',crumbs:['Account','Sale List']},
 '/preview/acc-form-sales-create':{file:'preview/acc-form-sales-create.html',title:'Sale Create',crumbs:['Account','Sale Create']},
 '/preview/acc-form-sales-edit':{file:'preview/acc-form-sales-edit.html',title:'Edit Sale',crumbs:['Account','Edit Sale']},
 '/preview/acc-sale-returns':{file:'preview/acc-sale-returns.html',title:'Sale Return List',crumbs:['Account','Sale Return List']},
 '/preview/acc-form-sale-returns-create':{file:'preview/acc-form-sale-returns-create.html',title:'Sale Return Create',crumbs:['Account','Sale Return Create']},
 '/preview/acc-collections':{file:'preview/acc-collections.html',title:'Collection Lists',crumbs:['Account','Collection Lists']},
 '/preview/acc-damages':{file:'preview/acc-damages.html',title:'Damage List',crumbs:['Account','Damage List']},
 '/preview/acc-form-damages-create':{file:'preview/acc-form-damages-create.html',title:'Damage Create',crumbs:['Account','Damage Create']},
 '/preview/acc-account-ledger':{file:'preview/acc-account-ledger.html',title:'Account Ledger',crumbs:['Account','Account Ledger']},
 '/preview/acc-account-payables':{file:'preview/acc-account-payables.html',title:'Account Payable',crumbs:['Account','Account Payable']},
 '/preview/acc-account-receivables':{file:'preview/acc-account-receivables.html',title:'Account Receivable',crumbs:['Account','Account Receivable']},
 '/preview/acc-balance-sheet':{file:'preview/acc-balance-sheet.html',title:'Balance Sheet',crumbs:['Account','Balance Sheet']},
 '/preview/acc-cash-flow':{file:'preview/acc-cash-flow.html',title:'Cash Flow Report',crumbs:['Account','Cash Flow Report']},
 '/preview/acc-chart-of-account':{file:'preview/acc-chart-of-account.html',title:'Chart Of Account',crumbs:['Account','Chart Of Account']},
 '/preview/acc-customer-ledger':{file:'preview/acc-customer-ledger.html',title:'Customer Ledger',crumbs:['Account','Customer Ledger']},
 '/preview/acc-equity-statement':{file:'preview/acc-equity-statement.html',title:'Equity Statement',crumbs:['Account','Equity Statement']},
 '/preview/acc-expense-analysis':{file:'preview/acc-expense-analysis.html',title:'Expense Analysis',crumbs:['Account','Expense Analysis']},
 '/preview/acc-income-statement':{file:'preview/acc-income-statement.html',title:'Income  Statement',crumbs:['Account','Income  Statement']},
 '/preview/acc-journal-report':{file:'preview/acc-journal-report.html',title:'Ledger Journal',crumbs:['Account','Ledger Journal']},
 '/preview/acc-ratio-analysis':{file:'preview/acc-ratio-analysis.html',title:'Ratio Analysis',crumbs:['Account','Ratio Analysis']},
 '/preview/acc-received-payment-statement':{file:'preview/acc-received-payment-statement.html',title:'Received Payment Statement',crumbs:['Account','Received Payment Statement']},
 '/preview/acc-revenue-analysis':{file:'preview/acc-revenue-analysis.html',title:'Revenue Analysis',crumbs:['Account','Revenue Analysis']},
 '/preview/acc-subsidiary-wise-ledger':{file:'preview/acc-subsidiary-wise-ledger.html',title:'Subsidiary Wise Ledger',crumbs:['Account','Subsidiary Wise Ledger']},
 '/preview/acc-supplier-ledger':{file:'preview/acc-supplier-ledger.html',title:'Supplier Ledger',crumbs:['Account','Supplier Ledger']},
 '/preview/acc-supplier-report':{file:'preview/acc-supplier-report.html',title:'Supplier Report',crumbs:['Account','Supplier Report']},
 '/preview/acc-trial-balance':{file:'preview/acc-trial-balance.html',title:'Trial Balance',crumbs:['Account','Trial Balance']},
 '/preview/acc-voucher-reports':{file:'preview/acc-voucher-reports.html',title:'Voucer Reports',crumbs:['Account','Voucer Reports']},
 '/preview/acc-nominal-account-ledger':{file:'preview/acc-nominal-account-ledger.html',title:'Nominal Account Ledger',crumbs:['Account','Nominal Account Ledger']},
 '/preview/acc-transaction-ledger':{file:'preview/acc-transaction-ledger.html',title:'Transaction Ledger',crumbs:['Account','Transaction Ledger']},
 '/preview/acc-stock-in-hand':{file:'preview/acc-stock-in-hand.html',title:'Product Stock In Hand',crumbs:['Account','Product Stock In Hand']},
 '/preview/acc-item-ledger':{file:'preview/acc-item-ledger.html',title:'Item Ledger',crumbs:['Account','Item Ledger']},
 '/preview/bq-aminities-index':{file:'preview/bq-aminities-index.html',title:'Hall Amenities',crumbs:['Banquet Hall','Hall Amenities']},
 '/preview/bq-aminities-create':{file:'preview/bq-aminities-create.html',title:'Add Hall Amenity',crumbs:['Banquet Hall','Add Hall Amenity']},
 '/preview/bq-aminities-edit':{file:'preview/bq-aminities-edit.html',title:'Edit Hall Amenity',crumbs:['Banquet Hall','Edit Hall Amenity']},
 '/preview/bq-category-index':{file:'preview/bq-category-index.html',title:'Hall Categories',crumbs:['Banquet Hall','Hall Categories']},
 '/preview/bq-category-create':{file:'preview/bq-category-create.html',title:'Add Hall Category',crumbs:['Banquet Hall','Add Hall Category']},
 '/preview/bq-category-edit':{file:'preview/bq-category-edit.html',title:'Edit Hall Category',crumbs:['Banquet Hall','Edit Hall Category']},
 '/preview/bq-rooms-index':{file:'preview/bq-rooms-index.html',title:'Halls',crumbs:['Banquet Hall','Halls']},
 '/preview/bq-rooms-create':{file:'preview/bq-rooms-create.html',title:'Add Hall',crumbs:['Banquet Hall','Add Hall']},
 '/preview/bq-rooms-edit':{file:'preview/bq-rooms-edit.html',title:'Edit Hall',crumbs:['Banquet Hall','Edit Hall']},
 '/preview/bq-booking-index':{file:'preview/bq-booking-index.html',title:'Banquet Bookings',crumbs:['Banquet Hall','Banquet Bookings']},
 '/preview/bh-bookings':{file:'preview/bq-booking-index.html',title:'Banquet Bookings',crumbs:['Banquet Hall','Banquet Bookings']},
 '/preview/bq-booking-index-empty':{file:'preview/bq-booking-index-empty.html',title:'Banquet Bookings (empty)',crumbs:['Banquet Hall','Banquet Bookings (empty)']},
 '/preview/bq-booking-create':{file:'preview/bq-booking-create.html',title:'New Banquet Booking',crumbs:['Banquet Hall','New Banquet Booking']},
 '/preview/bh-booking-create':{file:'preview/bq-booking-create.html',title:'New Banquet Booking',crumbs:['Banquet Hall','New Banquet Booking']},
 '/preview/web-about':{file:'preview/web-about.html',title:'About Section',crumbs:['Website','About Section']},
 '/preview/web-banner-index':{file:'preview/web-banner-index.html',title:'Homepage Banners',crumbs:['Website','Homepage Banners']},
 '/preview/web-banner-create':{file:'preview/web-banner-create.html',title:'Add Banner',crumbs:['Website','Add Banner']},
 '/preview/web-banner-edit':{file:'preview/web-banner-edit.html',title:'Edit Banner',crumbs:['Website','Edit Banner']},
 '/preview/web-gallery-index':{file:'preview/web-gallery-index.html',title:'Gallery',crumbs:['Website','Gallery']},
 '/preview/web-gallery-create':{file:'preview/web-gallery-create.html',title:'Add Image',crumbs:['Website','Add Image']},
 '/preview/web-gallery-edit':{file:'preview/web-gallery-edit.html',title:'Edit Image',crumbs:['Website','Edit Image']},
 '/preview/web-feature-heading':{file:'preview/web-feature-heading.html',title:'Feature Heading',crumbs:['Website','Feature Heading']},
 '/preview/web-feature-list-index':{file:'preview/web-feature-list-index.html',title:'Homepage Features',crumbs:['Website','Homepage Features']},
 '/preview/web-feature-list-create':{file:'preview/web-feature-list-create.html',title:'Add Feature',crumbs:['Website','Add Feature']},
 '/preview/web-feature-list-edit':{file:'preview/web-feature-list-edit.html',title:'Edit Feature',crumbs:['Website','Edit Feature']},
 '/preview/web-service-heading':{file:'preview/web-service-heading.html',title:'Services Heading',crumbs:['Website','Services Heading']},
 '/preview/web-service-list-index':{file:'preview/web-service-list-index.html',title:'Service Boxes',crumbs:['Website','Service Boxes']},
 '/preview/web-service-list-create':{file:'preview/web-service-list-create.html',title:'Add Service Box',crumbs:['Website','Add Service Box']},
 '/preview/web-service-list-edit':{file:'preview/web-service-list-edit.html',title:'Edit Service Box',crumbs:['Website','Edit Service Box']},
 '/preview/web-pages-index':{file:'preview/web-pages-index.html',title:'Pages',crumbs:['Website','Pages']},
 '/preview/web-pages-create':{file:'preview/web-pages-create.html',title:'Add Page',crumbs:['Website','Add Page']},
 '/preview/web-pages-edit':{file:'preview/web-pages-edit.html',title:'Edit Page',crumbs:['Website','Edit Page']},
 '/preview/web-privacy':{file:'preview/web-privacy.html',title:'Privacy Policy',crumbs:['Website','Privacy Policy']},
 '/preview/web-settings':{file:'preview/web-settings.html',title:'Website Settings',crumbs:['Website','Website Settings']},
 '/preview/acc-contras':{file:'preview/acc-contras.html',title:'Contra Vouchers',crumbs:['Account','Contra Vouchers']},
 '/preview/acc-form-contras-create':{file:'preview/acc-form-contras-create.html',title:'Create Contra Voucher',crumbs:['Account','Create Contra Voucher']},
 '/preview/acc-form-contras-edit':{file:'preview/acc-form-contras-edit.html',title:'Edit Contra Voucher',crumbs:['Account','Edit Contra Voucher']},
 '/preview/acc-form-fund-transfer-create':{file:'preview/acc-form-fund-transfer-create.html',title:'Fund Transfer Create',crumbs:['Account','Fund Transfer Create']},
 '/preview/acc-form-fund-transfer-edit':{file:'preview/acc-form-fund-transfer-edit.html',title:'Fund Transfer Edit',crumbs:['Account','Fund Transfer Edit']},
 '/preview/acc-form-journals-create':{file:'preview/acc-form-journals-create.html',title:'Create Journal Voucher',crumbs:['Account','Create Journal Voucher']},
 '/preview/acc-form-journals-edit':{file:'preview/acc-form-journals-edit.html',title:'Edit Journal Voucher',crumbs:['Account','Edit Journal Voucher']},
 '/preview/acc-form-payments-create':{file:'preview/acc-form-payments-create.html',title:'Payment Voucher',crumbs:['Account','Payment Voucher']},
 '/preview/acc-form-receives-create':{file:'preview/acc-form-receives-create.html',title:'Receive Voucher',crumbs:['Account','Receive Voucher']},
 '/preview/acc-fund-transfers':{file:'preview/acc-fund-transfers.html',title:'Fund Transfers',crumbs:['Account','Fund Transfers']},
 '/preview/acc-journals-show':{file:'preview/acc-journals-show.html',title:'Journal Voucher Details',crumbs:['Account','Journal Voucher Details']},
 '/preview/acc-journals':{file:'preview/acc-journals.html',title:'Journal Vouchers',crumbs:['Account','Journal Vouchers']},
 '/preview/acc-payments-show':{file:'preview/acc-payments-show.html',title:'Payment Voucher Detail',crumbs:['Account','Payment Voucher Detail']},
 '/preview/acc-payments':{file:'preview/acc-payments.html',title:'Payment Vouchers',crumbs:['Account','Payment Vouchers']},
 '/preview/acc-receives-show':{file:'preview/acc-receives-show.html',title:'Receive Voucher Detail',crumbs:['Account','Receive Voucher Detail']},
 '/preview/acc-receives':{file:'preview/acc-receives.html',title:'Receive Vouchers',crumbs:['Account','Receive Vouchers']},
 '/preview/category-create':{file:'preview/category-create.html',title:'Add a room category',crumbs:['Hotel','Category','Create']},
 '/preview/hotel-monthly-booking':{file:'preview/hotel-monthly-booking.html',title:'Hotel Monthly Booking Report',crumbs:['Hotel','Reports','Monthly Booking']},
 '/preview/report-all-reports':{file:'preview/report-all-reports.html',title:'All Hotel Reports',crumbs:['Hotel','Reports','All Reports']},
 '/preview/report-expected-departure':{file:'preview/report-expected-departure.html',title:'Expected Departure List',crumbs:['Hotel','Reports','Expected Departure']},
 '/preview/report-in-house-guest':{file:'preview/report-in-house-guest.html',title:'In-House Guest List',crumbs:['Hotel','Reports','In-House Guest']},
 '/preview/report-services':{file:'preview/report-services.html',title:'Hotel Services Report',crumbs:['Hotel','Reports','Services']},
 '/preview/report-today-check-in':{file:'preview/report-today-check-in.html',title:'Today Check-In Report',crumbs:['Hotel','Reports','Today Check-In']},
 '/preview/report-today-check-out':{file:'preview/report-today-check-out.html',title:'Today Check-Out Report',crumbs:['Hotel','Reports','Today Check-Out']},
 '/preview/report-today-in-house':{file:'preview/report-today-in-house.html',title:'Today In-House Report',crumbs:['Hotel','Reports','Today In-House']},
 '/preview/report-vat-report-day':{file:'preview/report-vat-report-day.html',title:'Daily VAT Report',crumbs:['Hotel','Reports','Daily VAT']},
 '/preview/report-vat-report-monthly':{file:'preview/report-vat-report-monthly.html',title:'Monthly VAT Report',crumbs:['Hotel','Reports','Monthly VAT']},
 '/preview/setup-account_type-edit':{file:'preview/setup-account_type-edit.html',title:'Edit Account Type',crumbs:['Hotel','Setup','Edit Account Type']},
 '/preview/setup-aminities-edit':{file:'preview/setup-aminities-edit.html',title:'Edit Amenity',crumbs:['Hotel','Setup','Edit Amenity']},
 '/preview/setup-guest-registration-terms-edit':{file:'preview/setup-guest-registration-terms-edit.html',title:'Edit Registration Terms',crumbs:['Hotel','Setup','Edit Registration Terms']},
};
const between=(h,a,b)=>{const i=h.indexOf(a);return h.slice(i+a.length,h.indexOf(b,i+a.length));};
function shellWith(content,head,js,title,crumbs){
 let raw=fix('dashboard.html');
 const a='<!--SHELL-TOOLBAR-->',b='</div></div></div><!--SHELL-FOOTER-->';
 const i=raw.indexOf(a)+a.length,j=raw.indexOf(b);
 raw=raw.slice(0,i)+'\n<!--MM-PAGE-->'+raw.slice(j);
 let page=compose('dashboard.html',raw);
 const quickDrawer='<div class="modal fade mm-app-drawer" id="mm-quick-drawer" tabindex="-1" role="dialog" aria-labelledby="mm-quick-drawer-title"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="mm-quick-drawer-title"><i class="fa fa-columns"></i> Quick Action &amp; Inspector Drawer — '+title+'</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button></div><div class="modal-body"><section class="mm-ui"><div class="tw-space-y-4"><div class="mm-panel tw-p-4"><div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:12px"><strong><i class="fa fa-bolt"></i> Quick Entry Form (Frosted Glass Fields &amp; Dropdowns)</strong><span class="mm-badge mm-badge-paid">Slide-Over Drawer</span></div><form onsubmit="return false;" class="tw-space-y-3"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px"><div><label class="mm-label">Guest / Party Name <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input type="text" class="form-control" value="Tanvir Ahmed Chowdhury" placeholder="Enter full name"></div></div><div><label class="mm-label">Department / Outlet</label><select class="form-control"><option>Front Desk &amp; Room Reservations</option><option>Heritage Dining Restaurant</option><option>Skyline Lounge Bar</option><option>General Store &amp; Inventory</option><option>Accounts &amp; Finance</option></select></div><div><label class="mm-label">Transaction Category</label><select class="form-control"><option>Room Advance / Folio Settlement</option><option>Supplier Purchase Requisition</option><option>Account Payment / Journal Voucher</option><option>VIP Guest Profile Registration</option></select></div><div><label class="mm-label">Amount (BDT)</label><div class="input-group"><span class="input-group-addon">৳</span><input type="number" class="form-control" value="12500"><span class="input-group-addon">.00</span></div></div></div><div><label class="mm-label">Reference / Front-Desk Notes</label><textarea class="form-control" rows="2" placeholder="Add remarks or instructions...">Verified corporate booking &amp; instant settlement via Slide-Over App Drawer.</textarea></div></form></div><div class="mm-panel tw-p-4"><div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:10px"><strong><i class="fa fa-table"></i> Live Breakdown Table (Glass Table Surface)</strong><span class="mm-badge mm-badge-partial">3 Active Lines</span></div><div class="mm-table-scroll"><table class="table table-bordered table-striped"><thead><tr><th>Code</th><th>Description</th><th>Payment / Status</th><th class="text-right">Amount (৳)</th></tr></thead><tbody><tr><td><code>#RM-101</code></td><td>Deluxe Double · 2 Nights</td><td><span class="mm-badge mm-badge-paid">Confirmed</span></td><td class="text-right">8,400.00</td></tr><tr><td><code>#RST-204</code></td><td>Restaurant Room Service KOT</td><td><span class="mm-badge mm-badge-partial">Posted to Folio</span></td><td class="text-right">2,600.00</td></tr><tr><td><code>#SRV-019</code></td><td>Airport Chauffeur Pickup</td><td><span class="mm-badge mm-badge-paid">Settled</span></td><td class="text-right">1,500.00</td></tr></tbody></table></div></div></div></section></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Save &amp; Apply Changes</button></div></div></div></div>';
 const banner='<div role="note" style="padding:10px 14px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px;border-radius:10px"><div><strong>Visual preview · sample data only.</strong> '+title+' — nothing here is saved. <a href="/preview">All preview screens</a> · <a href="/">Dashboard</a></div><div style="display:flex;flex-wrap:wrap;gap:8px"><button type="button" data-toggle="modal" data-target="#mm-quick-drawer" class="mm-button" style="min-height:32px;padding:4px 12px"><i class="fa fa-columns"></i> Open App Drawer</button><button type="button" data-mm-theme-toggle aria-pressed="false" aria-label="Dark theme" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-moon-o"></i> Dark / Light</button><a href="/preview/booking-next" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-map-o"></i> Wizard + Floor Plan</a><a href="/preview/acc-opening-balances-data" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-balance-scale"></i> Opening Balances</a><a href="/preview/acc-form-purchases-create" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-shopping-cart"></i> Commercial PO</a><a href="/preview/acc-payments-show" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-file-text-o"></i> Voucher Detail</a><a href="/preview/gs-form-purchase-approve" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-check-circle"></i> Approve Store PO</a><a href="/preview/hservice-sale-create" class="mm-button mm-button-secondary" style="min-height:32px;padding:4px 12px"><i class="fa fa-concierge-bell"></i> Service Sale</a></div></div>';
 crumbs=crumbs||['Preview','Screens'];
 page=page.replace(/<li><span>Hotel<\/span><\/li>\s*<li><span>Booking<\/span><\/li>\s*<li><span aria-current="page">Create<\/span><\/li>/,()=>crumbs.slice(0,-1).map(c=>'<li><span>'+c+'</span></li>').join('')+'<li><span aria-current="page">'+crumbs[crumbs.length-1]+'</span></li>');
 page=page.replace('<!--MM-PAGE-->',()=>banner+content+quickDrawer);
 const styleAnchor='<link rel="stylesheet" href="/assets/custom_css/style.css">';
 page=(page.includes(styleAnchor)?page.replace(styleAnchor,()=>head+styleAnchor):page.replace('</head>',()=>head+'</head>')).replace('</body>',()=>js+'</body>');
 return page;
}
function screen(name){
 const html=fix(pages[name].file);
 return shellWith(between(html,'<!--MM-BODY-->','<!--/MM-BODY-->'),between(html,'<!--MM-HEAD-->','<!--/MM-HEAD-->'),between(html,'<!--MM-JS-->','<!--/MM-JS-->').replace(/window\.addEventListener\('load'[\s\S]*?\}\);\s*(?=<\/script>)/,'/* auto-print disabled in the preview */'),pages[name].title,pages[name].crumbs);
}
function uiKitPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">UI Component Library (/ui-kit)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Laravel + Blade + Tailwind · isolated renovation layer (&lt;x-mm.*&gt; components × states in Light &amp; Dark Mode).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="#fields" class="mm-button"><i class="fa fa-sliders"></i> Explore Form Fields</a><button type="button" data-toggle="modal" data-target="#mm-quick-drawer" class="mm-button mm-button-secondary"><i class="fa fa-columns"></i> Slide-Over Drawer</button><a href="/preview/system-settings" class="mm-button mm-button-secondary"><i class="fa fa-cogs"></i> System Settings</a><a href="/preview/login" class="mm-button mm-button-secondary"><i class="fa fa-lock"></i> Auth Screen</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Design system metrics"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-cubes"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Blade Components</span><div class="mm-table-kpi-value">14 Kit Primitives <span class="mm-table-kpi-sub">&lt;x-mm.*&gt;</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-desktop"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Renovated Screens</span><div class="mm-table-kpi-value">300 Live Routes <span class="mm-table-kpi-sub">10 Modules</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-columns"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Slide-Over Drawers</span><div class="mm-table-kpi-value">29 / 29 Modals <span class="mm-table-kpi-sub">100% Converted</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Automated Guardrails</span><div class="mm-table-kpi-value">653 Browser Tests <span class="mm-table-kpi-sub">6 Diff Tripwires</span></div></div></div></div>
 <div class="mm-panel tw-p-5" id="tiles"><h2 class="tw-m-0 tw-mb-2 tw-text-lg tw-font-semibold">1. Tiles (&lt;x-mm.tile&gt;) &amp; Status Chips (&lt;x-mm.chip&gt; / .mm-badge)</h2><p class="tw-text-muted tw-mb-3">Used across Dashboard, Night Audit, POS Table View, and Financial Statements.</p><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin-bottom:14px"><div class="mm-tile"><div class="mm-tile-label">Occupied Rooms</div><div class="mm-tile-value">42</div><div class="mm-tile-hint">of 60 total rooms</div></div><div class="mm-tile mm-tile-brand"><div class="mm-tile-label">Today Arrivals</div><div class="mm-tile-value">8</div><div class="mm-tile-hint">VIP &amp; Corporate</div></div><div class="mm-tile mm-tile-success"><div class="mm-tile-label">Settled Folios</div><div class="mm-tile-value">৳ 1,240,000</div><div class="mm-tile-hint">Current business week</div></div><div class="mm-tile mm-tile-danger"><div class="mm-tile-label">Pending Checkouts</div><div class="mm-tile-value">2</div><div class="mm-tile-hint">Late departure</div></div></div><div class="tw-flex tw-flex-wrap tw-gap-2"><span class="mm-badge mm-badge-paid">Confirmed / Paid</span><span class="mm-badge mm-badge-partial">Partial / In-Progress</span><span class="mm-badge mm-badge-unpaid">Unpaid / Due</span><span class="mm-chip mm-chip-brand">Brand Chip</span><span class="mm-chip mm-chip-success">Success Chip</span><span class="mm-chip mm-chip-warning">Warning Chip</span><span class="mm-chip mm-chip-danger">Danger Chip</span></div></div>
 <div class="mm-panel tw-p-5" id="stepper"><h2 class="tw-m-0 tw-mb-2 tw-text-lg tw-font-semibold">2. 3-Step Visual Stepper (&lt;x-mm.stepper&gt;)</h2><p class="tw-text-muted tw-mb-3">Powers the 3-Step Room Booking Wizard (<code>/preview/booking-next</code>) and Restaurant/Bar POS (<code>courses → fire → pay</code>).</p><nav class="mm-stepper" aria-label="Booking progress"><ol class="mm-stepper-list"><li class="mm-step is-done"><span class="mm-step-index">✓</span><span class="mm-step-label">1. Select rooms &amp; floor map</span></li><li class="mm-step is-current" aria-current="step"><span class="mm-step-index">2</span><span class="mm-step-label">2. Guest and stay details</span></li><li class="mm-step"><span class="mm-step-index">3</span><span class="mm-step-label">3. Payment and confirm</span></li></ol></nav></div>
 <div class="mm-panel tw-p-5" id="fields"><h2 class="tw-m-0 tw-mb-2 tw-text-lg tw-font-semibold">3. Frosted-Glass 40px Form Controls (&lt;x-mm.field&gt;, .input-group, Select)</h2><p class="tw-text-muted tw-mb-3">Unified 40px height, 10px radius, and focus rings across native inputs, currency addons, and Chosen/Select2 dropdowns.</p><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px"><div><label class="mm-label">Guest Full Name</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span><input type="text" class="form-control" value="Brig. Gen. Mahmudul Hasan"></div></div><div><label class="mm-label">Outlet / Cost Center</label><select class="form-control"><option>Front Desk &amp; Suites</option><option>Heritage Dining Restaurant</option><option>Skyline Lounge Bar</option><option>Grand Banquet Hall</option></select></div><div><label class="mm-label">Tariff / Voucher Amount</label><div class="input-group"><span class="input-group-addon">৳</span><input type="text" class="form-control text-right" value="18,500.00"><span class="input-group-btn"><button type="button" class="btn btn-primary">Apply</button></span></div></div><div><label class="mm-label">Quick Date Range</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="2026-10-12 → 2026-10-15"></div></div></div></div>
 <div class="mm-panel tw-p-5" id="data-table"><h2 class="tw-m-0 tw-mb-2 tw-text-lg tw-font-semibold">4. Responsive Data Table (&lt;x-mm.data-table&gt;) &amp; Visual Share Bars (.mm-share-bar)</h2><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Code</th><th>Revenue Stream</th><th>Outlet</th><th>Revenue Share</th><th class="text-right">Amount (৳)</th></tr></thead><tbody><tr><td><code>#REV-01</code></td><td><strong>Room Division &amp; Executive Suites</strong></td><td><span class="mm-badge mm-badge-paid">Front Desk</span></td><td><div class="mm-share-cell"><span>58.3%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:58.3%"></span></span></div></td><td class="text-right"><strong>1,120,000.00</strong></td></tr><tr><td><code>#REV-02</code></td><td><strong>Heritage Dining Restaurant POS</strong></td><td><span class="mm-badge mm-badge-paid">Restaurant</span></td><td><div class="mm-share-cell"><span>22.9%</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:22.9%"></span></span></div></td><td class="text-right"><strong>440,000.00</strong></td></tr><tr><td><code>#REV-03</code></td><td><strong>Skyline Lounge Bar &amp; Banquet Events</strong></td><td><span class="mm-badge mm-badge-partial">Bar &amp; Banquet</span></td><td><div class="mm-share-cell"><span>18.8%</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:18.8%"></span></span></div></td><td class="text-right"><strong>360,000.00</strong></td></tr></tbody></table></div></div>
 </div></section>`;
 return shellWith(body,'','','UI Component Library (/ui-kit)',['System','Design System','UI Kit']);
}
function systemSettingsPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-cogs"></i> System, Property, Tax &amp; RBAC Governance (Plan A · Phase 3 #9 &amp; W6.5)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Global hotel property configuration, active business date, SMS/email gateways, financial auto-voucher posting, 2FA security, and EN/বাংলা i18n rules.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/plan-a" class="mm-button mm-button-secondary"><i class="fa fa-sitemap"></i> Plan A Hub (9/9)</a><a href="/preview/room-settings" class="mm-button mm-button-secondary"><i class="fa fa-sliders"></i> Room &amp; Pricing Settings</a><a href="/preview/perm-access" class="mm-button mm-button-secondary"><i class="fa fa-shield"></i> RBAC Matrix</a><a href="/preview/admin-2fa" class="mm-button mm-button-secondary"><i class="fa fa-lock"></i> 2FA (F31)</a><a href="/preview/admin-i18n" class="mm-button mm-button-secondary"><i class="fa fa-globe"></i> i18n (F32)</a><button type="button" class="mm-button" id="sysset-btn-save"><i class="fa fa-check"></i> Save All Settings</button></div></header>
 <nav class="mm-stepper mm-no-print" aria-label="System settings governance workflow">
  <ol class="mm-stepper-list">
   <li class="mm-step is-done"><span class="mm-step-index">&#10003;</span><span class="mm-step-copy"><span class="mm-step-label">1. Property &amp; Business Date Profile</span><span class="mm-step-sub">M&amp;M Heritage Hotel &amp; Suites · 2026-10-14 (Asia/Dhaka)</span></span><span class="mm-step-pill">Active</span></li>
   <li class="mm-step is-current" aria-current="step"><span class="mm-step-index">2</span><span class="mm-step-copy"><span class="mm-step-label">2. POS, Night Audit &amp; Auto-GL Posting Rules</span><span class="mm-step-sub">80mm Thermal KOT · 5% Svc + 15% VAT · Auto-Voucher</span></span><span class="mm-step-pill">Configured</span></li>
   <li class="mm-step"><span class="mm-step-index">3</span><span class="mm-step-copy"><span class="mm-step-label">3. RBAC, 2FA, Bilingual i18n &amp; Cloud Sync</span><span class="mm-step-sub">8 Staff Roles · TOTP 2FA · EN/বাংলা · SHA-256 Sync</span></span><span class="mm-step-pill">Enforced</span></li>
  </ol>
 </nav>
 <div class="mm-table-kpi-grid" aria-label="System settings summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-building-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Property Profile</span><div class="mm-table-kpi-value">M&amp;M Heritage <span class="mm-table-kpi-sub">20 Suites · Active</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-calendar-check-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Business Date</span><div class="mm-table-kpi-value">14 Oct 2026 <span class="mm-table-kpi-sub">Asia/Dhaka</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-percent"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Default VAT &amp; Service</span><div class="mm-table-kpi-value">15% + 5% <span class="mm-table-kpi-sub">Mushak-6.3 Auto</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">RBAC, 2FA &amp; Cloud Sync</span><div class="mm-table-kpi-value">8 Roles · Online <span class="mm-table-kpi-sub">Synced Just Now</span></div></div></div></div>
 <div id="sysset-save-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;"><i class="fa fa-check-circle"></i> <strong>Global hotel system settings, business date rules, POS thermal configuration, and GL auto-posting policies saved!</strong></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th width="6%">SL</th><th width="32%">Setting Parameter</th><th>Configured Value</th><th width="18%">Module Scope</th></tr></thead><tbody><tr><td>1</td><td><strong>Hotel / Property Display Name</strong></td><td><input type="text" class="form-control" value="M&amp;M Heritage Hotel &amp; Suites"></td><td><span class="mm-badge mm-badge-paid">Global Shell</span></td></tr><tr><td>2</td><td><strong>Active Hotel Business Date</strong></td><td><input type="text" class="form-control" value="2026-10-14"></td><td><span class="mm-badge mm-badge-paid">Night Audit &amp; Board</span></td></tr><tr><td>3</td><td><strong>Standard Check-In / Check-Out Window</strong></td><td><div style="display:grid;grid-template-columns:1fr 1fr;gap:8px"><input type="text" class="form-control" value="14:00 PM"><input type="text" class="form-control" value="12:00 PM"></div></td><td><span class="mm-badge mm-badge-partial">Front Desk</span></td></tr><tr><td>4</td><td><strong>POS Thermal Printer Width &amp; Auto-Print</strong></td><td><select class="form-control"><option selected>80mm Thermal KOT + Auto-Fire Kitchen Board</option><option>A4 Standard Invoice Sheet</option></select></td><td><span class="mm-badge mm-badge-paid">Restaurant &amp; Bar POS</span></td></tr><tr><td>5</td><td><strong>Accounting Auto-Voucher Posting on Checkout</strong></td><td><select class="form-control"><option selected>Enabled — Post Room, F&amp;B &amp; VAT to General Ledger</option><option>Manual Journal Posting</option></select></td><td><span class="mm-badge mm-badge-paid">Account &amp; Finance</span></td></tr><tr><td>6</td><td><strong>Staff 2FA Enforcement &amp; Session Timeout</strong></td><td><select class="form-control"><option selected>Mandatory TOTP 2FA for Night Audit, Split-Folio &amp; RBAC Admin (F31)</option><option>Password Only</option></select></td><td><span class="mm-badge mm-badge-paid">Security &amp; RBAC</span></td></tr><tr><td>7</td><td><strong>Default UI Language &amp; Currency Format</strong></td><td><select class="form-control"><option selected>Bilingual English + বাংলা (BDT ৳ · Mushak-6.3 Tax Format · F32)</option><option>English Only</option></select></td><td><span class="mm-badge mm-badge-paid">i18n &amp; Localization</span></td></tr></tbody></table></div></div></div></section>
 <script>(function(){var b=document.getElementById("sysset-btn-save"),ban=document.getElementById("sysset-save-banner");if(b&&ban){b.addEventListener("click",function(){ban.style.display="block";});}})();</script>`;
 return shellWith(body,'','','System Settings (Phase 3 #9 & W6.5)',['System','Settings','Configuration']);
}
function loginPage(){
 const body=`<section class="mm-ui mm-auth"><div style="max-width:460px;margin:24px auto"><div class="mm-panel tw-p-6"><div style="text-align:center;margin-bottom:20px"><span class="mm-table-kpi-icon mm-table-kpi-icon-all" style="width:48px;height:48px;font-size:20px;margin:0 auto 10px"><i class="fa fa-building-o"></i></span><h1 style="margin:0;font-size:20px;font-weight:800">M&amp;M Heritage Hotel ERP</h1><p class="tw-text-sm tw-text-muted" style="margin:4px 0 0">Sign in to Front Desk, POS, Inventory &amp; Accounting (W6.5 Auth Shell)</p></div><form onsubmit="window.location.href='/';return false;" class="tw-space-y-4"><div><label class="mm-label">Email or Staff Username</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o"></i></span><input type="email" class="form-control" value="admin@mmheritagehotel.com" required></div></div><div><label class="mm-label">Password</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span><input type="password" class="form-control" value="••••••••••••" required></div></div><div style="display:flex;align-items:center;justify-content:space-between;font-size:12.5px"><label style="margin:0;display:inline-flex;align-items:center;gap:6px"><input type="checkbox" checked> Remember this workstation</label><a href="/preview/password-reset">Reset password?</a></div><button type="submit" class="mm-button" style="width:100%;justify-content:center"><i class="fa fa-sign-in"></i> Sign In to Workspace</button><div style="text-align:center;font-size:12.5px;margin-top:8px"><a href="/preview/register">Register new staff account</a> · <a href="/preview/email-templates">Email templates</a></div></form></div></div></section>`;
 return shellWith(body,'','','Sign In (W6.5 Auth Layout)',['System','Auth','Login']);
}
function passwordResetPage(){
 const body=`<section class="mm-ui mm-auth"><div style="max-width:460px;margin:24px auto"><div class="mm-panel tw-p-6"><div style="text-align:center;margin-bottom:20px"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ" style="width:48px;height:48px;font-size:20px;margin:0 auto 10px"><i class="fa fa-key"></i></span><h1 style="margin:0;font-size:20px;font-weight:800">Reset Staff Password</h1><p class="tw-text-sm tw-text-muted" style="margin:4px 0 0">Send a secure password recovery link to your registered hotel email (W6.5 Auth).</p></div><form onsubmit="return false;" class="tw-space-y-4"><div><label class="mm-label">Registered Staff Email</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o"></i></span><input type="email" class="form-control" value="frontdesk@mmheritagehotel.com" required></div></div><button type="submit" class="mm-button" style="width:100%;justify-content:center"><i class="fa fa-paper-plane"></i> Send Password Reset Link</button><div style="text-align:center;font-size:12.5px"><a href="/preview/login">← Back to Staff Login</a> · <a href="/preview/email-templates">Preview Reset Email</a></div></form></div></div></section>`;
 return shellWith(body,'','','Password Reset (W6.5 Auth)',['System','Auth','Password Reset']);
}
function registerPage(){
 const body=`<section class="mm-ui mm-auth"><div style="max-width:520px;margin:24px auto"><div class="mm-panel tw-p-6"><div style="text-align:center;margin-bottom:20px"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail" style="width:48px;height:48px;font-size:20px;margin:0 auto 10px"><i class="fa fa-user-plus"></i></span><h1 style="margin:0;font-size:20px;font-weight:800">Register Staff Account</h1><p class="tw-text-sm tw-text-muted" style="margin:4px 0 0">Provision a new Front Desk, POS, Inventory, or Accounting user profile (W6.5).</p></div><form onsubmit="return false;" class="tw-space-y-4"><div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><div><label class="mm-label">Full Name</label><input type="text" class="form-control" value="Nusrat Jahan"></div><div><label class="mm-label">Department Role</label><select class="form-control"><option>Front Desk Supervisor</option><option>Restaurant POS Cashier</option><option>Accounts &amp; Audit</option></select></div></div><div><label class="mm-label">Work Email</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope-o"></i></span><input type="email" class="form-control" value="nusrat@mmheritagehotel.com"></div></div><div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><div><label class="mm-label">Password</label><input type="password" class="form-control" value="••••••••••••"></div><div><label class="mm-label">Confirm Password</label><input type="password" class="form-control" value="••••••••••••"></div></div><button type="submit" class="mm-button" style="width:100%;justify-content:center"><i class="fa fa-check"></i> Create Staff Account</button><div style="text-align:center;font-size:12.5px"><a href="/preview/login">← Already registered? Sign in</a></div></form></div></div></section>`;
 return shellWith(body,'','','Register Staff Account (W6.5 Auth)',['System','Auth','Register']);
}
function newsNoticesPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Staff &amp; Guest Notice Board</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Property-wide announcements, shift handovers, and VIP arrival advisories (W2.3).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/news-notice-create" class="mm-button"><i class="fa fa-plus"></i> Publish Notice</a><a href="/preview/sms-send" class="mm-button mm-button-secondary"><i class="fa fa-commenting-o"></i> Dispatch SMS</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Notice board summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bullhorn"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Notices</span><div class="mm-table-kpi-value">12 Published <span class="mm-table-kpi-sub">All Outlets</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-star"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">VIP Arrival Alerts</span><div class="mm-table-kpi-value">3 Today <span class="mm-table-kpi-sub">Presidential &amp; Suites</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-users"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Department Briefs</span><div class="mm-table-kpi-value">5 Active <span class="mm-table-kpi-sub">Front Desk &amp; F&amp;B</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Read Acknowledgment</span><div class="mm-table-kpi-value">96.4% <span class="mm-table-kpi-sub">On-Shift Staff</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th width="6%">SL</th><th width="14%">Date</th><th>Notice Title &amp; Summary</th><th width="16%">Audience</th><th width="14%">Status</th><th width="14%" class="text-center">Action</th></tr></thead><tbody><tr><td>1</td><td>2026-10-12</td><td><strong>Diplomatic Delegation Check-In Protocol &amp; Floor 3 Security</strong></td><td>Front Desk &amp; Housekeeping</td><td><span class="mm-badge mm-badge-paid">Published</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px" data-toggle="modal" data-target="#mm-quick-drawer">Inspect</button></td></tr><tr><td>2</td><td>2026-10-12</td><td><strong>Autumn Gala Banquet Menu &amp; Kitchen Prep Schedule</strong></td><td>Restaurant &amp; Banquet</td><td><span class="mm-badge mm-badge-paid">Published</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px" data-toggle="modal" data-target="#mm-quick-drawer">Inspect</button></td></tr><tr><td>3</td><td>2026-10-11</td><td><strong>Monthly Night Audit &amp; VAT Reconciliation Cutoff</strong></td><td>Accounts &amp; Audit</td><td><span class="mm-badge mm-badge-partial">Pinned</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px" data-toggle="modal" data-target="#mm-quick-drawer">Inspect</button></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Notice Board (W2.3)',['News & Events','Notices','List']);
}
function newsNoticeCreatePage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Publish New Notice</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Create a property-wide or department-targeted operational notice (W2.3).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/news-notices" class="mm-button mm-button-secondary"><i class="fa fa-list"></i> Notice List</a></div></header>
 <div class="mm-panel tw-p-5"><form onsubmit="return false;" class="tw-space-y-4"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px"><div><label class="mm-label">Notice Headline <span class="text-danger">*</span></label><input type="text" class="form-control" value="VIP Corporate Summit — Express Check-In Desk Readiness"></div><div><label class="mm-label">Target Department</label><select class="form-control"><option>All Hotel Departments</option><option>Front Desk &amp; Concierge</option><option>Heritage Dining &amp; Bar POS</option><option>Housekeeping &amp; Engineering</option></select></div><div><label class="mm-label">Effective Date</label><input type="date" class="form-control" value="2026-10-12"></div><div><label class="mm-label">Priority Status</label><select class="form-control"><option>Published — High Priority</option><option>Standard Notice</option><option>Draft</option></select></div></div><div><label class="mm-label">Notice Body &amp; Operational Instructions</label><textarea class="form-control" rows="4">Please ensure all Executive Suite welcome amenities and express keycard packets are staged by 11:30 AM prior to the 12:30 PM airport shuttle arrival.</textarea></div><div class="mm-form-actions"><button type="submit" class="mm-button"><i class="fa fa-check"></i> Publish Notice</button><a href="/preview/news-notices" class="mm-button mm-button-secondary">Cancel</a></div></form></div></div></section>`;
 return shellWith(body,'','','Publish Notice (W2.3)',['News & Events','Notices','Create']);
}
function smsApisPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">SMS Gateway APIs</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Manage transactional SMS providers for booking confirmations, OTPs, and guest alerts (W2.3).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/sms-send" class="mm-button"><i class="fa fa-paper-plane"></i> Dispatch SMS</a><button type="button" data-toggle="modal" data-target="#mm-quick-drawer" class="mm-button mm-button-secondary"><i class="fa fa-plus"></i> Add Gateway</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="SMS gateway metrics"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-signal"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Primary Gateway</span><div class="mm-table-kpi-value">SSL Wireless BD <span class="mm-table-kpi-sub">99.9% Uptime</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-credit-card"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Available SMS Credits</span><div class="mm-table-kpi-value">48,250 SMS <span class="mm-table-kpi-sub">Masking Active</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Delivered Today</span><div class="mm-table-kpi-value">184 Messages <span class="mm-table-kpi-sub">99.4% Delivery</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-tag"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Sender ID Mask</span><div class="mm-table-kpi-value">MMHERITAGE <span class="mm-table-kpi-sub">BTRC Approved</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Gateway Provider</th><th>Sender ID / Mask</th><th>Endpoint URL</th><th>Delivery Share</th><th>Status</th></tr></thead><tbody><tr><td>1</td><td><strong>SSL WirelessMasking Gateway</strong></td><td><code>MMHERITAGE</code></td><td><code>https://smsplus.sslwireless.com/api/v3/send-sms</code></td><td><div class="mm-share-cell"><span>85% Primary</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:85%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Active Default</span></td></tr><tr><td>2</td><td><strong>Banglalink Corporate Bulk SMS</strong></td><td><code>88019XXXXXXXX</code></td><td><code>https://vas.banglalink.net/sendSMS</code></td><td><div class="mm-share-cell"><span>15% Failover</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:15%"></span></span></div></td><td><span class="mm-badge mm-badge-partial">Standby</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','SMS Gateway APIs (W2.3)',['News & Events','SMS','Gateways']);
}
function smsSendPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Guest &amp; Corporate SMS Dispatch</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Send instant reservation confirmations, check-in greetings, or promotional campaigns (W2.3).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/sms-apis" class="mm-button mm-button-secondary"><i class="fa fa-cog"></i> Gateway Settings</a><a href="/preview/hotel-sms" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> Hotel Guest SMS</a></div></header>
 <div class="mm-panel tw-p-5"><form onsubmit="return false;" class="tw-space-y-4"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px"><div><label class="mm-label">Recipient Cohort</label><select class="form-control"><option>In-House Guests (42 Rooms)</option><option>Today Expected Arrivals (8 Bookings)</option><option>VIP Corporate Directory (165 Contacts)</option><option>Manual Phone Numbers</option></select></div><div><label class="mm-label">Sender Gateway</label><select class="form-control"><option>SSL Wireless (Mask: MMHERITAGE)</option><option>Banglalink Corporate Failover</option></select></div><div><label class="mm-label">Message Template</label><select class="form-control"><option>Booking Confirmation &amp; Check-In Pin</option><option>Welcome to M&amp;M Heritage Hotel</option><option>Checkout Folio &amp; Thank You</option></select></div></div><div><label class="mm-label">SMS Message Body (148 / 160 chars · 1 SMS segment)</label><textarea class="form-control" rows="3">Dear Guest, your reservation #BK-2026-104 at M&amp;M Heritage Hotel is confirmed for 12 Oct 2026 (Check-in 2:00 PM). Call +880-1711-000000 for pickup.</textarea></div><div class="mm-form-actions"><button type="submit" class="mm-button"><i class="fa fa-paper-plane"></i> Dispatch SMS Now</button></div></form></div></div></section>`;
 return shellWith(body,'','','Guest & Corporate SMS Dispatch (W2.3)',['News & Events','SMS','Dispatch']);
}
function systemSyncPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Cloud Data Sync v2 (W6.5)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Synchronize Front Desk, Restaurant POS, Bar POS, and General Ledger vouchers with cloud backup.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button"><i class="fa fa-cloud-upload"></i> Trigger Incremental Sync</button><button type="button" data-toggle="modal" data-target="#mm-quick-drawer" class="mm-button mm-button-secondary"><i class="fa fa-list-alt"></i> Sync Status Drawer</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Cloud sync metrics"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Replication Health</span><div class="mm-table-kpi-value">100% In Sync <span class="mm-table-kpi-sub">Zero Pending Queue</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-database"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Last Cloud Snapshot</span><div class="mm-table-kpi-value">2 mins ago <span class="mm-table-kpi-sub">12 Oct 2026</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-exchange"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Synced Vouchers &amp; KOTs</span><div class="mm-table-kpi-value">1,428 Records <span class="mm-table-kpi-sub">Current Business Day</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">TLS &amp; Checksum</span><div class="mm-table-kpi-value">SHA-256 Verified <span class="mm-table-kpi-sub">Encrypted</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Module Stream</th><th>Last Synced Batch</th><th>Records Replicated</th><th>Sync Completion</th><th>Status</th></tr></thead><tbody><tr><td><strong>Hotel Bookings &amp; Guest Folios</strong></td><td><code>#SYNC-9841</code></td><td>412 records</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Verified</span></td></tr><tr><td><strong>Restaurant &amp; Bar POS Orders</strong></td><td><code>#SYNC-9842</code></td><td>684 records</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Verified</span></td></tr><tr><td><strong>Account Vouchers &amp; Ledger Postings</strong></td><td><code>#SYNC-9843</code></td><td>332 records</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Verified</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Cloud Data Sync v2 (W6.5)',['System','Cloud Sync']);
}
function emailTemplatesPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Branded Hospitality Email Templates (B3)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Transactional HTML email templates for Booking Confirmations, Folio Receipts, and Password Resets.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/invoice" class="mm-button mm-button-secondary"><i class="fa fa-print"></i> Booking Invoice</a><a href="/preview/password-reset" class="mm-button mm-button-secondary"><i class="fa fa-key"></i> Password Reset</a></div></header>
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:16px">
  <div class="mm-panel tw-p-5"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><strong>1. Booking Confirmation &amp; Reservation Alert</strong><span class="mm-badge mm-badge-paid">mails/booking-alert</span></div><div style="border:1px solid rgba(148,163,184,0.3);border-top:4px solid #1d4ed8;border-radius:12px;padding:18px;background:rgba(255,255,255,0.96);color:#0f172a"><div style="font-size:11px;font-weight:700;text-transform:uppercase;color:#1d4ed8;letter-spacing:.06em">M&amp;M Heritage Hotel &amp; Suites</div><h3 style="margin:4px 0 8px;font-size:18px;font-weight:800;color:#0f172a">Reservation Confirmed · #BK-2026-104</h3><p style="font-size:13px;color:#334155;margin:0 0 12px">Dear <strong>Tanvir Ahmed Chowdhury</strong>, your Deluxe Double Suite is reserved for <strong>12 Oct 2026 → 15 Oct 2026 (3 Nights)</strong>.</p><table class="table table-bordered" style="font-size:12px;margin-bottom:12px"><thead><tr><th>Room</th><th>Nights</th><th class="text-right">Total (৳)</th></tr></thead><tbody><tr><td>Deluxe Suite #204</td><td>3</td><td class="text-right"><strong>12,600.00</strong></td></tr></tbody></table><a href="/preview/invoice" class="mm-button" style="width:100%;justify-content:center">View &amp; Print Reservation Invoice</a></div></div>
  <div class="mm-panel tw-p-5"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px"><strong>2. Staff &amp; Guest Password Reset Email</strong><span class="mm-badge mm-badge-partial">mails/password-reset</span></div><div style="border:1px solid rgba(148,163,184,0.3);border-top:4px solid #1d4ed8;border-radius:12px;padding:18px;background:rgba(255,255,255,0.96);color:#0f172a"><div style="font-size:11px;font-weight:700;text-transform:uppercase;color:#1d4ed8;letter-spacing:.06em">Security Notification</div><h3 style="margin:4px 0 8px;font-size:18px;font-weight:800;color:#0f172a">Reset Your Account Password</h3><p style="font-size:13px;color:#334155;margin:0 0 14px">Tap the button below to reset your M&amp;M Heritage Hotel account password. This token expires in 60 minutes.</p><a href="/preview/password-reset" class="mm-button" style="width:100%;justify-content:center">Reset Account Password</a><p style="font-size:11.5px;color:#64748b;margin:12px 0 0">If you did not request a password reset, no further action is required.</p></div></div>
 </div></div></section>`;
 return shellWith(body,'','','Branded Email Templates (B3)',['System','Communications','Email Templates']);
}
function bookingDrawersPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Front-Desk &amp; POS Slide-Over App Drawers (W3.9)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Click any operational drawer below to inspect the 43 upgraded Blade &amp; fixture Slide-Over App Drawers (.mm-drawer).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-map-o"></i> Booking Wizard</a><a href="/preview/bookings" class="mm-button mm-button-secondary"><i class="fa fa-list"></i> Booking List</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Slide-over drawer summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-columns"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Blade Modal Partials</span><div class="mm-table-kpi-value">43 Upgraded <span class="mm-table-kpi-sub">100% .mm-drawer</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Fixture Drawers</span><div class="mm-table-kpi-value">29 / 29 <span class="mm-table-kpi-sub">Admin Screens</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-sliders"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Form Controls</span><div class="mm-table-kpi-value">40px Frosted <span class="mm-table-kpi-sub">Vertical Stack</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-keyboard-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Accessibility</span><div class="mm-table-kpi-value">ESC &amp; Backdrop <span class="mm-table-kpi-sub">Focus Safe</span></div></div></div></div>
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:14px">
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-paid">booking_check_in_modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">1. Express Room Check-In Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Assign room keycards, verify arrival time, and confirm check-in deposit.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-checkin"><i class="fa fa-key"></i> Open Check-In Drawer</button></div>
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-paid">extend-date-modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">2. Extend Stay Date &amp; Tariff Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Extend guest departure date with automatic room availability &amp; tariff preview.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-extend"><i class="fa fa-calendar-plus-o"></i> Open Extend Stay Drawer</button></div>
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-partial">extra-charge-modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">3. Post Extra Folio Charge Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Post minibar, laundry, airport transfer, or late-checkout charges to the folio.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-extracharge"><i class="fa fa-plus-circle"></i> Open Extra Charge Drawer</button></div>
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-paid">member-detail-modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">4. Add Co-Guest / Companion Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Register accompanying family members or corporate delegates with passport/NID.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-member"><i class="fa fa-user-plus"></i> Open Co-Guest Drawer</button></div>
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-paid">multi-account-pay-modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">5. Split / Multi-Account Tender Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Split settlement across Cash, Visa/Mastercard POS, bKash, and City Ledger.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-multipayment"><i class="fa fa-credit-card"></i> Open Split Tender Drawer</button></div>
  <div class="mm-panel tw-p-5"><span class="mm-badge mm-badge-partial">webcam-modal</span><h3 style="margin:8px 0 4px;font-size:16px;font-weight:700">6. Guest Webcam &amp; ID Capture Drawer</h3><p class="tw-text-sm tw-text-muted" style="margin:0 0 12px">Capture live guest photo or upload scanned passport/NID directly at Front Desk.</p><button type="button" class="mm-button" data-toggle="modal" data-target="#drawer-webcam"><i class="fa fa-camera"></i> Open Webcam Capture Drawer</button></div>
 </div>
 <div id="drawer-checkin" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-key"></i> Express Room Check-In · #BK-2026-104</h4></div><div class="modal-body"><div class="form-group"><label>Guest Name</label><input type="text" class="form-control" value="Tanvir Ahmed Chowdhury" readonly></div><div class="form-group"><label>Assigned Suite &amp; Keycard</label><select class="form-control"><option>Room 204 — Deluxe Double Suite (2 Keycards Encoded)</option></select></div><div class="form-group"><label>Check-In Timestamp</label><input type="text" class="form-control" value="2026-10-12 14:00 PM"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Confirm Check-In</button></div></div></div></div>
 <div id="drawer-extend" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-calendar-plus-o"></i> Extend Stay Date · Room 204</h4></div><div class="modal-body"><div class="form-group"><label>Current Check-Out Date</label><input type="date" class="form-control" value="2026-10-15" readonly></div><div class="form-group"><label>New Extended Check-Out Date</label><input type="date" class="form-control" value="2026-10-17"></div><div class="form-group"><label>Additional Nights &amp; Tariff</label><input type="text" class="form-control" value="+2 Nights · ৳ 8,400.00 (Room 204 Available)" readonly></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Apply Extension</button></div></div></div></div>
 <div id="drawer-extracharge" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-plus-circle"></i> Post Extra Folio Charge</h4></div><div class="modal-body"><div class="form-group"><label>Service / Charge Category</label><select class="form-control"><option>Airport Executive Limousine Pickup</option><option>In-Room Minibar &amp; Refreshments</option><option>Express Laundry &amp; Valet</option><option>Late Check-Out Half-Day Tariff</option></select></div><div class="form-group"><label>Charge Amount (৳)</label><input type="number" class="form-control" value="1500"></div><div class="form-group"><label>Folio Remarks</label><input type="text" class="form-control" value="DAC Airport Terminal 1 Arrival Transfer"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Post to Guest Folio</button></div></div></div></div>
 <div id="drawer-member" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-user-plus"></i> Add Co-Guest / Companion Member</h4></div><div class="modal-body"><div class="form-group"><label>Companion Full Name</label><input type="text" class="form-control" value="Farhana Yasmin Chowdhury"></div><div class="form-group"><label>Passport / National ID Number</label><input type="text" class="form-control" value="A09481204"></div><div class="form-group"><label>Relationship / Group Role</label><select class="form-control"><option>Spouse / Family Member</option><option>Corporate Delegate</option></select></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Save Co-Guest</button></div></div></div></div>
 <div id="drawer-multipayment" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-credit-card"></i> Split / Multi-Account Payment Tender</h4></div><div class="modal-body"><div class="form-group"><label>Cash Counter (৳)</label><input type="number" class="form-control" value="5000"></div><div class="form-group"><label>Visa / Mastercard POS Terminal (৳)</label><input type="number" class="form-control" value="7600"></div><div class="form-group"><label>bKash / Nagad Merchant Wallet (৳)</label><input type="number" class="form-control" value="0"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Apply Split Payment (৳ 12,600)</button></div></div></div></div>
 <div id="drawer-webcam" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-camera"></i> Guest Webcam &amp; Passport Capture</h4></div><div class="modal-body"><div class="well" style="text-align:center"><i class="fa fa-video-camera" style="font-size:36px;margin-bottom:8px;display:block"></i><strong>HD Front-Desk Camera Ready (1080p)</strong><p class="tw-text-sm tw-text-muted" style="margin:4px 0 0">Align guest face or passport MRZ within the frame.</p></div><div class="form-group"><label>Or Upload Scanned ID / Passport Image</label><input type="file" class="form-control"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-camera"></i> Capture &amp; Attach to Profile</button></div></div></div></div>
 </div></section>`;
 return shellWith(body,'','','Front-Desk & POS Slide-Over Drawers (W3.9)',['Hotel','Bookings','Slide-Over Drawers']);
}
function globalCompaniesPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Enterprise Companies &amp; Properties</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Manage hotel properties, TIN/BIN registration, and corporate billing entities (global/companies).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/global-groups" class="mm-button mm-button-secondary"><i class="fa fa-sitemap"></i> Group Hierarchy</a><button type="button" class="mm-button" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-plus"></i> Add Property</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Companies summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-building-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Registered Entities</span><div class="mm-table-kpi-value">3 Properties <span class="mm-table-kpi-sub">Active Licenses</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">VAT &amp; BIN Status</span><div class="mm-table-kpi-value">100% Verified <span class="mm-table-kpi-sub">Mushak 6.3 Ready</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Combined Inventory</span><div class="mm-table-kpi-value">60 Suites + 4 Halls <span class="mm-table-kpi-sub">2 F&amp;B Outlets</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-globe"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Primary Currency</span><div class="mm-table-kpi-value">BDT (৳) / USD <span class="mm-table-kpi-sub">Multi-Currency</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Company / Property Name</th><th>Group</th><th>BIN / Tax ID</th><th>Phone &amp; Email</th><th>Revenue Share</th><th>Status</th></tr></thead><tbody><tr><td>1</td><td><strong>M&amp;M Heritage Hotel &amp; Suites (Flagship)</strong></td><td>M&amp;M Hospitality Group</td><td><code>BIN-004192831-0101</code></td><td>+880-1711-000000 · info@mmheritagehotel.com</td><td><div class="mm-share-cell"><span>74%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:74%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Primary</span></td></tr><tr><td>2</td><td><strong>Heritage Convention &amp; Banquet Center</strong></td><td>M&amp;M Hospitality Group</td><td><code>BIN-004192831-0102</code></td><td>+880-1711-000001 · banquet@mmheritagehotel.com</td><td><div class="mm-share-cell"><span>26%</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:26%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Active</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Enterprise Companies (global/companies)',['Global Setup','Companies']);
}
function globalGroupsPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Enterprise Group Setup</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Parent group configuration for multi-property consolidation and inter-company accounting (global/groups).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/global-companies" class="mm-button mm-button-secondary"><i class="fa fa-building-o"></i> Companies</a></div></header>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Group Name</th><th>Headquarters</th><th>Member Properties</th><th>Status</th></tr></thead><tbody><tr><td>1</td><td><strong>M&amp;M Hospitality &amp; Resorts Group Ltd.</strong></td><td>Dhaka, Bangladesh</td><td>3 Active Properties</td><td><span class="mm-badge mm-badge-paid">Active</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Enterprise Groups (global/groups)',['Global Setup','Groups']);
}
function globalIdCardsPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Staff &amp; VIP ID Card Designer</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Configure PVC badge dimensions, QR/RFID encoding, and brand signature for employee &amp; VIP cards.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button"><i class="fa fa-check"></i> Save Card Template</button></div></header>
 <div class="mm-panel tw-p-5"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:14px"><div><label class="mm-label">Card Orientation</label><select class="form-control"><option>CR80 Portrait (54mm × 85.6mm)</option><option>CR80 Landscape</option></select></div><div><label class="mm-label">Header Accent Color</label><input type="text" class="form-control" value="#1D4ED8 (Heritage Sapphire)"></div><div><label class="mm-label">Barcode / RFID Mode</label><select class="form-control"><option>QR Code + Mifare 13.56MHz RFID</option><option>Code-128 Barcode</option></select></div><div><label class="mm-label">Authorized Signatory Title</label><input type="text" class="form-control" value="General Manager · M&amp;M Heritage Hotel"></div></div></div></div></section>`;
 return shellWith(body,'','','ID Card Settings (global/id-card-settings)',['Global Setup','ID Cards']);
}
function activityLogPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">System Audit &amp; Activity Log</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Immutable trail of booking modifications, folio settlements, POS voids, and voucher approvals.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/night-audit" class="mm-button mm-button-secondary"><i class="fa fa-moon-o"></i> Night Audits</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Activity log summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Logged Events (24h)</span><div class="mm-table-kpi-value">418 Actions <span class="mm-table-kpi-sub">Zero Anomalies</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Front-Desk Events</span><div class="mm-table-kpi-value">164 Logs <span class="mm-table-kpi-sub">Check-In &amp; Folio</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-cutlery"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">POS &amp; KOT Events</span><div class="mm-table-kpi-value">192 Logs <span class="mm-table-kpi-sub">Restaurant &amp; Bar</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-book"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Ledger Vouchers</span><div class="mm-table-kpi-value">62 Logs <span class="mm-table-kpi-sub">Approved</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Timestamp</th><th>Staff User</th><th>Module</th><th>Event &amp; Reference</th><th>Workstation IP</th></tr></thead><tbody><tr><td>2026-10-12 14:12:08</td><td><strong>Nusrat Jahan</strong></td><td><span class="mm-badge mm-badge-paid">Front Desk</span></td><td>Checked in Booking <code>#BK-2026-104</code> (Room 204)</td><td><code>192.168.10.14</code></td></tr><tr><td>2026-10-12 13:55:41</td><td><strong>Kamrul Hasan</strong></td><td><span class="mm-badge mm-badge-paid">Restaurant POS</span></td><td>Fired KOT &amp; settled Invoice <code>#RST-0101</code> (৳ 1,250.00)</td><td><code>192.168.10.22</code></td></tr><tr><td>2026-10-12 12:30:19</td><td><strong>System Admin</strong></td><td><span class="mm-badge mm-badge-partial">Accounting</span></td><td>Approved Receive Voucher <code>#RV-2026-088</code> (৳ 18,500.00)</td><td><code>192.168.10.05</code></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','System Activity Log (activity-log)',['System','Audit','Activity Log']);
}
function attendanceDevicesPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Biometric Attendance Devices</h1><p class="tw-m-0 tw-text-sm tw-text-muted">ZKTeco &amp; facial recognition terminal status and real-time staff punch synchronization (machine_int/attendance).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button"><i class="fa fa-refresh"></i> Pull Punch Logs Now</button></div></header>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Terminal ID</th><th>Location</th><th>Device IP &amp; Port</th><th>Today Punches</th><th>Sync Health</th><th>Status</th></tr></thead><tbody><tr><td><code>ZK-FD-01</code></td><td><strong>Front Office &amp; Concierge Lobby</strong></td><td><code>192.168.10.201:4370</code></td><td>86 punches</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Online</span></td></tr><tr><td><code>ZK-FB-02</code></td><td><strong>Main Kitchen &amp; Service Back-of-House</strong></td><td><code>192.168.10.202:4370</code></td><td>112 punches</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Online</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Biometric Attendance Devices',['System','Hardware','Attendance Devices']);
}
function paymentSchedulesPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Software &amp; AMC Payment Schedules</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Track annual maintenance contracts, cloud hosting, and SMS gateway renewal schedules.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-plus"></i> Add Schedule Drawer</button></div></header>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Billing Cycle</th><th>Service Description</th><th>Amount (৳)</th><th>Status</th></tr></thead><tbody><tr><td>1</td><td>Oct 2026</td><td><strong>ERP Cloud Hosting, Automated Backup &amp; SSL Renewal</strong></td><td class="text-right"><strong>15,000.00</strong></td><td><span class="mm-badge mm-badge-paid">Paid</span></td></tr></tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Payment Schedules (smart-soft-payment-schedules)',['System','Billing','Payment Schedules']);
}
function wikiPage(){
 const topics=[
  ['Front Desk / ফ্রন্ট ডেস্ক','1. Room Booking Board & 3-Step Booking Wizard','১. রুম স্ট্যাটাস বোর্ড ও ৩-ধাপের বুকিং উইজার্ড','Use quick date chips (Tonight / Weekend / 7 Days) on the Room Board, then launch the 3-Step Booking Wizard to select rooms from the Floor 1–3 Map, attach guest/passport details, and collect advance deposit.','রুম বোর্ডে তারিখ নির্বাচন করে খালি রুম দেখুন। এরপর ৩-ধাপের বুকিং উইজার্ডে ফ্লোর ম্যাপ (ফ্লোর ১–৩) থেকে রুম সিলেক্ট করুন, গেস্টের এনআইডি/পাসপোর্ট তথ্য দিন এবং অগ্রিম পেমেন্ট গ্রহণ করে বুকিং নিশ্চিত করুন।','/preview/booking-next','Open Booking Wizard / বুকিং উইজার্ড'],
  ['Front Desk / ফ্রন্ট ডেস্ক','2. Slide-Over App Drawers (Check-In, Extend Stay, Extra Charge)','২. স্লাইড-ওভার ড্রয়ার (চেক-ইন, অবস্থান বৃদ্ধি ও অতিরিক্ত চার্জ)','Click any booking action to open the right-hand Slide-Over Drawer (.mm-drawer): encode keycards on check-in, extend departure dates, post minibar/laundry charges, or add co-guests without leaving the page.','পেজ পরিবর্তন না করেই ডান পাশের স্লাইড-ওভার ড্রয়ারের মাধ্যমে দ্রুত রুম চেক-ইন, অবস্থানকাল বৃদ্ধি (Extend Date), মিনিবার/লন্ড্রি চার্জ সংযোজন এবং সহযাত্রী (Co-Guest) নিবন্ধন সম্পন্ন করুন।','/preview/booking-drawers','Test Slide-Over Drawers / ড্রয়ার গ্যালারি'],
  ['Front Desk / ফ্রন্ট ডেস্ক','3. Guest Checkout, Split Payment & Printable Folio Invoice','৩. গেস্ট চেক-আউট, মাল্টি-পেমেন্ট ও প্রিন্টেবল ইনভয়েস','Adjust stay nights (+/-), review consolidated Room + Restaurant + Bar + Service charges, split settlement across Cash/Card/bKash, and print the A4 Guest Folio Invoice.','চেক-আউটের সময় রাতের সংখ্যা (+/-) সমন্বয় করুন, রুম ও রেস্তোরাঁর সব বকেয়া চার্জ একসাথে দেখুন, ক্যাশ/কার্ড/বিকাশে পেমেন্ট নিন এবং এ৪ (A4) ইনভয়েস প্রিন্ট করুন।','/preview/checkout','Open Checkout Folio / চেক-আউট ফোলিও'],
  ['POS & Kitchen / রেস্তোরাঁ ও বার','4. Restaurant POS (Courses → Fire KOT → Pay) & 80mm Thermal Receipt','৪. রেস্তোরাঁ পিওএস (খাবার নির্বাচন → কিচেন KOT → বিল পেমেন্ট)','Select dine-in table or in-house room, add food items by category/search, fire the 80mm Thermal KOT to the Kitchen Display Board (KDS), and settle or transfer the bill to the guest room folio.','টেবিল অথবা গেস্ট রুম সিলেক্ট করুন, খাবার যোগ করে কিচেন বোর্ডে (KDS) অর্ডার পাঠান এবং ৮০ মিমি থার্মাল রিসিপ্ট প্রিন্ট করুন অথবা রুম ফোলিওতে বিল ট্রান্সফার করুন।','/preview/rst-pos','Launch Restaurant POS / রেস্তোরাঁ পিওএস'],
  ['POS & Kitchen / রেস্তোরাঁ ও বার','5. Interactive Table View & Kitchen Display Board (KDS)','৫. ইন্টারেক্টিভ টেবিল ভিউ ও কিচেন ডিসপ্লে বোর্ড (KDS)','Monitor Available vs. Occupied tables with live bill/guest/timer badges on the Table View, and track cooking timers on the real-time Kitchen Display Board.','টেবিল ভিউতে কোন টেবিল খালি বা অকুপাইড তা রিয়েল-টাইম টাইমার ও বিলের পরিমাণসহ দেখুন এবং কিচেন ডিসপ্লে বোর্ডে (KDS) খাবার তৈরির অগ্রগতি পর্যবেক্ষণ করুন।','/preview/rst-kitchen-board','Open Kitchen Board / কিচেন বোর্ড'],
  ['Inventory & Store / ইনভেন্টরি ও স্টোর','6. Kitchen Inventory & General Store (PO → GRN → Requisition → GIN)','৬. কিচেন ইনভেন্টরি ও জেনারেল স্টোর (ক্রয় থেকে স্টক রিপোর্ট)','Create supplier Purchase Orders with live Total/Paid/Due summary cards, receive goods (GRN), issue stock to departments (GIN), and monitor Stock-in-Hand with visual progress bars.','সাপ্লায়ার পারচেজ অর্ডার (PO) তৈরি করুন, পণ্য গ্রহণ (GRN) ও ডিপার্টমেন্টে ইস্যু (GIN) রেকর্ড করুন এবং ভিজ্যুয়াল প্রোগ্রেস বারসহ বর্তমান স্টক রিপোর্ট দেখুন।','/preview/gs-stock-in-hand','View Stock-in-Hand / স্টক রিপোর্ট'],
  ['Accounts & Finance / অ্যাকাউন্টস','7. Double-Entry Vouchers & Financial Statements with Share Bars','৭. ডাবল-এন্ট্রি ভাউচার ও ফিন্যান্সিয়াল স্টেটমেন্ট (.mm-share-bar)','Post Receive, Payment, Journal, and Contra vouchers, and inspect Balance Sheet, Income Statement, Trial Balance, Cash Flow, and Ratio Analysis with inline visual share bars.','রিসিভ, পেমেন্ট, জার্নাল ও কন্ট্রা ভাউচার এন্ট্রি করুন এবং ভিজ্যুয়াল শেয়ার বারসহ ব্যালেন্স শিট, ইনকাম স্টেটমেন্ট, ট্রায়াল ব্যালেন্স ও রেশিও অ্যানালাইসিস দেখুন।','/preview/acc-ratio-analysis','Open Ratio Analysis / রেশিও রিপোর্ট'],
  ['Shortcuts & Audit / শর্টকাট ও অডিট','8. Daily Night Audit SOP & Power Keyboard Shortcuts (Ctrl+K & g-Chords)','৮. প্রতিদিনের নাইট অডিট নিয়মাবলী ও কিবোর্ড শর্টকাট (Ctrl+K)','Press Ctrl+K to search all 317 screens, press ? for shortcut help, or use two-key chords: g d (Dashboard), g w (Booking Wizard), g r (Restaurant POS), g b (Bar POS), g k (KDS), g p (Preview Hub), g h (Wiki & Help).','Ctrl+K চেপে যেকোনো স্ক্রিন খুঁজুন, অথবা শর্টকাট ব্যবহার করুন: g d (ড্যাশবোর্ড), g w (বুকিং উইজার্ড), g r (রেস্তোরাঁ পিওএস), g b (বার পিওএস), g k (কিচেন বোর্ড), g h (উইকি ও হেল্প)।','/preview/night-audit-generate','Open Night Audit / নাইট অডিট'],
 ];
 const cards=topics.map(([cat,enTitle,bnTitle,enBody,bnBody,href,btn])=>'<article class="mm-panel tw-p-5 wiki-card" data-wiki-cat="'+cat+'" data-wiki-search="'+(cat+' '+enTitle+' '+bnTitle+' '+enBody+' '+bnBody).toLowerCase().replace(/"/g,'')+'"><div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:10px"><span class="mm-badge mm-badge-paid">'+cat+'</span><a href="'+href+'" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px;font-size:12px"><i class="fa fa-external-link"></i> '+btn+'</a></div><div class="wiki-lang-en" style="margin-bottom:12px"><h3 style="margin:0 0 6px;font-size:16px;font-weight:800">'+enTitle+'</h3><p class="tw-text-sm tw-text-muted" style="margin:0;line-height:1.55">'+enBody+'</p></div><div class="wiki-lang-bn" style="border-top:1px dashed rgba(148,163,184,0.35);padding-top:10px"><h4 style="margin:0 0 6px;font-size:15px;font-weight:700">'+bnTitle+'</h4><p class="tw-text-sm tw-text-muted" style="margin:0;line-height:1.65">'+bnBody+'</p></div></article>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">User Documentation, Wiki &amp; Help · ব্যবহারকারী ম্যানুয়াল ও সহায়িকা (EN / বাংলা)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Bilingual operational guide, SOP checklists, and keyboard shortcuts for all 10 ERP modules (docs/USER-WIKI-EN-BN.md).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button" data-toggle="modal" data-target="#wiki-drawer-audit"><i class="fa fa-check-square-o"></i> Night Audit SOP / নাইট অডিট চেকলিস্ট</button><button type="button" class="mm-button mm-button-secondary" data-toggle="modal" data-target="#wiki-drawer-pos"><i class="fa fa-cutlery"></i> POS &amp; Folio SOP / পিওএস গাইড</button><a href="/preview" class="mm-button mm-button-secondary"><i class="fa fa-th-large"></i> All 317 Screens</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Wiki and help summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-book"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Bilingual Wiki / দ্বিভাষিক উইকি</span><div class="mm-table-kpi-value">English + বাংলা <span class="mm-table-kpi-sub">10 Modules Covered</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-th-large"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Interactive Screens / মোট স্ক্রিন</span><div class="mm-table-kpi-value">317 Routes <span class="mm-table-kpi-sub">Direct Launch Links</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-keyboard-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Quick Chord / দ্রুত শর্টকাট</span><div class="mm-table-kpi-value">Press g then h <span class="mm-table-kpi-sub">Or Ctrl+K Search</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-list-ul"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">SOP Drawers / এসওপি ড্রয়ার</span><div class="mm-table-kpi-value">Shift Checklists <span class="mm-table-kpi-sub">Front Desk &amp; POS</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar"><div class="input-group" style="max-width:380px;flex:1 1 260px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-wiki-search" class="form-control" placeholder="Search Wiki in English or বাংলা (e.g., বুকিং, POS, চেক-আউট, ভাউচার)..."></div><div class="tw-flex tw-flex-wrap tw-gap-2" role="group" aria-label="Language filter"><button type="button" class="mm-preview-pill is-active" data-wiki-lang="both">Both / উভয় ভাষা (EN + বাংলা)</button><button type="button" class="mm-preview-pill" data-wiki-lang="en">English Only</button><button type="button" class="mm-preview-pill" data-wiki-lang="bn">শুধু বাংলা (Bengali)</button></div></div><div class="mm-preview-filter-pills" style="margin-top:10px"><button type="button" class="mm-preview-pill is-active" data-wiki-cat-btn="All">All Topics / সব বিষয় (8)</button><button type="button" class="mm-preview-pill" data-wiki-cat-btn="Front Desk / ফ্রন্ট ডেস্ক">Front Desk / ফ্রন্ট ডেস্ক (3)</button><button type="button" class="mm-preview-pill" data-wiki-cat-btn="POS & Kitchen / রেস্তোরাঁ ও বার">POS &amp; Kitchen / রেস্তোরাঁ ও বার (2)</button><button type="button" class="mm-preview-pill" data-wiki-cat-btn="Inventory & Store / ইনভেন্টরি ও স্টোর">Inventory &amp; Store / ইনভেন্টরি ও স্টোর (1)</button><button type="button" class="mm-preview-pill" data-wiki-cat-btn="Accounts & Finance / অ্যাকাউন্টস">Accounts &amp; Finance / অ্যাকাউন্টস (1)</button><button type="button" class="mm-preview-pill" data-wiki-cat-btn="Shortcuts & Audit / শর্টকাট ও অডিট">Shortcuts &amp; Audit / শর্টকাট ও অডিট (1)</button></div><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:14px;margin-top:14px" id="mm-wiki-grid">${cards}</div></div>
 <div class="mm-panel tw-p-5"><h2 class="tw-m-0 tw-mb-3 tw-text-lg tw-font-semibold">Keyboard Shortcuts &amp; Navigation Chords · কিবোর্ড শর্টকাট তালিকা</h2><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Shortcut / শর্টকাট</th><th>English Action</th><th>বাংলা বিবরণ (Bengali Description)</th><th>Target Route</th></tr></thead><tbody><tr><td><kbd>Ctrl+K</kbd> / <kbd>/</kbd></td><td>Open Global Command Palette &amp; Screen Search</td><td>গ্লোবাল কমান্ড প্যালেট ও স্ক্রিন সার্চ ওপেন করুন</td><td><code>Global Dialog</code></td></tr><tr><td><kbd>?</kbd></td><td>Open Keyboard Shortcuts Help Dialog</td><td>কিবোর্ড শর্টকাট সহায়িকা ডায়ালগ ওপেন করুন</td><td><code>#mm-shortcuts</code></td></tr><tr><td><kbd>g</kbd> then <kbd>d</kbd></td><td>Go to Dashboard &amp; Room Booking Board</td><td>ড্যাশবোর্ড এবং রুম বুকিং বোর্ডে যান</td><td><a href="/"><code>/</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>w</kbd></td><td>Go to 3-Step Booking Wizard &amp; Floor Plan</td><td>৩-ধাপের বুকিং উইজার্ড ও ফ্লোর ম্যাপে যান</td><td><a href="/preview/booking-next"><code>/preview/booking-next</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>r</kbd></td><td>Go to Restaurant POS Terminal</td><td>রেস্তোরাঁ পিওএস (POS) টার্মিনালে যান</td><td><a href="/preview/rst-pos"><code>/preview/rst-pos</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>b</kbd></td><td>Go to Bar &amp; Lounge POS Terminal</td><td>বার ও লাউঞ্জ পিওএস টার্মিনালে যান</td><td><a href="/preview/bar-pos"><code>/preview/bar-pos</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>k</kbd></td><td>Go to Kitchen Display Board (KDS)</td><td>কিচেন ডিসপ্লে বোর্ডে (KDS) যান</td><td><a href="/preview/rst-kitchen-board"><code>/preview/rst-kitchen-board</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>p</kbd></td><td>Go to All 317-Screen Preview Explorer Hub</td><td>সব স্ক্রিনের ইন্টারেক্টিভ প্রিভিউ হাবে যান</td><td><a href="/preview"><code>/preview</code></a></td></tr><tr><td><kbd>g</kbd> then <kbd>h</kbd></td><td>Open Bilingual User Wiki &amp; Help Center</td><td>দ্বিভাষিক ইউজার উইকি ও হেল্প সেন্টারে যান</td><td><a href="/preview/wiki"><code>/preview/wiki</code></a></td></tr></tbody></table></div></div>
 <div id="wiki-drawer-audit" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-check-square-o"></i> Daily Night Audit SOP · নাইট অডিট চেকলিস্ট</h4></div><div class="modal-body"><div class="well"><strong>English SOP Checklist:</strong><ol style="margin:6px 0 0;padding-left:18px"><li>Verify all Expected Arrivals &amp; Departures are checked in or checked out.</li><li>Confirm all Restaurant &amp; Bar POS running tables are settled or posted to room folios.</li><li>Reconcile Cash, Visa/Mastercard POS, and bKash/Nagad collections before clicking Generate Audit.</li></ol></div><div class="well"><strong>বাংলা চেকলিস্ট (Bengali SOP):</strong><ol style="margin:6px 0 0;padding-left:18px"><li>দিনের সব সম্ভাব্য চেক-ইন ও চেক-আউট তালিকা যাচাই করুন।</li><li>রেস্তোরাঁ ও বার পিওএস-এর সব রানিং টেবিল বিল নিষ্পত্তি বা রুম ফোলিওতে ট্রান্সফার হয়েছে কি না নিশ্চিত করুন।</li><li>ক্যাশ, কার্ড ও মোবাইল ব্যাংকিং কালেকশন মিলিয়ে নাইট অডিট সম্পন্ন করুন।</li></ol></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close / বন্ধ করুন</button><a href="/preview/night-audit-generate" class="mm-button"><i class="fa fa-arrow-right"></i> Go to Night Audit</a></div></div></div></div>
 <div id="wiki-drawer-pos" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-cutlery"></i> POS &amp; Room Folio Billing SOP · পিওএস নির্দেশিকা</h4></div><div class="modal-body"><div class="well"><strong>English POS Guide:</strong><ol style="margin:6px 0 0;padding-left:18px"><li>Select Table &amp; Waiter (for dine-in) or select In-House Guest Room (for room-service folio transfer).</li><li>Click menu items to build the order, then click Fire to Kitchen to print the 80mm KOT and notify KDS.</li><li>Settle via Cash/Card/Split Tender or post directly to the guest room folio.</li></ol></div><div class="well"><strong>বাংলা পিওএস নির্দেশিকা:</strong><ol style="margin:6px 0 0;padding-left:18px"><li>ডাইন-ইন অর্ডারের জন্য টেবিল ও ওয়েটার সিলেক্ট করুন, অথবা রুম সার্ভিসের জন্য গেস্ট রুম নির্বাচন করুন।</li><li>মেনু থেকে খাবার সিলেক্ট করে কিচেনে (KDS) পাঠান এবং ৮০ মিমি থার্মাল KOT প্রিন্ট করুন।</li><li>ক্যাশ বা কার্ডে বিল গ্রহণ করুন অথবা সরাসরি গেস্টের রুম ফোলিওতে বিল যুক্ত করুন।</li></ol></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close / বন্ধ করুন</button><a href="/preview/rst-pos" class="mm-button"><i class="fa fa-cutlery"></i> Open Restaurant POS</a></div></div></div></div>
 </div></section><script>(function(){var q=document.getElementById("mm-wiki-search"),catBtns=document.querySelectorAll("[data-wiki-cat-btn]"),langBtns=document.querySelectorAll("[data-wiki-lang]"),cards=document.querySelectorAll(".wiki-card"),cat="All",lang="both";function apply(){var s=(q&&q.value||"").toLowerCase().trim();for(var i=0;i<cards.length;i++){var c=cards[i],ok=(cat==="All"||c.getAttribute("data-wiki-cat")===cat)&&(!s||c.getAttribute("data-wiki-search").indexOf(s)>=0);c.style.display=ok?"":"none";var en=c.querySelector(".wiki-lang-en"),bn=c.querySelector(".wiki-lang-bn");if(en)en.style.display=(lang==="bn")?"none":"";if(bn){bn.style.display=(lang==="en")?"none":"";bn.style.borderTop=(lang==="bn")?"none":"1px dashed rgba(148,163,184,0.35)";bn.style.paddingTop=(lang==="bn")?"0":"10px";}}}if(q)q.addEventListener("input",apply);for(var j=0;j<catBtns.length;j++){catBtns[j].addEventListener("click",function(){for(var k=0;k<catBtns.length;k++)catBtns[k].classList.remove("is-active");this.classList.add("is-active");cat=this.getAttribute("data-wiki-cat-btn");apply();});}for(var m=0;m<langBtns.length;m++){langBtns[m].addEventListener("click",function(){for(var n=0;n<langBtns.length;n++)langBtns[n].classList.remove("is-active");this.classList.add("is-active");lang=this.getAttribute("data-wiki-lang");apply();});}})();</script>`;
 return shellWith(body,'','','User Wiki & Help (EN / বাংলা)',['System','Documentation','Wiki & Help']);
}
function sopPrintPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Printable Bilingual SOP Cheat Sheet · ফ্রন্ট ডেস্ক ও পিওএস প্রিন্ট শিট</h1><p class="tw-m-0 tw-text-sm tw-text-muted">1-page A4 quick-reference sheet for Front Desk, Restaurant POS, Bar Counter, and Night Audit staff.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print A4 Cheat Sheet / প্রিন্ট করুন</button><a href="/preview/wiki" class="mm-button mm-button-secondary"><i class="fa fa-book"></i> Back to Wiki / উইকি</a></div></header>
 <div id="print_body" class="invoice-doc"><section class="mm-print-sheet mm-print-sheet-a4"><div style="border-bottom:2px solid #1d4ed8;padding-bottom:10px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center"><div><h2 style="margin:0;font-size:18px;font-weight:800">M&amp;M HERITAGE HOTEL &amp; SUITES · STAFF QUICK-REFERENCE SOP</h2><p style="margin:2px 0 0;font-size:12px">Front Desk, Restaurant POS, Bar POS, Kitchen KDS &amp; Night Audit (English + বাংলা)</p></div><span class="mm-badge mm-badge-paid">Rev 2026.10</span></div>
 <table class="table table-bordered" style="margin-bottom:14px"><thead><tr><th width="20%">Shortcut / শর্টকাট</th><th width="40%">English Action</th><th width="40%">বাংলা নির্দেশিকা (Bengali Action)</th></tr></thead><tbody>
 <tr><td><code>Ctrl + K</code> / <code>Cmd + K</code></td><td>Open Global Command Palette (Search all 319 screens)</td><td>কমান্ড প্যালেট খুলুন (যেকোনো স্ক্রিন ও অ্যাকশন খুঁজুন)</td></tr>
 <tr><td><code>g d</code> · <code>g w</code></td><td>Dashboard Room Board (<code>g d</code>) · 3-Step Booking Wizard (<code>g w</code>)</td><td>ড্যাশবোর্ড রুম বোর্ড (<code>g d</code>) · ৩-ধাপের বুকিং উইজার্ড (<code>g w</code>)</td></tr>
 <tr><td><code>g r</code> · <code>g b</code> · <code>g k</code></td><td>Restaurant POS (<code>g r</code>) · Bar POS (<code>g b</code>) · Kitchen KDS (<code>g k</code>)</td><td>রেস্তোরাঁ পিওএস (<code>g r</code>) · বার পিওএস (<code>g b</code>) · কিচেন বোর্ড (<code>g k</code>)</td></tr>
 <tr><td><code>g a</code> · <code>g p</code> · <code>g h</code></td><td>Account Vouchers (<code>g a</code>) · Preview Hub (<code>g p</code>) · Wiki (<code>g h</code>)</td><td>অ্যাকাউন্ট ভাউচার (<code>g a</code>) · প্রিভিউ হাব (<code>g p</code>) · হেল্প উইকি (<code>g h</code>)</td></tr>
 <tr><td><code>/</code> · <code>?</code> · <code>Esc</code></td><td>Sidebar Search (<code>/</code>) · Shortcut Help (<code>?</code>) · Close Drawer (<code>Esc</code>)</td><td>মেনু সার্চ (<code>/</code>) · শর্টকাট তালিকা (<code>?</code>) · ড্রয়ার বন্ধ (<code>Esc</code>)</td></tr>
 </tbody></table>
 <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
  <div class="well" style="margin:0"><strong>Front-Desk Shift Handover SOP (EN / বাংলা):</strong><ol style="margin:6px 0 0;padding-left:18px;font-size:12px"><li>Check Expected Arrivals &amp; VIP Room readiness / আজকের সম্ভাব্য চেক-ইন ও ভিআইপি রুম যাচাই করুন।</li><li>Use Slide-Over Drawers for Check-In, Extend Stay &amp; Extra Charge / চেক-ইন ও অতিরিক্ত চার্জের জন্য স্লাইড-ওভার ড্রয়ার ব্যবহার করুন।</li><li>Verify Cash, Card &amp; bKash balance before shift sign-off / শিফট শেষে ক্যাশ, কার্ড ও বিকাশ ব্যালেন্স মিলিয়ে নিন।</li></ol></div>
  <div class="well" style="margin:0"><strong>Night Audit &amp; POS Closing SOP (EN / বাংলা):</strong><ol style="margin:6px 0 0;padding-left:18px;font-size:12px"><li>Settle or transfer all open Restaurant &amp; Bar POS tables to room folios / সব রানিং টেবিল বিল নিষ্পত্তি বা রুম ফোলিওতে ট্রান্সফার করুন।</li><li>Review Today's Activities &amp; Pending Departures / আজকের সব লেনদেন ও চেক-আউট তালিকা যাচাই করুন।</li><li>Run Night Audit to close and advance the business date / নাইট অডিট সম্পন্ন করে পরবর্তী বিজনেস তারিখে যান।</li></ol></div>
 </div></section></div></div></section>`;
 return shellWith(body,'','','Printable Bilingual SOP Cheat Sheet',['System','Documentation','Printable SOP']);
}
function onboardingPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Role-Based Staff Onboarding &amp; Training Hub · কর্মী প্রশিক্ষণ কেন্দ্র (EN / বাংলা)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Interactive training tracks for Front Desk, POS Cashiers, Kitchen Chefs, Store Officers, and Night Auditors.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/wiki" class="mm-button mm-button-secondary"><i class="fa fa-book"></i> Bilingual Wiki</a><a href="/preview/sop-print" class="mm-button"><i class="fa fa-print"></i> Print SOP Sheet</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Onboarding summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-graduation-cap"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Role Tracks / প্রশিক্ষণ বিভাগ</span><div class="mm-table-kpi-value">5 Core Roles <span class="mm-table-kpi-sub">EN + বাংলা</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Staff Certified / প্রশিক্ষিত কর্মী</span><div class="mm-table-kpi-value">94.8% <span class="mm-table-kpi-sub">All Shifts</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-clock-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Avg Training Time / গড় সময়</span><div class="mm-table-kpi-value">25 Mins <span class="mm-table-kpi-sub">Hands-On</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-print"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">A4 Desk Cards / ডেস্ক গাইড</span><div class="mm-table-kpi-value">Ready <span class="mm-table-kpi-sub">/preview/sop-print</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Role / পদবি</th><th>English Training Modules</th><th>বাংলা প্রশিক্ষণ মডিউল (Bengali Modules)</th><th>Readiness</th><th>Practice Screen</th></tr></thead><tbody>
 <tr><td><strong>1. Front-Desk Receptionist<br><small>ফ্রন্ট ডেস্ক রিসেপশনিস্ট</small></strong></td><td>Room Board, 3-Step Booking Wizard, Floor Plan Map, Slide-Over Check-In &amp; Checkout Folio</td><td>রুম বোর্ড, ৩-ধাপের বুকিং উইজার্ড, ফ্লোর ম্যাপ, স্লাইড-ওভার চেক-ইন এবং চেক-আউট ফোলিও</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><a href="/preview/booking-next" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px">Practice →</a></td></tr>
 <tr><td><strong>2. Restaurant &amp; Bar Cashier<br><small>রেস্তোরাঁ ও বার ক্যাশিয়ার</small></strong></td><td>Interactive Table View, 3-Step POS (Courses → Fire KOT → Pay), Split Tender &amp; Folio Transfer</td><td>টেবিল ভিউ, ৩-ধাপের পিওএস (অর্ডার → KOT → বিল), মাল্টি-পেমেন্ট এবং রুম ফোলিও ট্রান্সফার</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><a href="/preview/rst-pos" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px">Practice →</a></td></tr>
 <tr><td><strong>3. Executive Chef &amp; KDS<br><small>কিচেন শেফ ও কেডিএস</small></strong></td><td>Real-time Kitchen Display Board (KDS), 80mm Thermal KOT tickets &amp; Recipe Production Requisitions</td><td>রিয়েল-টাইম কিচেন ডিসপ্লে বোর্ড (KDS), ৮০ মিমি থার্মাল টিকিট এবং কিচেন রিকুইজিশন</td><td><div class="mm-share-cell"><span>96%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:96%"></span></span></div></td><td><a href="/preview/rst-kitchen-board" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px">Practice →</a></td></tr>
 <tr><td><strong>4. Store &amp; Inventory Officer<br><small>স্টোর ও ইনভেন্টরি অফিসার</small></strong></td><td>Supplier Purchase Orders (Total/Paid/Due cards), GRN Receive, Department GIN &amp; Stock-in-Hand</td><td>সাপ্লায়ার পারচেজ অর্ডার (PO), পণ্য গ্রহণ (GRN), ডিপার্টমেন্টে ইস্যু (GIN) এবং স্টক রিপোর্ট</td><td><div class="mm-share-cell"><span>92%</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:92%"></span></span></div></td><td><a href="/preview/gs-stock-in-hand" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px">Practice →</a></td></tr>
 <tr><td><strong>5. Night Auditor &amp; Accountant<br><small>নাইট অডিটর ও অ্যাকাউন্ট্যান্ট</small></strong></td><td>Hotel/POS Night Audit Closing, Double-Entry Vouchers, Balance Sheet &amp; Ratio Share Analysis</td><td>হোটেল ও পিওএস নাইট অডিট, ডাবল-এন্ট্রি ভাউচার, ব্যালেন্স শিট এবং রেশিও অ্যানালাইসিস</td><td><div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><a href="/preview/acc-ratio-analysis" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px">Practice →</a></td></tr>
 </tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Role-Based Staff Onboarding (EN / বাংলা)',['System','Documentation','Staff Onboarding']);
}
function tapeChartPage(){
 const tapeRows=[
  ['#101','1','Deluxe Twin','3,600','Tanvir Chowdhury (#BK-101 · Checked-In)',3,'Available',2,'Orion Pharma (#BK-121 · Confirmed)',2,'paid','Tanvir Ahmed Chowdhury · +880 1711-948210 · Orion Pharma'],
  ['#102','1','Deluxe King','4,500','Nadia Islam (#BK-102 · Acme Global)',4,'Available',1,'Dr. Mahmudul Karim (#BK-122)',2,'paid','Nadia Islam · +880 1713-220491 · Acme Global'],
  ['#103','1','Deluxe King','4,500','Dr. Farhana Rahman (#BK-103 · VIP Platinum)',5,'HK Turnaround',1,'Available',1,'paid','Dr. Farhana Rahman · +880 1819-334812 · Square Hospitals'],
  ['#104','1','Executive Twin','3,800','Available',1,'Rafiqul Islam Bhuiyan (#BK-104 · Beximco)',4,'Available',2,'partial','Rafiqul Islam Bhuiyan · +880 1911-458920 · Beximco Textiles'],
  ['#105','1','Superior Double','4,200','Tahmina Akter (#BK-105 · BRAC Bank)',3,'HK Turnaround',1,'Anisur Rahman (#BK-123 · Confirmed)',3,'paid','Tahmina Akter · +880 1611-883920 · BRAC Bank PLC'],
  ['#106','1','Standard Single','2,800','Available',2,'Syed Kamrul Hasan (#BK-106 · Grameenphone)',3,'Available',2,'partial','Syed Kamrul Hasan · +880 1715-609281 · Grameenphone'],
  ['#201','2','Heritage Suite','6,500','Nusrat Jahan (#BK-107 · UNICEF Mission)',4,'Available',1,'UN Delegation (#BK-124 · Confirmed)',2,'paid','Nusrat Jahan · +880 1819-552104 · UNICEF Mission'],
  ['#202','2','Heritage Suite','6,500','HK Turnaround',1,'Engr. Tariqul Alam (#BK-108 · Summit Power)',4,'Available',2,'unpaid','Engr. Tariqul Alam · +880 1712-990412 · Summit Power'],
  ['#203','2','Superior Twin','3,900','Shabnam Mustari (#BK-109 · Biman Crew)',2,'Available',2,'Biman Flight Crew (#BK-125)',3,'paid','Shabnam Mustari · +880 1714-772109 · Biman Bangladesh'],
  ['#204','2','Royal Family Suite','8,500','Brig. Gen. Mahmud (#BK-110 · VIP Stay)',4,'HK Turnaround',1,'Diplomatic Envoy (#BK-126)',2,'paid','Brig. Gen. Mahmudul Hasan · +880 1711-004921 · Defense Delegation'],
  ['#205','2','Deluxe Double','4,400','Ashraful Alam Siddiqui (#BK-111 · Walton)',3,'Available',2,'Walton Executive Team (#BK-127)',2,'paid','Ashraful Alam Siddiqui · +880 1817-203948 · Walton Hi-Tech'],
  ['#206','2','Executive King','5,200','Available',1,'Barrister Zaima Rahman (#BK-112 · Confirmed)',4,'Available',2,'partial','Barrister Zaima Rahman · +880 1716-839201 · Supreme Court Bar'],
  ['#301','3','Diplomatic Suite','9,500','Amb. Kenji Takahashi (#BK-113 · Embassy of Japan)',5,'HK Turnaround',1,'JICA Mission (#BK-128)',1,'paid','Ambassador Kenji Takahashi · +880 1711-889900 · Embassy of Japan'],
  ['#302','3','Presidential Suite','14,500','Available',2,'Horizon Aviation Crew (#BK-114 · 5 Nights)',5,'',0,'paid','Capt. Jonathan Vance · +880 1713-774411 · Horizon Aviation'],
  ['#303','3','Executive Twin','4,800','Meher Afroz Shaon (#BK-115 · Berger Paints)',3,'Available',2,'Berger Regional Summit (#BK-129)',2,'paid','Meher Afroz Shaon · +880 1912-665432 · Berger Paints'],
  ['#304','3','Heritage Double Suite','7,200','Salma Khatun (#BK-116 · UNDP · Departing)',2,'Available',2,'UNDP Country Team (#BK-130)',3,'paid','Salma Khatun · +880 1718-392011 · UNDP Mission'],
  ['#305','3','Family Interconnecting','8,200','Imran Hossain & Family (#BK-117 · City Group)',4,'Available',3,'',0,'paid','Imran Hossain · +880 1811-902384 · City Group Family'],
  ['#401','4','Royal Penthouse','12,000','Available',1,'Dr. Ahsan Habib (#BK-118 · icddr,b Keynote)',4,'Available',2,'partial','Dr. Ahsan Habib · +880 1711-332211 · icddr,b Summit'],
  ['#402','4','Skyview Executive','9,800','Elena Rostova (#BK-119 · World Bank)',5,'Available',2,'',0,'paid','Elena Rostova · +880 1719-445566 · World Bank Consultant'],
  ['#403','4','Grand Presidential','16,500','Rubana Huq (#BK-120 · Mohammadi Group VIP)',6,'HK Turnaround',1,'',0,'paid','Rubana Huq · +880 1711-223344 · Mohammadi Group'],
 ];
 const badgeFor=(txt,def)=>txt==='Available'?'mm-badge-partial':(txt==='HK Turnaround'?'mm-badge-unpaid':'mm-badge-'+(def||'paid'));
 const trs=tapeRows.map(([rm,fl,cat,rate,s1,c1,s2,c2,s3,c3,b1,guestMeta])=>'<tr class="tape-room-row" data-floor="'+fl+'" data-room="'+rm+' · '+cat+' (Floor '+fl+' · ৳ '+rate+'/nt)" data-guest="'+guestMeta+'" data-search="'+(rm+' '+cat+' '+s1+' '+s2+' '+s3+' '+guestMeta).toLowerCase().replace(/"/g,'')+'" style="cursor:pointer" data-toggle="modal" data-target="#tape-drawer"><td><strong>'+rm+' · '+cat+'</strong><div class="tw-text-xs tw-text-muted">Floor '+fl+' · ৳ '+rate+'/nt</div></td><td colspan="'+c1+'"><span class="mm-badge '+badgeFor(s1,b1)+'">'+s1+'</span></td>'+(c2?'<td colspan="'+c2+'"><span class="mm-badge '+badgeFor(s2,'paid')+'">'+s2+'</span></td>':'')+(c3?'<td colspan="'+c3+'"><span class="mm-badge '+badgeFor(s3,'paid')+'">'+s3+'</span></td>':'')+'</tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">7-Day Reservation Tape Chart · রুম ক্যালেন্ডার গ্রিড (Plan A #2 · Plan B F6)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">20 Rooms × 7-Day visual availability timeline, conflict-guarded stay blocks, and Server-Side Reservation Cart (<code>booking_carts</code>).</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/plan-a" class="mm-button mm-button-secondary"><i class="fa fa-sitemap"></i> Plan A Hub</a><a href="/preview/rooms" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> 20 Rooms Directory</a><a href="/preview/guests" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> 20 Guests CRM</a><a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-map-o"></i> 3-Step Booking Wizard</a><button type="button" class="mm-button" data-toggle="modal" data-target="#tape-drawer"><i class="fa fa-calendar-plus-o"></i> Quick Reservation Drawer</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Tape chart summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-calendar"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">7-Day Occupancy / অকুপেন্সি</span><div class="mm-table-kpi-value">85.0% <span class="mm-table-kpi-sub">20 Suites · 14–20 Oct 2026</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">In-House Stays / অবস্থানরত</span><div class="mm-table-kpi-value">14 Suites <span class="mm-table-kpi-sub">Checked-In Guests</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-shopping-cart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Server Cart (booking_carts)</span><div class="mm-table-kpi-value" id="tape-kpi-cart">2 Suites Held <span class="mm-table-kpi-sub">Cookie Cart Replaced</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Overlap Conflict Guard</span><div class="mm-table-kpi-value">0 Conflicts <span class="mm-table-kpi-sub">DB Row-Lock Active</span></div></div></div></div>
 <div id="tape-cart-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;"><i class="fa fa-check-circle"></i> <strong id="tape-cart-banner-msg">Suite block added to server-side <code>booking_carts</code> table with zero date-overlap conflicts!</strong></div>
 <div class="mm-panel tw-p-5" id="tape-cart-panel"><div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:8px;margin-bottom:10px"><div><strong><i class="fa fa-shopping-cart"></i> Plan A Workflow #2 · Server-Side Reservation Cart (<code>booking_carts</code>) &amp; Conflict Guard</strong><div class="tw-text-xs tw-text-muted">Replaces legacy <code>booking_info</code> browser cookies with server-validated room holds and instant conflict checking.</div></div><a href="/preview/booking-next" class="mm-button" style="min-height:30px;padding:3px 12px"><i class="fa fa-arrow-right"></i> Checkout Cart in 3-Step Wizard →</a></div><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:10px;align-items:end;margin-bottom:10px"><div><label class="mm-label">Select Available Suite</label><select id="tape-cart-room" class="form-control"><option value="#104 · Executive Twin|3800">#104 · Executive Twin (৳ 3,800/nt)</option><option value="#106 · Standard Single|2800">#106 · Standard Single (৳ 2,800/nt)</option><option value="#206 · Executive King|5200">#206 · Executive King (৳ 5,200/nt)</option><option value="#302 · Presidential Suite|14500">#302 · Presidential Suite (৳ 14,500/nt)</option><option value="#401 · Royal Penthouse|12000">#401 · Royal Penthouse (৳ 12,000/nt)</option></select></div><div><label class="mm-label">Stay Window</label><select id="tape-cart-dates" class="form-control"><option value="14 Oct → 16 Oct (2 Nights)|2">14 Oct → 16 Oct (2 Nights)</option><option value="15 Oct → 18 Oct (3 Nights)|3">15 Oct → 18 Oct (3 Nights)</option><option value="16 Oct → 20 Oct (4 Nights)|4">16 Oct → 20 Oct (4 Nights)</option></select></div><div><label class="mm-label">Rate Plan</label><select id="tape-cart-plan" class="form-control"><option value="BAR-2026 (Flexible)">BAR-2026 (Flexible)</option><option value="CORP-VIP (-15% Contract)">CORP-VIP (-15% Contract)</option><option value="WKND-3N (-20% Escape)">WKND-3N (-20% Escape)</option></select></div><div><button type="button" id="tape-btn-add-cart" class="mm-button" style="width:100%"><i class="fa fa-plus-circle"></i> + Hold in Server Cart</button></div></div><div class="tw-flex tw-flex-wrap tw-gap-2" id="tape-cart-chips"><span class="mm-badge mm-badge-paid"><i class="fa fa-lock"></i> Cart #1: Suite #101 · 14–16 Oct (2N · ৳ 17,000)</span><span class="mm-badge mm-badge-paid"><i class="fa fa-lock"></i> Cart #2: Suite #201 · 14–16 Oct (2N · ৳ 25,000)</span></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar" style="margin-bottom:12px"><div class="input-group" style="max-width:100%;min-width:0;flex:1 1 200px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-tape-search" class="form-control" placeholder="Search 20 rooms or guests (e.g., #204, Tanvir, Diplomatic, Penthouse)..."></div><div class="mm-preview-filter-pills"><button type="button" class="mm-preview-pill is-active" data-tape-floor="all">All Floors (20)</button><button type="button" class="mm-preview-pill" data-tape-floor="1">Floor 1 · Deluxe (6)</button><button type="button" class="mm-preview-pill" data-tape-floor="2">Floor 2 · Suites (6)</button><button type="button" class="mm-preview-pill" data-tape-floor="3">Floor 3 · Diplomatic (5)</button><button type="button" class="mm-preview-pill" data-tape-floor="4">Floor 4 · Penthouse (3)</button></div></div><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped" id="mm-tape-table"><thead><tr><th width="18%">Suite / Room (20)</th><th>Wed 14 Oct</th><th>Thu 15 Oct</th><th>Fri 16 Oct</th><th>Sat 17 Oct</th><th>Sun 18 Oct</th><th>Mon 19 Oct</th><th>Tue 20 Oct</th></tr></thead><tbody>${trs}</tbody></table></div></div>
 <div id="tape-drawer" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-calendar-check-o"></i> Tape Chart Reservation Inspector</h4></div><div class="modal-body"><div class="form-group"><label>Selected Suite</label><input type="text" id="tape-drawer-room" class="form-control" value="#204 · Royal Family Suite (Floor 2 · ৳ 8,500/nt)" readonly></div><div class="form-group"><label>Primary Guest &amp; Corporate Account</label><input type="text" id="tape-drawer-guest" class="form-control" value="Brig. Gen. Mahmudul Hasan · +880 1711-004921 · Defense Delegation" readonly></div><div class="form-group"><label>Stay Window</label><input type="text" class="form-control" value="2026-10-14 → 2026-10-18 (4 Nights)"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button><a href="/preview/booking-next" class="mm-button">Open in Booking Wizard</a></div></div></div></div></div></section>
 <script>(function(){var q=document.getElementById("mm-tape-search"),btns=document.querySelectorAll("[data-tape-floor]"),rows=document.querySelectorAll(".tape-room-row"),fl="all";function run(){var s=(q&&q.value||"").toLowerCase().trim();for(var i=0;i<rows.length;i++){var r=rows[i],ok=(fl==="all"||r.getAttribute("data-floor")===fl)&&(!s||r.getAttribute("data-search").indexOf(s)>=0);r.style.display=ok?"":"none";}}if(q)q.addEventListener("input",run);for(var j=0;j<btns.length;j++){btns[j].addEventListener("click",function(){for(var k=0;k<btns.length;k++)btns[k].classList.remove("is-active");this.classList.add("is-active");fl=this.getAttribute("data-tape-floor");run();});}for(var m=0;m<rows.length;m++){rows[m].addEventListener("click",function(){var rm=document.getElementById("tape-drawer-room"),gs=document.getElementById("tape-drawer-guest");if(rm)rm.value=this.getAttribute("data-room");if(gs)gs.value=this.getAttribute("data-guest");});}var addBtn=document.getElementById("tape-btn-add-cart"),chips=document.getElementById("tape-cart-chips"),kpiC=document.getElementById("tape-kpi-cart"),ban=document.getElementById("tape-cart-banner"),banMsg=document.getElementById("tape-cart-banner-msg");if(addBtn&&chips){addBtn.addEventListener("click",function(){var rParts=(document.getElementById("tape-cart-room").value||"").split("|"),dParts=(document.getElementById("tape-cart-dates").value||"").split("|"),pl=document.getElementById("tape-cart-plan").value;var n=chips.querySelectorAll(".mm-badge").length+1,tot=Number(rParts[1]||3800)*Number(dParts[1]||2);var sp=document.createElement("span");sp.className="mm-badge mm-badge-paid";sp.innerHTML='<i class="fa fa-lock"></i> Cart #'+n+": "+rParts[0]+" · "+dParts[0]+" · "+pl+" (৳ "+tot.toLocaleString("en-US")+")";chips.appendChild(sp);if(kpiC)kpiC.innerHTML=n+' Suites Held <span class="mm-table-kpi-sub">Cookie Cart Replaced</span>';if(ban&&banMsg){banMsg.textContent="Added "+rParts[0]+" ("+dParts[0]+") to server-side booking_carts — 0 overlap conflicts!";ban.style.display="block";}});}})();</script>`;
 return shellWith(body,'','','7-Day Reservation Tape Chart (F6)',['Hotel','Bookings','Tape Chart']);
}
function roomsDirectoryPage(){
 const rooms=[
  ['#101','1','East Wing','Deluxe Twin','Twin Beds · 2 Adults','3,600','Occupied Clean','Tanvir Ahmed Chowdhury (#BK-101)',92,'paid'],
  ['#102','1','East Wing','Deluxe King','King Bed · 2 Adults','4,500','Occupied Clean','Nadia Islam (#BK-102)',88,'paid'],
  ['#103','1','East Wing','Deluxe King','King Bed · 2 Adults','4,500','Occupied Clean','Dr. Farhana Rahman (#BK-103 · VIP)',95,'paid'],
  ['#104','1','Garden Wing','Executive Twin','Twin Beds · 2 Adults','3,800','Inspected Ready','Vacant → Arriving: Rafiqul Islam (#BK-104)',78,'partial'],
  ['#105','1','Garden Wing','Superior Double','Queen Bed · 2 Adults','4,200','Occupied Clean','Tahmina Akter (#BK-105)',84,'paid'],
  ['#106','1','Courtyard','Standard Single','Single Bed · 1 Adult','2,800','Inspected Ready','Vacant → Arriving: Syed Kamrul Hasan (#BK-106)',72,'partial'],
  ['#201','2','VIP Wing','Heritage Suite','King Suite · 3 Adults','6,500','Occupied Clean','Nusrat Jahan (#BK-107 · UNICEF)',94,'paid'],
  ['#202','2','VIP Wing','Heritage Suite','King Suite · 3 Adults','6,500','Turnaround Cleaning','HK Prep → Arriving: Engr. Tariqul Alam (#BK-108)',86,'unpaid'],
  ['#203','2','West Wing','Superior Twin','Twin Beds · 2 Adults','3,900','Occupied Clean','Shabnam Mustari (#BK-109 · Biman Crew)',82,'paid'],
  ['#204','2','Balcony Wing','Royal Family Suite','2 King Beds · 4 Adults','8,500','VIP Turndown','Brig. Gen. Mahmudul Hasan (#BK-110 · VIP)',98,'paid'],
  ['#205','2','West Wing','Deluxe Double','Double Bed · 2 Adults','4,400','Occupied Clean','Ashraful Alam Siddiqui (#BK-111)',85,'paid'],
  ['#206','2','Balcony Wing','Executive King','King Bed · 2 Adults','5,200','Inspected Ready','Vacant → Arriving: Barrister Zaima Rahman (#BK-112)',80,'partial'],
  ['#301','3','Diplomatic Wing','Diplomatic Suite','King + Lounge · 3 Adults','9,500','VIP Turndown','Amb. Kenji Takahashi (#BK-113 · Japan Embassy)',96,'paid'],
  ['#302','3','Panoramic Wing','Presidential Suite','2 Bedroom Suite · 4 Adults','14,500','Occupied Clean','Capt. Jonathan Vance (#BK-114 · Horizon Crew)',91,'paid'],
  ['#303','3','Panoramic Wing','Executive Twin','Twin Beds · 2 Adults','4,800','Occupied Clean','Meher Afroz Shaon (#BK-115)',87,'paid'],
  ['#304','3','Diplomatic Wing','Heritage Double Suite','King + Sofa · 3 Adults','7,200','Checkout Pending','Salma Khatun (#BK-116 · UNDP)',89,'partial'],
  ['#305','3','Garden Wing','Family Interconnecting','Triple + Twin · 5 Adults','8,200','Occupied Clean','Imran Hossain & Family (#BK-117)',90,'paid'],
  ['#401','4','Penthouse','Royal Penthouse Suite','King + Terrace · 4 Adults','12,000','Inspected Ready','Vacant → Arriving: Dr. Ahsan Habib (#BK-118)',88,'partial'],
  ['#402','4','Penthouse','Skyview Executive Suite','King + Study · 2 Adults','9,800','Occupied Clean','Elena Rostova (#BK-119 · World Bank)',93,'paid'],
  ['#403','4','Penthouse','Grand Heritage Presidential','Master Penthouse · 6 Adults','16,500','VIP Turndown','Rubana Huq (#BK-120 · Mohammadi Group VIP)',99,'paid'],
 ];
 const rows=rooms.map(([rm,fl,wing,cat,beds,rate,hk,guest,occ,badge],i)=>'<tr class="rm-dir-row" data-floor="'+fl+'" data-search="'+(rm+' '+wing+' '+cat+' '+beds+' '+hk+' '+guest).toLowerCase().replace(/"/g,'')+'"><td>'+(i+1)+'</td><td><strong>'+rm+'</strong><div class="tw-text-xs tw-text-muted">Floor '+fl+' · '+wing+'</div></td><td><strong>'+cat+'</strong><div class="tw-text-xs tw-text-muted">'+beds+'</div></td><td class="text-right"><strong>৳ '+rate+'</strong></td><td>'+guest+'</td><td><div class="mm-share-cell"><span>'+occ+'% MTD</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:'+occ+'%"></span></span></div></td><td><span class="mm-badge mm-badge-'+badge+'">'+hk+'</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px" data-toggle="modal" data-target="#room-drawer">Inspect</button></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Hotel Rooms &amp; Suite Inventory (20 Rooms Sample Data)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Complete 20-room directory across Floors 1–4 with live occupancy, housekeeping readiness, and assigned guests.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/room-settings" class="mm-button"><i class="fa fa-sliders"></i> Room Settings, Pricing &amp; Rules</a><a href="/preview/category-create" class="mm-button mm-button-secondary"><i class="fa fa-plus-circle"></i> + Room Category</a><a href="/preview/tape-chart" class="mm-button mm-button-secondary"><i class="fa fa-calendar"></i> 7-Day Tape Chart</a><a href="/preview/guests" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> 20 Guests</a><button type="button" class="mm-button mm-button-secondary" data-toggle="modal" data-target="#room-drawer"><i class="fa fa-plus"></i> Add / Inspect Room</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Rooms inventory summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Sample Rooms</span><div class="mm-table-kpi-value">20 Suites &amp; Rooms <span class="mm-table-kpi-sub">Floors 1–4</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Occupied In-House</span><div class="mm-table-kpi-value">14 Rooms <span class="mm-table-kpi-sub">70% Instant Occupancy</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-key"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Vacant &amp; Arriving</span><div class="mm-table-kpi-value">6 Rooms <span class="mm-table-kpi-sub">5 Ready · 1 Turnaround</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-money"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Average Daily Rate (ADR)</span><div class="mm-table-kpi-value">৳ 6,995 <span class="mm-table-kpi-sub">6 Room Categories</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar" style="margin-bottom:12px"><div class="input-group" style="max-width:100%;min-width:0;flex:1 1 200px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-rm-search" class="form-control" placeholder="Search 20 rooms by #number, category, wing, or guest..."></div><div class="mm-preview-filter-pills"><button type="button" class="mm-preview-pill is-active" data-rm-floor="all">All Rooms (20)</button><button type="button" class="mm-preview-pill" data-rm-floor="1">Floor 1 (6)</button><button type="button" class="mm-preview-pill" data-rm-floor="2">Floor 2 (6)</button><button type="button" class="mm-preview-pill" data-rm-floor="3">Floor 3 (5)</button><button type="button" class="mm-preview-pill" data-rm-floor="4">Floor 4 Penthouse (3)</button></div></div><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Room &amp; Wing</th><th>Category &amp; Capacity</th><th class="text-right">Tariff / Night</th><th>Current Guest / Assignment</th><th>30-Day Occupancy</th><th>Status</th><th class="text-center">Action</th></tr></thead><tbody>${rows}</tbody></table></div></div>
 <div id="room-drawer" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-bed"></i> Room Inventory &amp; Tariff Inspector</h4></div><div class="modal-body"><div class="form-group"><label>Room Number &amp; Floor</label><input type="text" class="form-control" value="#204 · 2nd Floor Balcony Wing"></div><div class="form-group"><label>Room Category</label><select class="form-control"><option selected>Royal Family Suite (৳ 8,500 / Night)</option><option>Presidential Suite (৳ 14,500 / Night)</option><option>Heritage Suite (৳ 6,500 / Night)</option><option>Deluxe King (৳ 4,500 / Night)</option></select></div><div class="form-group"><label>Housekeeping Status</label><select class="form-control"><option selected>Inspected &amp; Ready</option><option>Occupied Clean</option><option>Turnaround Cleaning</option></select></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Save Room</button></div></div></div></div></div></section>
 <script>(function(){var q=document.getElementById("mm-rm-search"),btns=document.querySelectorAll("[data-rm-floor]"),rows=document.querySelectorAll(".rm-dir-row"),fl="all";function run(){var s=(q&&q.value||"").toLowerCase().trim();for(var i=0;i<rows.length;i++){var r=rows[i],ok=(fl==="all"||r.getAttribute("data-floor")===fl)&&(!s||r.getAttribute("data-search").indexOf(s)>=0);r.style.display=ok?"":"none";}}if(q)q.addEventListener("input",run);for(var j=0;j<btns.length;j++){btns[j].addEventListener("click",function(){for(var k=0;k<btns.length;k++)btns[k].classList.remove("is-active");this.classList.add("is-active");fl=this.getAttribute("data-rm-floor");run();});}})();</script>`;
 return shellWith(body,'','','Hotel Rooms Directory (20 Rooms)',['Hotel','Rooms','Directory']);
}
function guestsDirectoryPage(){
 const guests=[
  ['#GST-1001','Tanvir Ahmed Chowdhury','Orion Pharma Ltd.','Corporate','+880 1711-948210','tanvir.chowdhury@orionpharma.com.bd','BD-8894021562','#101 · Deluxe Twin','8 Stays · ৳ 94,500',88,'paid'],
  ['#GST-1002','Nadia Islam','Acme Global','Corporate','+880 1713-220491','nadia.islam@acmeglobal.com','BD-7741029384','#102 · Deluxe King','6 Stays · ৳ 72,000',76,'paid'],
  ['#GST-1003','Dr. Farhana Rahman','Square Hospitals Ltd.','VIP','+880 1819-334812','farhana.rahman@squarehospital.com','A09481204','#103 · Deluxe King','14 Stays · ৳ 186,000',96,'paid'],
  ['#GST-1004','Rafiqul Islam Bhuiyan','Beximco Textiles','Corporate','+880 1911-458920','rafiq.bhuiyan@beximco.com','BD-5521904811','#104 · Executive Twin','4 Stays · ৳ 48,200',65,'partial'],
  ['#GST-1005','Tahmina Akter','BRAC Bank PLC','Corporate','+880 1611-883920','tahmina.akter@bracbank.com','BD-6630192845','#105 · Superior Double','9 Stays · ৳ 112,000',84,'paid'],
  ['#GST-1006','Syed Kamrul Hasan','Grameenphone Ltd.','Corporate','+880 1715-609281','kamrul.hasan@grameenphone.com','BD-4489201932','#106 · Standard Single','5 Stays · ৳ 42,500',62,'partial'],
  ['#GST-1007','Nusrat Jahan','UNICEF Bangladesh','Diplomatic','+880 1819-552104','nusrat.jahan@unicef.org','BG-0192837','#201 · Heritage Suite','11 Stays · ৳ 164,000',92,'paid'],
  ['#GST-1008','Engr. Tariqul Alam','Summit Power International','Corporate','+880 1712-990412','tariqul.alam@summitpower.com','BD-3391028475','#202 · Heritage Suite','7 Stays · ৳ 98,000',80,'partial'],
  ['#GST-1009','Shabnam Mustari','Biman Bangladesh Airlines','Corporate','+880 1714-772109','shabnam.mustari@biman.com','BP-4491023','#203 · Superior Twin','19 Stays · ৳ 148,000',90,'paid'],
  ['#GST-1010','Brig. Gen. Mahmudul Hasan','Defense Services Delegation','VIP','+880 1711-004921','mahmud.hasan@defense.gov.bd','DP-0049182','#204 · Royal Family Suite','12 Stays · ৳ 245,000',98,'paid'],
  ['#GST-1011','Ashraful Alam Siddiqui','Walton Hi-Tech Industries','Corporate','+880 1817-203948','ashraful@waltonbd.com','BD-9182736450','#205 · Deluxe Double','6 Stays · ৳ 68,400',74,'paid'],
  ['#GST-1012','Barrister Zaima Rahman','Supreme Court Bar Chambers','VIP','+880 1716-839201','zaima.rahman@chambers.bd','A10293847','#206 · Executive King','10 Stays · ৳ 152,000',89,'paid'],
  ['#GST-1013','Amb. Kenji Takahashi','Embassy of Japan in Dhaka','Diplomatic','+880 1711-889900','k.takahashi@embassy-jp.org','JP-8839201','#301 · Diplomatic Suite','15 Stays · ৳ 310,000',100,'paid'],
  ['#GST-1014','Capt. Jonathan Vance','Horizon Aviation UK','Diplomatic','+880 1713-774411','j.vance@horizon-aviation.uk','UK-5549201','#302 · Presidential Suite','9 Stays · ৳ 275,000',95,'paid'],
  ['#GST-1015','Meher Afroz Shaon','Berger Paints Bangladesh','Corporate','+880 1912-665432','meher.shaon@bergerbd.com','BD-2201938476','#303 · Executive Twin','5 Stays · ৳ 64,000',72,'paid'],
  ['#GST-1016','Salma Khatun','UNDP Country Mission','Diplomatic','+880 1718-392011','salma.khatun@undp.org','UN-7738291','#304 · Heritage Double','8 Stays · ৳ 136,000',86,'paid'],
  ['#GST-1017','Imran Hossain & Family','City Group Ltd.','VIP','+880 1811-902384','imran.hossain@citygroup.com.bd','BD-7788990011','#305 · Family Suite','4 Stays · ৳ 92,000',78,'paid'],
  ['#GST-1018','Dr. Ahsan Habib','icddr,b International Summit','VIP','+880 1711-332211','ahsan.habib@icddrb.org','A88392019','#401 · Royal Penthouse','7 Stays · ৳ 198,000',91,'partial'],
  ['#GST-1019','Elena Rostova','World Bank South Asia','Diplomatic','+880 1719-445566','e.rostova@worldbank.org','WB-6647382','#402 · Skyview Executive','11 Stays · ৳ 264,000',94,'paid'],
  ['#GST-1020','Rubana Huq','Mohammadi Group','VIP','+880 1711-223344','rubana.huq@mohammadi.group','A77482910','#403 · Grand Presidential','16 Stays · ৳ 385,000',100,'paid'],
 ];
 const rows=guests.map(([id,name,org,tier,phone,email,nid,rm,spend,score,badge],i)=>'<tr class="gst-dir-row" data-tier="'+tier+'" data-search="'+(id+' '+name+' '+org+' '+tier+' '+phone+' '+email+' '+nid+' '+rm).toLowerCase().replace(/"/g,'')+'"><td>'+(i+1)+'</td><td><code>'+id+'</code><div style="font-weight:700">'+name+'</div><div class="tw-text-xs tw-text-muted">'+org+'</div></td><td><span class="mm-badge mm-badge-'+badge+'">'+tier+'</span></td><td><div>'+phone+'</div><div class="tw-text-xs tw-text-muted">'+email+'</div></td><td><code>'+nid+'</code></td><td><strong>'+rm+'</strong></td><td><div class="mm-share-cell"><span>'+spend+'</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:'+score+'%"></span></span></div></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px" data-toggle="modal" data-target="#guest-drawer">Profile</button></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Registered Guests &amp; VIP CRM Directory (20 Guests Sample Data)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">20 verified guest profiles with NID/Passport KYC, corporate affiliation, assigned suites, and lifetime folio spend.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/rooms" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> 20 Rooms</a><a href="/preview/bookings" class="mm-button mm-button-secondary"><i class="fa fa-list-alt"></i> 20 Bookings</a><a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-map-o"></i> Booking Wizard</a><button type="button" class="mm-button" data-toggle="modal" data-target="#guest-drawer"><i class="fa fa-user-plus"></i> Add New Guest</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Guest directory summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-users"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Registered Sample Guests</span><div class="mm-table-kpi-value">20 Profiles <span class="mm-table-kpi-sub">100% KYC Verified</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-star"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">VIP &amp; Diplomatic</span><div class="mm-table-kpi-value">11 Guests <span class="mm-table-kpi-sub">6 VIP · 5 Diplomatic</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-briefcase"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Corporate Accounts</span><div class="mm-table-kpi-value">9 Partners <span class="mm-table-kpi-sub">Direct Billing Active</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-credit-card"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Cumulative Folio Value</span><div class="mm-table-kpi-value">৳ 3,254,600 <span class="mm-table-kpi-sub">Repeat Loyalty</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar" style="margin-bottom:12px"><div class="input-group" style="max-width:100%;min-width:0;flex:1 1 200px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-gst-search" class="form-control" placeholder="Search 20 guests by name, phone, passport/NID, or company..."></div><div class="mm-preview-filter-pills"><button type="button" class="mm-preview-pill is-active" data-gst-tier="all">All Guests (20)</button><button type="button" class="mm-preview-pill" data-gst-tier="VIP">VIP Platinum (6)</button><button type="button" class="mm-preview-pill" data-gst-tier="Diplomatic">Diplomatic &amp; UN (5)</button><button type="button" class="mm-preview-pill" data-gst-tier="Corporate">Corporate Partners (9)</button></div></div><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Guest &amp; Organization</th><th>Tier</th><th>Phone &amp; Email</th><th>NID / Passport</th><th>Assigned Suite</th><th>Lifetime Stays &amp; Spend</th><th class="text-center">Action</th></tr></thead><tbody>${rows}</tbody></table></div></div>
 <div id="guest-drawer" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-id-card-o"></i> Guest KYC &amp; Loyalty Profile Drawer</h4></div><div class="modal-body"><div class="form-group"><label>Guest Full Name</label><input type="text" class="form-control" value="Brig. Gen. Mahmudul Hasan"></div><div class="form-group"><label>Organization / Embassy</label><input type="text" class="form-control" value="Defense Services Delegation"></div><div class="form-group"><label>Mobile Phone &amp; Passport/NID</label><input type="text" class="form-control" value="+880 1711-004921 · Passport DP-0049182"></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button><button type="button" class="mm-button" data-dismiss="modal"><i class="fa fa-check"></i> Save Guest Profile</button></div></div></div></div></div></section>
 <script>(function(){var q=document.getElementById("mm-gst-search"),btns=document.querySelectorAll("[data-gst-tier]"),rows=document.querySelectorAll(".gst-dir-row"),tier="all";function run(){var s=(q&&q.value||"").toLowerCase().trim();for(var i=0;i<rows.length;i++){var r=rows[i],ok=(tier==="all"||r.getAttribute("data-tier")===tier)&&(!s||r.getAttribute("data-search").indexOf(s)>=0);r.style.display=ok?"":"none";}}if(q)q.addEventListener("input",run);for(var j=0;j<btns.length;j++){btns[j].addEventListener("click",function(){for(var k=0;k<btns.length;k++)btns[k].classList.remove("is-active");this.classList.add("is-active");tier=this.getAttribute("data-gst-tier");run();});}})();</script>`;
 return shellWith(body,'','','Hotel Guests & VIP CRM Directory (20 Guests)',['Hotel','Guests','Directory']);
}
function bookingsDirectoryPage(){
 const bks=[
  ['#BK-2026-101','Tanvir Ahmed Chowdhury','+880 1711-948210','#101 · Deluxe Twin','14 Oct → 17 Oct (3N)','10,800','10,800','0',100,'Checked-In','paid'],
  ['#BK-2026-102','Nadia Islam','+880 1713-220491','#102 · Deluxe King','14 Oct → 18 Oct (4N)','18,000','15,000','3,000',83,'Checked-In','paid'],
  ['#BK-2026-103','Dr. Farhana Rahman','+880 1819-334812','#103 · Deluxe King','14 Oct → 19 Oct (5N)','22,500','22,500','0',100,'VIP In-House','paid'],
  ['#BK-2026-104','Rafiqul Islam Bhuiyan','+880 1911-458920','#104 · Executive Twin','15 Oct → 19 Oct (4N)','15,200','8,000','7,200',53,'Confirmed Arrival','partial'],
  ['#BK-2026-105','Tahmina Akter','+880 1611-883920','#105 · Superior Double','14 Oct → 17 Oct (3N)','12,600','12,600','0',100,'Checked-In','paid'],
  ['#BK-2026-106','Syed Kamrul Hasan','+880 1715-609281','#106 · Standard Single','16 Oct → 19 Oct (3N)','8,400','5,000','3,400',60,'Confirmed Arrival','partial'],
  ['#BK-2026-107','Nusrat Jahan','+880 1819-552104','#201 · Heritage Suite','14 Oct → 18 Oct (4N)','26,000','26,000','0',100,'Checked-In','paid'],
  ['#BK-2026-108','Engr. Tariqul Alam','+880 1712-990412','#202 · Heritage Suite','15 Oct → 19 Oct (4N)','26,000','13,000','13,000',50,'Confirmed Arrival','partial'],
  ['#BK-2026-109','Shabnam Mustari','+880 1714-772109','#203 · Superior Twin','14 Oct → 16 Oct (2N)','7,800','7,800','0',100,'Checked-In','paid'],
  ['#BK-2026-110','Brig. Gen. Mahmudul Hasan','+880 1711-004921','#204 · Royal Family Suite','14 Oct → 18 Oct (4N)','34,000','34,000','0',100,'VIP In-House','paid'],
  ['#BK-2026-111','Ashraful Alam Siddiqui','+880 1817-203948','#205 · Deluxe Double','14 Oct → 17 Oct (3N)','13,200','10,000','3,200',76,'Checked-In','paid'],
  ['#BK-2026-112','Barrister Zaima Rahman','+880 1716-839201','#206 · Executive King','15 Oct → 19 Oct (4N)','20,800','15,000','5,800',72,'Confirmed Arrival','partial'],
  ['#BK-2026-113','Amb. Kenji Takahashi','+880 1711-889900','#301 · Diplomatic Suite','14 Oct → 19 Oct (5N)','47,500','47,500','0',100,'VIP In-House','paid'],
  ['#BK-2026-114','Capt. Jonathan Vance','+880 1713-774411','#302 · Presidential Suite','16 Oct → 21 Oct (5N)','72,500','72,500','0',100,'Checked-In','paid'],
  ['#BK-2026-115','Meher Afroz Shaon','+880 1912-665432','#303 · Executive Twin','14 Oct → 17 Oct (3N)','14,400','12,000','2,400',83,'Checked-In','paid'],
  ['#BK-2026-116','Salma Khatun','+880 1718-392011','#304 · Heritage Double','14 Oct → 16 Oct (2N)','14,400','14,400','0',100,'Departing Today','partial'],
  ['#BK-2026-117','Imran Hossain & Family','+880 1811-902384','#305 · Family Suite','14 Oct → 18 Oct (4N)','32,800','25,000','7,800',76,'Checked-In','paid'],
  ['#BK-2026-118','Dr. Ahsan Habib','+880 1711-332211','#401 · Royal Penthouse','15 Oct → 19 Oct (4N)','48,000','30,000','18,000',63,'Confirmed Arrival','partial'],
  ['#BK-2026-119','Elena Rostova','+880 1719-445566','#402 · Skyview Executive','14 Oct → 19 Oct (5N)','49,000','49,000','0',100,'VIP In-House','paid'],
  ['#BK-2026-120','Rubana Huq','+880 1711-223344','#403 · Grand Presidential','14 Oct → 20 Oct (6N)','99,000','99,000','0',100,'VIP In-House','paid'],
 ];
 const rows=bks.map(([ref,guest,phone,rm,dates,tot,paid,due,pct,st,badge],i)=>'<tr class="bk-dir-row" data-status="'+st+'" data-search="'+(ref+' '+guest+' '+phone+' '+rm+' '+dates+' '+st).toLowerCase().replace(/"/g,'')+'"><td>'+(i+1)+'</td><td><code>'+ref+'</code></td><td><strong>'+guest+'</strong><div class="tw-text-xs tw-text-muted">'+phone+'</div></td><td><strong>'+rm+'</strong><div class="tw-text-xs tw-text-muted">'+dates+'</div></td><td class="text-right"><strong>৳ '+tot+'</strong></td><td class="text-right">৳ '+paid+'</td><td class="text-right">'+(due==='0'?'<span class="mm-badge mm-badge-paid">Settled</span>':'<strong>৳ '+due+'</strong>')+'</td><td><div class="mm-share-cell"><span>'+pct+'% Paid</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:'+pct+'%"></span></span></div></td><td><span class="mm-badge mm-badge-'+badge+'">'+st+'</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 8px" data-toggle="modal" data-target="#booking-list-drawer">Folio</button></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Room Bookings &amp; Active Reservations (20 Bookings Sample Data)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Complete reservation ledger for all 20 sample rooms &amp; guests with live deposit tracking and Slide-Over Folio inspection.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/booking-next" class="mm-button"><i class="fa fa-plus"></i> New Booking Wizard</a><a href="/preview/tape-chart" class="mm-button mm-button-secondary"><i class="fa fa-calendar"></i> 7-Day Tape Chart</a><a href="/preview/rooms" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> 20 Rooms</a><a href="/preview/guests" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> 20 Guests</a></div></header>
 <div class="mm-table-kpi-grid" aria-label="Bookings summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-calendar-check-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Reservations</span><div class="mm-table-kpi-value">20 Bookings <span class="mm-table-kpi-sub">#BK-101 to #BK-120</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">In-House Checked-In</span><div class="mm-table-kpi-value">14 Stays <span class="mm-table-kpi-sub">5 VIP Suites</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-money"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Total Folio Value</span><div class="mm-table-kpi-value">৳ 592,900 <span class="mm-table-kpi-sub">৳ 529,100 Collected</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-clock-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Balance Receivable</span><div class="mm-table-kpi-value">৳ 63,800 <span class="mm-table-kpi-sub">89.2% Paid</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar" style="margin-bottom:12px"><div class="input-group" style="max-width:100%;min-width:0;flex:1 1 200px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-bk-search" class="form-control" placeholder="Search 20 bookings by #BK code, guest, room, or phone..."></div><div class="mm-preview-filter-pills"><button type="button" class="mm-preview-pill is-active" data-bk-st="all">All Bookings (20)</button><button type="button" class="mm-preview-pill" data-bk-st="Checked-In">Checked-In (9)</button><button type="button" class="mm-preview-pill" data-bk-st="VIP In-House">VIP In-House (5)</button><button type="button" class="mm-preview-pill" data-bk-st="Confirmed Arrival">Confirmed Arrival (5)</button></div></div><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>SL</th><th>Booking Ref</th><th>Guest &amp; Phone</th><th>Suite &amp; Stay Dates</th><th class="text-right">Total (৳)</th><th class="text-right">Paid (৳)</th><th class="text-right">Due (৳)</th><th>Settlement</th><th>Status</th><th class="text-center">Action</th></tr></thead><tbody>${rows}</tbody></table></div></div>
 <div id="booking-list-drawer" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title"><i class="fa fa-file-text-o"></i> Reservation &amp; Folio Inspector</h4></div><div class="modal-body"><div class="form-group"><label>Booking Reference</label><input type="text" class="form-control" value="#BK-2026-110 · Brig. Gen. Mahmudul Hasan" readonly></div><div class="form-group"><label>Assigned Room &amp; Stay</label><input type="text" class="form-control" value="Room #204 · Royal Family Suite (14 Oct → 18 Oct · 4 Nights)" readonly></div></div><div class="modal-footer"><button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button><a href="/preview/checkout" class="mm-button">Open Checkout Folio</a></div></div></div></div></div></section>
 <script>(function(){var q=document.getElementById("mm-bk-search"),btns=document.querySelectorAll("[data-bk-st]"),rows=document.querySelectorAll(".bk-dir-row"),st="all";function run(){var s=(q&&q.value||"").toLowerCase().trim();for(var i=0;i<rows.length;i++){var r=rows[i],ok=(st==="all"||r.getAttribute("data-status")===st)&&(!s||r.getAttribute("data-search").indexOf(s)>=0);r.style.display=ok?"":"none";}}if(q)q.addEventListener("input",run);for(var j=0;j<btns.length;j++){btns[j].addEventListener("click",function(){for(var k=0;k<btns.length;k++)btns[k].classList.remove("is-active");this.classList.add("is-active");st=this.getAttribute("data-bk-st");run();});}})();</script>`;
 return shellWith(body,'','','Room Bookings & Active Reservations (20 Bookings)',['Hotel','Bookings','List']);
}
function housekeepingBoardPage(){
 const hkRows=[
  ['#101','Deluxe Twin (1F)','Rahima Begum','Tanvir Ahmed Chowdhury (In-House)','100% Inspected',100,'is-emerald','Ready for Check-In','paid'],
  ['#102','Deluxe King (1F)','Rahima Begum','Nadia Islam (In-House)','100% Fresh Linen',100,'is-emerald','Occupied Clean','paid'],
  ['#103','Deluxe King (1F)','Mina Akter','Dr. Farhana Rahman (VIP)','100% VIP Turndown',100,'is-emerald','VIP Inspected','paid'],
  ['#104','Executive Twin (1F)','Mina Akter','Arriving: Rafiqul Islam Bhuiyan','100% Inspected',100,'is-emerald','Ready for Check-In','paid'],
  ['#105','Superior Double (1F)','Kamal Hossain','Tahmina Akter (In-House)','90% Restocking Minibar',90,'','Occupied Service','paid'],
  ['#106','Standard Single (1F)','Kamal Hossain','Arriving: Syed Kamrul Hasan','100% Sanitized',100,'is-emerald','Ready for Check-In','paid'],
  ['#201','Heritage Suite (2F)','Sohel Rana','Nusrat Jahan (UNICEF)','100% Inspected',100,'is-emerald','Occupied Clean','paid'],
  ['#202','Heritage Suite (2F)','Sohel Rana','Arriving: Engr. Tariqul Alam','65% Linen & Bath',65,'','In Progress','partial'],
  ['#203','Superior Twin (2F)','Jahanara Khatun','Shabnam Mustari (Biman Crew)','100% Inspected',100,'is-emerald','Occupied Clean','paid'],
  ['#204','Royal Family Suite (2F)','Jahanara Khatun','Brig. Gen. Mahmudul Hasan (VIP)','100% VIP Amenity Tray',100,'is-emerald','VIP Inspected','paid'],
  ['#205','Deluxe Double (2F)','abdul Karim','Ashraful Alam Siddiqui','85% Towel & Bath',85,'','In Progress','partial'],
  ['#206','Executive King (2F)','Abdul Karim','Arriving: Barrister Zaima Rahman','100% Inspected',100,'is-emerald','Ready for Check-In','paid'],
  ['#301','Diplomatic Suite (3F)','Nasreen Sultana','Amb. Kenji Takahashi (VIP)','100% Diplomatic Protocol',100,'is-emerald','VIP Inspected','paid'],
  ['#302','Presidential Suite (3F)','Nasreen Sultana','Capt. Jonathan Vance','100% Butler Checked',100,'is-emerald','Occupied Clean','paid'],
  ['#304','Heritage Double (3F)','Rashedul Islam','Salma Khatun (Departing)','45% Checkout Strip',45,'','Turnaround','unpaid'],
  ['#305','Family Suite (3F)','Rashedul Islam','Imran Hossain & Family','100% Extra Bedding Set',100,'is-emerald','Occupied Clean','paid'],
  ['#401','Royal Penthouse (4F)','lima Akter','Arriving: Dr. Ahsan Habib','100% Penthouse Staged',100,'is-emerald','Ready for Check-In','paid'],
  ['#403','Grand Presidential (4F)','Lima Akter','Rubana Huq (VIP Stay)','100% Executive Turndown',100,'is-emerald','VIP Inspected','paid'],
 ];
 const trs=hkRows.map(([rm,cat,staff,guest,lbl,pct,cls,st,badge])=>'<tr><td><strong>'+rm+'</strong></td><td>'+cat+'<div class="tw-text-xs tw-text-muted">'+guest+'</div></td><td>'+staff+'</td><td><div class="mm-share-cell"><span>'+lbl+'</span><span class="mm-share-bar"><span class="mm-share-fill '+cls+'" style="width:'+pct+'%"></span></span></div></td><td><span class="mm-badge mm-badge-'+badge+'">'+st+'</span></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Housekeeping &amp; Maintenance SLA Board (Plan B · F13/F14)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">18-Room live cleanliness checklist, attendant assignments, VIP turndown status, and engineering tickets.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/rooms" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> 20 Rooms</a><a href="/preview/concierge-requests" class="mm-button mm-button-secondary"><i class="fa fa-bell-o"></i> Concierge Desk</a><button type="button" class="mm-button" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-plus"></i> Assign Attendant</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Housekeeping SLA summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Inspected &amp; Ready</span><div class="mm-table-kpi-value">15 Rooms <span class="mm-table-kpi-sub">100% Checklist Passed</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-refresh"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Cleaning in Progress</span><div class="mm-table-kpi-value">3 Rooms <span class="mm-table-kpi-sub">Avg 18m Turnaround</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-users"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Attendants On Shift</span><div class="mm-table-kpi-value">12 Staff <span class="mm-table-kpi-sub">Floors 1–4</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-wrench"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Maintenance Tickets</span><div class="mm-table-kpi-value">1 Open <span class="mm-table-kpi-sub">HVAC Filter #202</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Room</th><th>Category &amp; Guest</th><th>Attendant</th><th>Checklist Progress</th><th>Status</th></tr></thead><tbody>${trs}</tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Housekeeping & Maintenance SLA Board (F13/F14)',['Hotel','Housekeeping','SLA Board']);
}
function conciergeRequestsPage(){
 const reqs=[
  ['#REQ-401','Room #204 · Brig. Gen. Mahmudul Hasan','Airport Limousine Pickup (DAC Terminal 1)','On Time (12m)',100,'is-emerald','Dispatched','paid'],
  ['#REQ-402','Room #302 · Capt. Jonathan Vance (Horizon Crew)','Extra Ironing Board & Universal Adapter Plug','In Transit (4m)',85,'','Assigned HK','partial'],
  ['#REQ-403','Room #301 · Amb. Kenji Takahashi','Japanese Green Tea & Diplomatic Press Kit','Delivered (6m)',100,'is-emerald','Completed','paid'],
  ['#REQ-404','Room #403 · Rubana Huq (Mohammadi Group)','Executive Boardroom Projector & Espresso Setup','Delivered (9m)',100,'is-emerald','Completed','paid'],
  ['#REQ-405','Room #101 · Tanvir Ahmed Chowdhury','Express Valet Laundry (3 Business Suits)','Pressing (18m)',75,'','In Laundry','partial'],
  ['#REQ-406','Room #102 · Nadia Islam','Late Check-Out Extension Request (Until 4:00 PM)','Approved',100,'is-emerald','Folio Updated','paid'],
  ['#REQ-407','Room #103 · Dr. Farhana Rahman','Hypoallergenic Feather Pillows (2 pcs)','Delivered (5m)',100,'is-emerald','Completed','paid'],
  ['#REQ-408','Room #201 · Nusrat Jahan (UNICEF)','Airport Drop-Off Chauffeur Reservation (07:30 AM)','Scheduled',100,'is-emerald','Confirmed','paid'],
  ['#REQ-409','Room #305 · Imran Hossain & Family','Baby Crib & Extra Rollaway Bed Setup','In Progress (8m)',80,'','Assigned HK','partial'],
  ['#REQ-410','Room #402 · Elena Rostova (World Bank)','High-Speed Dedicated VPN Router & Ethernet Cable','Installed (10m)',100,'is-emerald','Completed','paid'],
  ['#REQ-411','Room #203 · Shabnam Mustari','04:45 AM Flight Crew Wake-Up Call & Breakfast Box','Scheduled',100,'is-emerald','Confirmed','paid'],
  ['#REQ-412','Room #105 · Tahmina Akter','Lost & Found Inquiry — Apple MagSafe Charger (Lobby)','Logged & Found',100,'is-emerald','Returned to Guest','paid'],
  ['#REQ-413','Room #205 · Ashraful Alam Siddiqui','In-Room Dining Anniversary Cake & Floral Bouquet','Prepping (14m)',70,'','Kitchen Prep','partial'],
  ['#REQ-414','Room #303 · Meher Afroz Shaon','Chauffeur Full-Day Dhaka Heritage City Tour','Assigned Car #03',100,'is-emerald','Dispatched','paid'],
  ['#REQ-415','Room #304 · Salma Khatun (UNDP)','Bell Desk Luggage Down & Express Folio Print','Bellboy En Route',90,'','In Progress','partial'],
  ['#REQ-416','Room #401 · Dr. Ahsan Habib (Arriving)','VIP Welcome Fruit Basket & Orchid Staging','Staged in Suite',100,'is-emerald','Completed','paid'],
 ];
 const trs=reqs.map(([id,rm,type,sla,pct,cls,st,badge])=>'<tr><td><code>'+id+'</code></td><td><strong>'+rm+'</strong></td><td>'+type+'</td><td><div class="mm-share-cell"><span>'+sla+'</span><span class="mm-share-bar"><span class="mm-share-fill '+cls+'" style="width:'+pct+'%"></span></span></div></td><td><span class="mm-badge mm-badge-'+badge+'">'+st+'</span></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Concierge, Guest Requests &amp; Lost-and-Found (Plan B · F12)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">16 active guest requests across Floors 1–4 with airport pickups, amenities, wake-up calls, and lost-and-found SLA timers.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/guests" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> 20 Guests</a><a href="/preview/housekeeping-board" class="mm-button mm-button-secondary"><i class="fa fa-check-square-o"></i> Housekeeping Board</a><button type="button" class="mm-button" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-plus"></i> Log Guest Request</button></div></header>
 <div class="mm-table-kpi-grid" aria-label="Concierge summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bell-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Requests</span><div class="mm-table-kpi-value">16 Tickets <span class="mm-table-kpi-sub">16 In-House Guests</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Fulfilled On-Time</span><div class="mm-table-kpi-value">11 Completed <span class="mm-table-kpi-sub">Avg 8m Response</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-car"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Chauffeur &amp; Transfers</span><div class="mm-table-kpi-value">3 Flights <span class="mm-table-kpi-sub">DAC Terminal 1</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-search"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Lost &amp; Found</span><div class="mm-table-kpi-value">100% Returned <span class="mm-table-kpi-sub">Verified Handover</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Ticket</th><th>Room &amp; Guest</th><th>Request Type</th><th>SLA Progress</th><th>Status</th></tr></thead><tbody>${trs}</tbody></table></div></div></div></section>`;
 return shellWith(body,'','','Concierge & Guest Requests (F12)',['Hotel','Concierge','Guest Requests']);
}
function channelManagerPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">OTA Channel Manager &amp; iCal Sync (Plan B · F2)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Two-way inventory and rate synchronization across Booking.com, Agoda, Expedia, Airbnb, and Direct Web.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/plan-b" class="mm-button mm-button-secondary"><i class="fa fa-rocket"></i> Plan B Hub</a><a href="/preview/rate-plans" class="mm-button mm-button-secondary"><i class="fa fa-tags"></i> Rate Plans (F3)</a><button type="button" id="f2-btn-push-top" class="mm-button"><i class="fa fa-refresh"></i> Push ARI Inventory Now</button></div></header>
 <div id="f2-sync-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;"><i class="fa fa-check-circle"></i> <strong id="f2-sync-msg">Two-Way ARI Rate &amp; Availability Push completed across all 5 channels (20 Rooms · 0 Conflicts · iCal feeds refreshed)!</strong></div>
 <div class="mm-table-kpi-grid" aria-label="Channel manager summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-globe"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Connected Channels</span><div class="mm-table-kpi-value">5 Active <span class="mm-table-kpi-sub">Zero Overbookings</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bolt"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Last ARI Sync</span><div class="mm-table-kpi-value" id="f2-kpi-sync">18 secs ago <span class="mm-table-kpi-sub">Real-Time Webhook</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-calendar-check-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">OTA Bookings MTD</span><div class="mm-table-kpi-value" id="f2-kpi-count">142 Stays <span class="mm-table-kpi-sub">64% Direct + OTA</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Parity Status</span><div class="mm-table-kpi-value">100% Matched <span class="mm-table-kpi-sub">BAR Rate Synced</span></div></div></div></div>
 <div class="mm-panel tw-p-5"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;align-items:end;margin-bottom:14px"><div><label class="mm-label">Target OTA Channel</label><select id="f2-channel-sel" class="form-control"><option value="All 5 Connected Channels">All 5 Connected Channels</option><option value="Booking.com Extranet">Booking.com Extranet</option><option value="Agoda YCS &amp; Expedia">Agoda YCS &amp; Expedia</option><option value="Airbnb Host iCal">Airbnb Host iCal</option></select></div><div><label class="mm-label">Room Category Scope</label><select id="f2-cat-sel" class="form-control"><option value="All 6 Suite Categories (20 Rooms)">All 6 Suite Categories (20 Rooms)</option><option value="Deluxe King &amp; Executive Suites">Deluxe King &amp; Executive Suites</option><option value="Diplomatic &amp; Presidential Suites">Diplomatic &amp; Presidential Suites</option></select></div><div><label class="mm-label">iCal Export Feed URL</label><input type="text" class="form-control" value="https://mmheritagehotel.com/ical/rooms-all.ics" readonly></div><div><button type="button" id="f2-btn-sync" class="mm-button" style="width:100%"><i class="fa fa-bolt"></i> Sync ARI &amp; Import Bookings</button></div></div>
 <div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Channel / OTA Partner</th><th>Protocol</th><th>Booking Share</th><th>Last Sync</th><th>Status</th></tr></thead><tbody id="f2-tbody">
 <tr><td><strong>Direct Hotel Website (mm-web Storefront)</strong></td><td><code>Native DB + Cart</code></td><td><div class="mm-share-cell"><span>42% Share</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:42%"></span></span></div></td><td class="f2-sync-cell">Instant</td><td><span class="mm-badge mm-badge-paid">Commission-Free</span></td></tr>
 <tr><td><strong>Booking.com Extranet</strong></td><td><code>OTA XML / iCal</code></td><td><div class="mm-share-cell"><span>34% Share</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:34%"></span></span></div></td><td class="f2-sync-cell">18s ago</td><td><span class="mm-badge mm-badge-paid">Synced</span></td></tr>
 <tr><td><strong>Agoda YCS &amp; Expedia Partner Central</strong></td><td><code>ARI REST + iCal</code></td><td><div class="mm-share-cell"><span>24% Share</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:24%"></span></span></div></td><td class="f2-sync-cell">24s ago</td><td><span class="mm-badge mm-badge-paid">Synced</span></td></tr>
 </tbody></table></div></div></div></section>
 <script>(function(){var b1=document.getElementById("f2-btn-sync"),b2=document.getElementById("f2-btn-push-top"),ban=document.getElementById("f2-sync-banner"),msg=document.getElementById("f2-sync-msg"),kSync=document.getElementById("f2-kpi-sync");function run(){var ch=document.getElementById("f2-channel-sel").value,cat=document.getElementById("f2-cat-sel").value;var cells=document.querySelectorAll(".f2-sync-cell");for(var i=0;i<cells.length;i++)cells[i].textContent="Just now (0s ago)";if(kSync)kSync.innerHTML='Just now <span class="mm-table-kpi-sub">ARI Push Verified</span>';if(ban&&msg){msg.textContent="Synced "+cat+" to "+ch+" — 0 overbooking conflicts!";ban.style.display="block";}}if(b1)b1.addEventListener("click",run);if(b2)b2.addEventListener("click",run);})();</script>`;
 return shellWith(body,'','','OTA Channel Manager & iCal Sync (F2)',['Hotel','Distribution','Channel Manager']);
}
function ratePlansPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Seasonal Rate Plans &amp; Promo Engine (Plan B · F3)</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Manage BAR, Corporate, Diplomatic, and Weekend Promo rate plans with min-stay, CTA/CTD restrictions, and promo codes.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/plan-b" class="mm-button mm-button-secondary"><i class="fa fa-rocket"></i> Plan B Hub</a><a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-calendar-plus-o"></i> Booking Wizard (#3)</a><a href="/preview/channel-manager" class="mm-button mm-button-secondary"><i class="fa fa-globe"></i> Channel Manager</a><button type="button" class="mm-button" id="f3-btn-add-top"><i class="fa fa-plus"></i> Publish Rate Plan</button></div></header>
 <div id="f3-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;"><i class="fa fa-check-circle"></i> <strong id="f3-banner-msg">New Seasonal Rate Plan &amp; Promo Code published and synced to the 3-Step Booking Wizard &amp; OTA Channel Manager!</strong></div>
 <div class="mm-table-kpi-grid" aria-label="Rate plans summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-tags"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Rate Plans</span><div class="mm-table-kpi-value" id="f3-kpi-plans">6 Plans <span class="mm-table-kpi-sub">BAR · Corp · Diplomatic</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-calendar"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Current Season</span><div class="mm-table-kpi-value">Autumn Peak <span class="mm-table-kpi-sub">Oct–Dec 2026</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-ticket"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Promo Codes Live</span><div class="mm-table-kpi-value">4 Campaigns <span class="mm-table-kpi-sub">HERITAGE20 · WKND3N</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-line-chart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Realized BAR Index</span><div class="mm-table-kpi-value">104.2% <span class="mm-table-kpi-sub">+4.2% vs Base</span></div></div></div></div>
 <div class="mm-panel tw-p-5" id="f3-controls-panel"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:10px;align-items:end;margin-bottom:14px"><div><label class="mm-label">Plan / Promo Code</label><input type="text" id="f3-code" class="form-control" value="WINTER-VIP25"></div><div><label class="mm-label">Rate Plan Title</label><input type="text" id="f3-name" class="form-control" value="Winter Diplomatic &amp; Long-Stay Offer"></div><div><label class="mm-label">Meal &amp; Inclusion Rule</label><select id="f3-meal" class="form-control"><option value="Breakfast + Airport Limousine">Breakfast + Airport Limousine</option><option value="Half-Board (Breakfast + Dinner)">Half-Board (Breakfast + Dinner)</option><option value="All-Inclusive Club Lounge">All-Inclusive Club Lounge</option></select></div><div><label class="mm-label">Min-Stay &amp; Tariff Index</label><select id="f3-idx" class="form-control"><option value="2 Nights|75">2 Nights · 75% (-25%)</option><option value="3 Nights|80">3 Nights · 80% (-20%)</option><option value="5 Nights|70">5 Nights · 70% (-30%)</option></select></div><div><button type="button" id="f3-btn-create" class="mm-button" style="width:100%"><i class="fa fa-plus-circle"></i> + Add Rate Plan</button></div></div>
 <div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Code</th><th>Rate Plan Name</th><th>Meal Plan</th><th>Min Stay</th><th>Tariff Index</th><th>Status</th></tr></thead><tbody id="f3-tbody">
 <tr><td><code>BAR-2026</code></td><td><strong>Best Available Flexible Rate (BAR)</strong></td><td>Complimentary Buffet Breakfast</td><td>1 Night</td><td><div class="mm-share-cell"><span>100% Base</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Active</span></td></tr>
 <tr><td><code>CORP-VIP</code></td><td><strong>Corporate &amp; Diplomatic Contract Rate</strong></td><td>Breakfast + Airport Transfer</td><td>1 Night</td><td><div class="mm-share-cell"><span>85% (-15%)</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:85%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Active</span></td></tr>
 <tr><td><code>WKND-3N</code></td><td><strong>Autumn Heritage Escape (3-Night Promo)</strong></td><td>Half-Board (Breakfast + Dinner)</td><td>3 Nights</td><td><div class="mm-share-cell"><span>80% (-20%)</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:80%"></span></span></div></td><td><span class="mm-badge mm-badge-partial">Seasonal</span></td></tr>
 </tbody></table></div></div></div></section>
 <script>(function(){var b1=document.getElementById("f3-btn-create"),b2=document.getElementById("f3-btn-add-top"),tb=document.getElementById("f3-tbody"),ban=document.getElementById("f3-banner"),msg=document.getElementById("f3-banner-msg");function addPlan(){var c=(document.getElementById("f3-code").value||"PROMO-26").replace(/[<>&"]/g,""),nm=(document.getElementById("f3-name").value||"Seasonal Rate Plan").replace(/[<>&"]/g,""),ml=document.getElementById("f3-meal").value,ix=(document.getElementById("f3-idx").value||"2 Nights|75").split("|");var tr=document.createElement("tr");tr.innerHTML="<td><code>"+c+"</code></td><td><strong>"+nm+"</strong></td><td>"+ml+"</td><td>"+ix[0]+'</td><td><div class="mm-share-cell"><span>'+ix[1]+'% Index</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:'+ix[1]+'%"></span></span></div></td><td><span class="mm-badge mm-badge-paid">Active</span></td>';if(tb)tb.insertBefore(tr,tb.firstChild);if(ban&&msg){msg.textContent="Published Rate Plan "+c+" ("+nm+") and synced to 3-Step Booking Wizard!";ban.style.display="block";}}if(b1)b1.addEventListener("click",addPlan);if(b2)b2.addEventListener("click",addPlan);})();</script>`;
 return shellWith(body,'','','Seasonal Rate Plans & Promo Engine (F3)',['Hotel','Distribution','Rate Plans']);
}
function draftBookingPdfPage(){
 const html=fix('preview/booking-next.html');
 const sheetStart=html.indexOf('<div class="mm-wiz-draft-sheet" id="wiz-draft-sheet">');
 const sheetEnd=html.indexOf('</div>\n                    </div>\n                    <div class="modal-footer">',sheetStart);
 const sheet=sheetStart>=0&&sheetEnd>sheetStart?html.slice(sheetStart,sheetEnd)+'</div>':'<div class="mm-panel tw-p-5">Draft Booking Summary PDF</div>';
 const body=`<section class="mm-ui mm-booking-next"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-file-pdf-o"></i> Whole Booking Preview — Draft Reservation Summary PDF (Standalone A4 View)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">2-Page Executive A4 Pre-Confirmation Reservation Summary for guest review, signature, and instant PDF print or download.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-arrow-left"></i> Back to 3-Step Booking Wizard</a>
   <a href="/assets/docs/draft-reservation-summary.pdf?download=1" download="MM-Heritage-Draft-Booking-Summary.pdf" id="standalone-btn-download-pdf" onclick="var t=document.getElementById('standalone-dl-toast');if(t)t.style.display='block';" class="mm-button mm-button-secondary"><i class="fa fa-download"></i> Download Draft PDF</a>
   <button type="button" id="standalone-btn-toggle-pdf-embed" class="mm-button mm-button-secondary" onclick="var el=document.getElementById('standalone-pdf-embed-wrap');if(el){el.style.display=el.style.display==='none'?'block':'none';}"><i class="fa fa-eye"></i> Embedded Vector PDF Viewer</button>
   <button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print 2-Page A4 PDF</button>
  </div>
 </header>
 <div id="standalone-dl-toast" class="mm-wiz-adv-alert is-ok mm-no-print" style="display:none; max-width:980px; margin:0 auto 10px; background:#ecfdf5; border:1px solid #34d399; color:#065f46; border-radius:10px; padding:10px 14px;">
  <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2">
   <span><i class="fa fa-check-circle"></i> <strong>MM-Heritage-Draft-Booking-Summary.pdf (2 Pages · A4 Vector)</strong> downloaded.</span>
   <button type="button" class="mm-button mm-button-secondary" onclick="var el=document.getElementById('standalone-pdf-embed-wrap');if(el)el.style.display='block';" style="min-height:26px;padding:2px 10px;font-size:11px;"><i class="fa fa-eye"></i> Open Embedded Vector PDF Viewer</button>
  </div>
 </div>
 <div class="mm-table-kpi-grid mm-no-print" aria-label="Draft booking PDF metrics">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-file-pdf-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Document Format</span><div class="mm-table-kpi-value">2-Page A4 PDF <span class="mm-table-kpi-sub">210 × 297 mm Vector</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Assigned Suites</span><div class="mm-table-kpi-value">2 Suites · 2 Nights <span class="mm-table-kpi-sub">#101 &amp; #201</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-users"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Registered Guests</span><div class="mm-table-kpi-value">3 Occupants <span class="mm-table-kpi-sub">KYC Verified</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Net Stay / Advance</span><div class="mm-table-kpi-value">৳ 25,000 / ৳ 10,000 <span class="mm-table-kpi-sub">Due: ৳ 15,000</span></div></div></div>
 </div>
 <div class="mm-wiz-pdf-toolbar mm-no-print" id="wiz-pdf-toolbar" style="max-width:980px; margin:0 auto 14px; border-radius:10px; border:1px solid rgba(148,163,184,0.36); box-shadow:0 4px 12px rgba(15,23,42,0.06);">
  <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
   <span class="mm-badge mm-badge-paid"><i class="fa fa-file-pdf-o"></i> Standalone 2-Page A4 Executive Summary (210 × 297 mm)</span>
   <span class="tw-text-xs tw-text-muted">Page 1: Stay, Guest KYC &amp; Suites · Page 2: Financial Folio, Policy &amp; Signatures</span>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <button type="button" class="mm-button" id="wiz-btn-print-draft-top" onclick="window.print()" style="min-height:30px; padding:3px 12px; font-size:11.5px;">
    <i class="fa fa-print"></i> Print 2-Page Draft PDF
   </button>
   <a href="/assets/docs/draft-reservation-summary.pdf?download=1" download="MM-Heritage-Draft-Booking-Summary.pdf" onclick="var t=document.getElementById('standalone-dl-toast');if(t)t.style.display='block';" class="mm-button mm-button-secondary" id="wiz-btn-download-draft-pdf" style="min-height:30px; padding:3px 11px; font-size:11.5px;">
    <i class="fa fa-download"></i> Download Draft PDF
   </a>
   <button type="button" class="mm-button mm-button-secondary" onclick="var el=document.getElementById('standalone-pdf-embed-wrap');if(el){el.style.display=el.style.display==='none'?'block':'none';}" style="min-height:30px; padding:3px 11px; font-size:11.5px;">
    <i class="fa fa-file-pdf-o"></i> View Embedded Vector PDF
   </button>
  </div>
 </div>
 <div id="standalone-pdf-embed-wrap" class="mm-panel tw-p-4 mm-no-print" style="display:none; max-width:980px; margin:0 auto 16px;">
  <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:10px;">
   <strong><i class="fa fa-file-pdf-o"></i> Embedded 2-Page Vector PDF Document (<code>/assets/docs/draft-reservation-summary.pdf</code>)</strong>
   <a href="/assets/docs/draft-reservation-summary.pdf?download=1" download="MM-Heritage-Draft-Booking-Summary.pdf" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:11.5px;"><i class="fa fa-download"></i> Save Vector PDF</a>
  </div>
  <iframe src="/assets/docs/draft-reservation-summary.pdf" title="Draft Reservation Summary Vector PDF" style="width:100%; height:640px; border:1px solid #cbd5e1; border-radius:8px; background:#fff;"></iframe>
 </div>
 <div style="max-width:980px;margin:0 auto;">${sheet}</div>
 </div></section>`;
 return shellWith(body,'','','Whole Booking Preview — Draft Reservation Summary PDF',['Hotel','Booking','Draft Summary PDF']);
}
function kioskCheckinPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-tablet"></i> Self-Service Kiosk Check-In &amp; Digital Registration Card (Plan B · F10)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Express passport/NID OCR verification, digital guest registration card, e-signature pad, and Mifare RFID keycard encoder.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-calendar-plus-o"></i> 3-Step Booking Wizard</a>
   <a href="/preview/draft-booking-pdf" class="mm-button mm-button-secondary"><i class="fa fa-file-pdf-o"></i> Draft Summary PDF</a>
   <button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print Registration Card</button>
  </div>
 </header>
 <div class="mm-table-kpi-grid mm-no-print" aria-label="Kiosk check-in summary">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-tablet"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Express Kiosk Queue</span><div class="mm-table-kpi-value">4 Arrivals Ready <span class="mm-table-kpi-sub">Avg 45s Check-In</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-id-card-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Passport / NID OCR</span><div class="mm-table-kpi-value">99.4% Accuracy <span class="mm-table-kpi-sub">MRZ + Smart NID</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-pencil-square-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Digital Reg. Cards</span><div class="mm-table-kpi-value">18 Signed Today <span class="mm-table-kpi-sub">Paperless Archive</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-key"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">RFID Key Encoder</span><div class="mm-table-kpi-value">Online · Terminal #1 <span class="mm-table-kpi-sub">Mifare 13.56 MHz</span></div></div></div>
 </div>
 <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:16px">
  <div class="mm-panel tw-p-5">
   <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <strong><i class="fa fa-id-card-o"></i> Step 1 · Reservation Lookup &amp; Passport / NID OCR Scan</strong>
    <span class="mm-badge mm-badge-paid" id="kiosk-ocr-badge">OCR Scanner Ready</span>
   </div>
   <div class="tw-space-y-3">
    <div>
     <label class="mm-label" for="kiosk-booking-select">Select Arriving Reservation</label>
     <select id="kiosk-booking-select" class="form-control">
      <option value="Tanvir Ahmed Chowdhury|BD-8894021562|+880 1711-948210|Suites #101 &amp; #201 (2 Nights)|Bangladeshi" selected>#BK-2026-1094 — Tanvir Ahmed Chowdhury (Suites #101 &amp; #201)</option>
      <option value="Brig. Gen. Mahmudul Hasan|BD-1904882104|+880 1713-220194|Royal Family Suite #204 (3 Nights)|Bangladeshi">#BK-2026-1088 — Brig. Gen. Mahmudul Hasan (Suite #204)</option>
      <option value="Amb. Kenji Takahashi|JP-TR9041822|+880 1714-882010|Diplomatic Suite #301 (4 Nights)|Japanese">#BK-2026-1091 — Amb. Kenji Takahashi (Suite #301)</option>
      <option value="Rubana Huq|BD-4401928371|+880 1711-500200|Grand Presidential #403 (2 Nights)|Bangladeshi">#BK-2026-1095 — Rubana Huq (Suite #403)</option>
     </select>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
     <div><label class="mm-label">Verified Guest Name</label><input type="text" id="kiosk-guest-name" class="form-control" value="Tanvir Ahmed Chowdhury"></div>
     <div><label class="mm-label">Passport / Smart NID (MRZ)</label><input type="text" id="kiosk-guest-nid" class="form-control" value="BD-8894021562"></div>
     <div><label class="mm-label">Mobile Phone</label><input type="text" id="kiosk-guest-phone" class="form-control" value="+880 1711-948210"></div>
     <div><label class="mm-label">Assigned Suite(s)</label><input type="text" id="kiosk-guest-rooms" class="form-control" value="Suites #101 &amp; #201 (2 Nights)" readonly></div>
    </div>
    <div id="kiosk-ocr-banner" class="alert alert-success" style="display:none;margin:0;font-size:12px;">
     <i class="fa fa-check-circle"></i> <strong>Govt. Smart NID / Passport MRZ Verified!</strong> Optical scan matched reservation KYC 100%.
    </div>
    <div class="tw-flex tw-flex-wrap tw-gap-2">
     <button type="button" id="kiosk-btn-scan-id" class="mm-button mm-button-secondary"><i class="fa fa-camera"></i> Scan Passport / NID via OCR</button>
     <button type="button" class="mm-button mm-button-secondary" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-user-plus"></i> Add Co-Guest ID</button>
    </div>
   </div>
  </div>
  <div class="mm-panel tw-p-5">
   <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
    <strong><i class="fa fa-pencil"></i> Step 2 · Digital E-Signature &amp; RFID Keycard Dispenser</strong>
    <span class="mm-badge mm-badge-partial" id="kiosk-sig-status">Awaiting Signature</span>
   </div>
   <p class="tw-text-xs tw-text-muted" style="margin:0 0 8px">Sign inside the touch pad below or click <strong>Apply Verified E-Signature</strong> to authorize check-in and encode RFID room keycards.</p>
   <div style="border:1.5px dashed #94a3b8;border-radius:10px;background:#f8fafc;padding:10px;text-align:center;margin-bottom:10px">
    <canvas id="kiosk-signature-canvas" width="440" height="110" style="width:100%;max-width:440px;height:110px;background:#ffffff;border:1px solid #cbd5e1;border-radius:8px;cursor:crosshair"></canvas>
    <div style="font-size:11px;color:#475569;margin-top:4px" id="kiosk-sig-caption">Signed by: <strong>Tanvir Ahmed Chowdhury</strong> · Agree to Hotel Registration Terms &amp; Folio Policy</div>
   </div>
   <div id="kiosk-keycard-banner" class="alert alert-success" style="display:none;margin:0 0 10px;font-size:12px;">
    <i class="fa fa-key"></i> <strong>2 Mifare RFID Keycards Encoded &amp; Dispensed!</strong> Rooms <code>#101</code> &amp; <code>#201</code> active until 16 Oct 2026 12:00 PM.
   </div>
   <div class="tw-flex tw-flex-wrap tw-gap-2">
    <button type="button" id="kiosk-btn-auto-sign" class="mm-button mm-button-secondary"><i class="fa fa-pencil"></i> Apply Verified E-Signature</button>
    <button type="button" id="kiosk-btn-clear-sig" class="mm-button mm-button-secondary"><i class="fa fa-eraser"></i> Clear Pad</button>
    <button type="button" id="kiosk-btn-encode-keys" class="mm-button"><i class="fa fa-key"></i> Complete Check-In &amp; Encode RFID Keys</button>
   </div>
  </div>
 </div>
 </div></section>
 <script>(function(){
  var sel=document.getElementById("kiosk-booking-select");
  if(sel)sel.addEventListener("change",function(){
   var p=(sel.value||"").split("|");
   if(p.length>=4){
    document.getElementById("kiosk-guest-name").value=p[0];
    document.getElementById("kiosk-guest-nid").value=p[1];
    document.getElementById("kiosk-guest-phone").value=p[2];
    document.getElementById("kiosk-guest-rooms").value=p[3];
    var cap=document.getElementById("kiosk-sig-caption");
    if(cap)cap.innerHTML="Signed by: <strong>"+p[0]+"</strong> · Agree to Hotel Registration Terms & Folio Policy";
   }
  });
  var scanBtn=document.getElementById("kiosk-btn-scan-id");
  if(scanBtn)scanBtn.addEventListener("click",function(){
   var b=document.getElementById("kiosk-ocr-banner"),bd=document.getElementById("kiosk-ocr-badge");
   if(b)b.style.display="block";
   if(bd){bd.className="mm-badge mm-badge-paid";bd.textContent="MRZ & NID Verified";}
  });
  var cvs=document.getElementById("kiosk-signature-canvas"),ctx=cvs?cvs.getContext("2d"):null;
  function drawSampleSig(name){
   if(!ctx)return;
   ctx.clearRect(0,0,cvs.width,cvs.height);
   ctx.strokeStyle="#1e3a8a";ctx.lineWidth=2.4;
   ctx.beginPath();ctx.moveTo(45,74);ctx.bezierCurveTo(95,28,155,88,215,44);ctx.bezierCurveTo(265,18,320,76,390,48);ctx.stroke();
   ctx.fillStyle="#0f172a";ctx.font="italic bold 18px Georgia, serif";
   ctx.fillText(name||"Tanvir A. Chowdhury",62,64);
   var st=document.getElementById("kiosk-sig-status");
   if(st){st.className="mm-badge mm-badge-paid";st.textContent="E-Signature Verified";}
  }
  drawSampleSig("Tanvir A. Chowdhury");
  var autoBtn=document.getElementById("kiosk-btn-auto-sign");
  if(autoBtn)autoBtn.addEventListener("click",function(){
   drawSampleSig((document.getElementById("kiosk-guest-name")||{}).value||"Verified Guest");
  });
  var clrBtn=document.getElementById("kiosk-btn-clear-sig");
  if(clrBtn)clrBtn.addEventListener("click",function(){
   if(ctx)ctx.clearRect(0,0,cvs.width,cvs.height);
   var st=document.getElementById("kiosk-sig-status");
   if(st){st.className="mm-badge mm-badge-partial";st.textContent="Cleared — Sign Again";}
  });
  var encBtn=document.getElementById("kiosk-btn-encode-keys");
  if(encBtn)encBtn.addEventListener("click",function(){
   drawSampleSig((document.getElementById("kiosk-guest-name")||{}).value||"Verified Guest");
   var kb=document.getElementById("kiosk-keycard-banner");
   if(kb)kb.style.display="block";
  });
 })();</script>`;
 return shellWith(body,'','','Self-Service Kiosk Check-In & Digital Registration Card (F10)',['Hotel','Front Desk','Kiosk Check-In']);
}
function roomMovePage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-exchange"></i> In-Stay Room Move, Upgrade &amp; Folio Transfer (Plan B · F9)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Move an in-house guest from Room A to Room B with automatic tariff differential calculation, folio transfer, and housekeeping turnaround trigger.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/tape-chart" class="mm-button mm-button-secondary"><i class="fa fa-calendar"></i> 7-Day Tape Chart</a>
   <a href="/preview/housekeeping-board" class="mm-button mm-button-secondary"><i class="fa fa-check-square-o"></i> Housekeeping SLA Board</a>
   <a href="/preview/checkout" class="mm-button mm-button-secondary"><i class="fa fa-credit-card"></i> Checkout Folio</a>
  </div>
 </header>
 <div class="mm-table-kpi-grid" aria-label="Room move summary">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active In-House Stays</span><div class="mm-table-kpi-value">18 Occupied <span class="mm-table-kpi-sub">Eligible for Move</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Inspected Target Suites</span><div class="mm-table-kpi-value">6 Ready Now <span class="mm-table-kpi-sub">100% HK Passed</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-exchange"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Room Moves Today</span><div class="mm-table-kpi-value" id="rmove-kpi-count">2 Completed <span class="mm-table-kpi-sub">Folio Synced</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Rate-Change Audit</span><div class="mm-table-kpi-value">100% Logged <span class="mm-table-kpi-sub">Zero Folio Variance</span></div></div></div>
 </div>
 <div class="mm-panel tw-p-5">
  <h2 class="tw-m-0 tw-mb-3 tw-text-base tw-font-semibold"><i class="fa fa-random"></i> Execute In-Stay Suite Transfer &amp; Folio Merge</h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:14px">
   <div>
    <label class="mm-label" for="rmove-source-select">Current Occupied Room (From)</label>
    <select id="rmove-source-select" class="form-control">
     <option value="#101|Tanvir Ahmed Chowdhury|4200|#BK-2026-1094" selected>#101 — Deluxe Double (Tanvir A. Chowdhury · ৳ 4,200/nt)</option>
     <option value="#105|Tahmina Akter|3800|#BK-2026-1082">#105 — Superior Double (Tahmina Akter · ৳ 3,800/nt)</option>
     <option value="#203|Shabnam Mustari|4500|#BK-2026-1086">#203 — Superior Twin (Shabnam Mustari · ৳ 4,500/nt)</option>
    </select>
   </div>
   <div>
    <label class="mm-label" for="rmove-target-select">Target Inspected Suite (To)</label>
    <select id="rmove-target-select" class="form-control">
     <option value="#206|Executive King (2F)|5500" selected>#206 — Executive King (2F · ৳ 5,500/nt · Ready)</option>
     <option value="#301|Diplomatic Suite (3F)|8500">#301 — Diplomatic Suite (3F · ৳ 8,500/nt · VIP Ready)</option>
     <option value="#401|Royal Penthouse (4F)|12000">#401 — Royal Penthouse (4F · ৳ 12,000/nt · Staged)</option>
    </select>
   </div>
   <div>
    <label class="mm-label" for="rmove-rate-policy">Tariff Differential Policy</label>
    <select id="rmove-rate-policy" class="form-control">
     <option value="upgrade_paid" selected>Charge Upgrade Difference (+৳ 1,300 / night)</option>
     <option value="comp_vip">Complimentary VIP Upgrade (Keep Original Tariff)</option>
     <option value="half_diff">50% Courtesy Corporate Upgrade (+৳ 650 / night)</option>
    </select>
   </div>
   <div>
    <label class="mm-label" for="rmove-reason">Move Reason &amp; Audit Note</label>
    <input type="text" id="rmove-reason" class="form-control" value="Guest requested higher-floor Executive King with quiet courtyard view">
   </div>
  </div>
  <div id="rmove-success-banner" class="alert alert-success" style="display:none;margin-bottom:12px;font-size:12.5px;">
   <i class="fa fa-check-circle"></i> <span id="rmove-success-text">Suite Move Completed: Folio transferred from #101 to #206, RFID keycard re-encoded, and Room #101 queued for Housekeeping Turnaround.</span>
  </div>
  <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2">
   <span class="tw-text-xs tw-text-muted" id="rmove-preview-line">Folio Impact Preview: <strong>#101 → #206</strong> · Nightly Rate <strong>৳ 4,200 → ৳ 5,500 (+৳ 1,300/nt)</strong> · Vacated room #101 auto-marked <em>Dirty / Turnaround</em>.</span>
   <button type="button" id="rmove-btn-execute" class="mm-button"><i class="fa fa-exchange"></i> Confirm Room Move &amp; Transfer Folio</button>
  </div>
 </div>
 <div class="mm-panel tw-p-5">
  <h2 class="tw-m-0 tw-mb-3 tw-text-base tw-font-semibold"><i class="fa fa-history"></i> Room Move &amp; Rate-Change Audit Log</h2>
  <div class="mm-table-scroll">
   <table class="mm-data-table table table-bordered table-striped" id="rmove-audit-table">
    <thead><tr><th>Timestamp</th><th>Booking Ref</th><th>Guest Name</th><th>From → To Suite</th><th>Tariff Adjustment</th><th>Reason &amp; HK Action</th><th>Status</th></tr></thead>
    <tbody id="rmove-audit-tbody">
     <tr><td>2026-10-14 11:20</td><td><code>#BK-2026-1079</code></td><td><strong>Dr. Farhana Rahman</strong></td><td><code>#102</code> → <code>#103</code></td><td>Complimentary VIP (৳ 4,800/nt)</td><td>Upgraded to corner Deluxe King · #102 Inspected</td><td><span class="mm-badge mm-badge-paid">Folio Merged</span></td></tr>
     <tr><td>2026-10-14 09:45</td><td><code>#BK-2026-1084</code></td><td><strong>Imran Hossain &amp; Family</strong></td><td><code>#205</code> → <code>#305</code></td><td>+৳ 2,200/nt (Family Suite)</td><td>Added extra rollaway bed · #205 Cleaned</td><td><span class="mm-badge mm-badge-paid">Folio Merged</span></td></tr>
    </tbody>
   </table>
  </div>
 </div>
 </div></section>
 <script>(function(){
  var src=document.getElementById("rmove-source-select"),tgt=document.getElementById("rmove-target-select"),pol=document.getElementById("rmove-rate-policy"),btn=document.getElementById("rmove-btn-execute");
  if(btn)btn.addEventListener("click",function(){
   var sp=(src.value||"").split("|"),tp=(tgt.value||"").split("|");
   var fromRm=sp[0]||"#101",gName=sp[1]||"Guest",oldRate=parseInt(sp[2]||"4200",10),bkRef=sp[3]||"#BK-2026-1094";
   var toRm=tp[0]||"#206",toCat=tp[1]||"Executive King",newRate=parseInt(tp[2]||"5500",10);
   var diff=pol.value==="comp_vip"?0:(pol.value==="half_diff"?Math.round((newRate-oldRate)/2):Math.max(0,newRate-oldRate));
   var rateTxt=diff===0?"Complimentary VIP (৳ "+oldRate.toLocaleString()+"/nt)":"+৳ "+diff.toLocaleString()+"/nt (New: ৳ "+(oldRate+diff).toLocaleString()+"/nt)";
   var reason=(document.getElementById("rmove-reason")||{}).value||"Suite move";
   var tb=document.getElementById("rmove-audit-tbody");
   if(tb){
    var tr=document.createElement("tr");
    tr.innerHTML="<td>2026-10-14 14:15</td><td><code>"+bkRef+"</code></td><td><strong>"+gName+"</strong></td><td><code>"+fromRm+"</code> → <code>"+toRm+"</code></td><td>"+rateTxt+"</td><td>"+reason+" · "+fromRm+" HK Turnaround</td><td><span class=\\"mm-badge mm-badge-paid\\">Folio Merged</span></td>";
    tb.insertBefore(tr,tb.firstChild);
   }
   var b=document.getElementById("rmove-success-banner"),bt=document.getElementById("rmove-success-text"),kc=document.getElementById("rmove-kpi-count");
   if(bt)bt.textContent="Suite Move Completed: "+gName+" ("+bkRef+") moved from "+fromRm+" to "+toRm+" ("+toCat+"). "+fromRm+" queued for Housekeeping Turnaround.";
   if(b)b.style.display="block";
   if(kc)kc.innerHTML=(tb?tb.children.length:3)+" Completed <span class=\\"mm-table-kpi-sub\\">Folio Synced</span>";
  });
 })();</script>`;
 return shellWith(body,'','','In-Stay Room Move & Folio Transfer (F9)',['Hotel','Bookings','Room Move']);
}
function guestCrmPage(){
 const crmRows=[
  ['Tanvir Ahmed Chowdhury','VIP Corporate','Mohammadi Group','8 Stays · 22 Nights','৳ 284,500','High Floor · King Bed · Extra Espresso','vip','Active In-House (#101)'],
  ['Brig. Gen. Mahmudul Hasan','VIP Protocol','Defence Services Command','11 Stays · 31 Nights','৳ 465,000','Corner Suite · Early Check-In · Newspaper','vip','Active In-House (#204)'],
  ['Amb. Kenji Takahashi','Diplomatic','Embassy of Japan (Dhaka)','6 Stays · 19 Nights','৳ 390,000','Japanese Green Tea · Diplomatic Floor 3','vip','Active In-House (#301)'],
  ['Nusrat Jahan','Corporate Contract','UNICEF Bangladesh','9 Stays · 24 Nights','৳ 312,000','Quiet Workspace · Airport Chauffeur','corp','Active In-House (#201)'],
  ['Elena Rostova','Corporate Contract','World Bank Group','7 Stays · 21 Nights','৳ 348,000','Dedicated VPN Router · Hypoallergenic Pillow','corp','Active In-House (#402)'],
  ['Rubana Huq','VIP Loyalty','BGMEA / Mohammadi','14 Stays · 38 Nights','৳ 620,000','Presidential Suite #403 · Boardroom Setup','loyalty','Active In-House (#403)'],
  ['Capt. Jonathan Vance','Airline Crew Contract','Horizon Air International','19 Stays · 42 Nights','৳ 410,000','Blackout Curtains · 04:45 AM Wake-Up Box','corp','Active In-House (#302)'],
  ['Dr. Farhana Rahman','Repeat Loyalty','Square Hospitals Ltd.','5 Stays · 12 Nights','৳ 148,000','Feather Pillows · Late Check-Out','loyalty','Active In-House (#103)'],
 ];
 const trs=crmRows.map(([name,tier,org,stays,ltv,pref,tag,st])=>'<tr class="crm-row" data-crm-tag="'+tag+'" data-crm-search="'+(name+' '+tier+' '+org+' '+pref).toLowerCase()+'"><td><strong>'+name+'</strong><div class="tw-text-xs tw-text-muted">'+org+'</div></td><td><span class="mm-badge mm-badge-'+(tag==='vip'?'paid':'partial')+'">'+tier+'</span></td><td>'+stays+'</td><td><strong>'+ltv+'</strong></td><td>'+pref+'</td><td><span class="mm-badge mm-badge-paid">'+st+'</span></td><td class="text-center"><button type="button" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-id-badge"></i> 360° Profile</button></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-address-book-o"></i> Guest &amp; Corporate CRM 360° Directory (Plan B · F11)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Revived CRM workspace (<code>c_r_m_customers</code>): guest preferences, stay history, lifetime folio value (LTV), corporate rate contracts, and loyalty tiers.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/guests" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> All 20 Guests</a>
   <a href="/preview/city-ledger" class="mm-button mm-button-secondary"><i class="fa fa-building-o"></i> Corporate City Ledger (F20)</a>
   <button type="button" class="mm-button" data-toggle="modal" data-target="#mm-quick-drawer"><i class="fa fa-plus"></i> New Corporate Contract</button>
  </div>
 </header>
 <div class="mm-table-kpi-grid" aria-label="Guest CRM summary">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-users"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">CRM Guest Profiles</span><div class="mm-table-kpi-value">20 Verified <span class="mm-table-kpi-sub">100% KYC Linked</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-building-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Corporate Contracts</span><div class="mm-table-kpi-value">8 Organizations <span class="mm-table-kpi-sub">Direct-Bill Active</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-repeat"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Repeat Stay Ratio</span><div class="mm-table-kpi-value">68.4% Returning <span class="mm-table-kpi-sub">Avg 8.4 Stays</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-line-chart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Combined Lifetime Value</span><div class="mm-table-kpi-value">৳ 4,820,000 <span class="mm-table-kpi-sub">Room + F&amp;B + Banquet</span></div></div></div>
 </div>
 <div class="mm-panel tw-p-5">
  <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px">
   <div class="tw-flex tw-flex-wrap tw-gap-2">
    <button type="button" class="mm-preview-pill is-active" data-crm-filter="all">All CRM Profiles (8)</button>
    <button type="button" class="mm-preview-pill" data-crm-filter="vip">VIP &amp; Diplomatic</button>
    <button type="button" class="mm-preview-pill" data-crm-filter="corp">Corporate Contracts</button>
    <button type="button" class="mm-preview-pill" data-crm-filter="loyalty">Repeat Loyalty Circle</button>
   </div>
   <input type="search" id="crm-search-input" class="form-control" style="max-width:280px" placeholder="Search guest, organization, preference...">
  </div>
  <div class="mm-table-scroll">
   <table class="mm-data-table table table-bordered table-striped" id="crm-table">
    <thead><tr><th>Guest &amp; Organization</th><th>CRM Tier</th><th>Stay History</th><th>Lifetime Value (LTV)</th><th>Recorded Room &amp; Dietary Preferences</th><th>Current Status</th><th class="text-center">Action</th></tr></thead>
    <tbody>${trs}</tbody>
   </table>
  </div>
 </div>
 </div></section>
 <script>(function(){
  var pills=document.querySelectorAll("[data-crm-filter]"),rows=document.querySelectorAll(".crm-row"),q=document.getElementById("crm-search-input"),tag="all";
  function filter(){
   var s=(q&&q.value||"").toLowerCase().trim();
   rows.forEach(function(r){
    var ok=(tag==="all"||r.getAttribute("data-crm-tag")===tag)&&(!s||r.getAttribute("data-crm-search").indexOf(s)>=0);
    r.style.display=ok?"":"none";
   });
  }
  pills.forEach(function(p){p.addEventListener("click",function(){pills.forEach(function(x){x.classList.remove("is-active");});p.classList.add("is-active");tag=p.getAttribute("data-crm-filter");filter();});});
  if(q)q.addEventListener("input",filter);
 })();</script>`;
 return shellWith(body,'','','Guest & Corporate CRM 360° Directory (F11)',['Hotel','CRM','Guest & Corporate CRM']);
}
function minibarFolioPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-glass"></i> In-Room Minibar &amp; Asset Consumption Auto-Post to Folio (Plan B · F15)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Link Bar &amp; General Store minibar stock directly to in-house guest room folios with automatic stock deduction and live folio posting.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/checkout" class="mm-button mm-button-secondary"><i class="fa fa-credit-card"></i> Checkout Folio</a>
   <a href="/preview/bar-pos" class="mm-button mm-button-secondary"><i class="fa fa-glass"></i> Bar POS</a>
  </div>
 </header>
 <div class="mm-table-kpi-grid" aria-label="Minibar folio summary">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-cubes"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Stocked Suites</span><div class="mm-table-kpi-value">20 Mini-Bars <span class="mm-table-kpi-sub"> Seal Verified</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Auto-Posted Today</span><div class="mm-table-kpi-value" id="mb-kpi-posted">৳ 8,450 <span class="mm-table-kpi-sub">14 Items Synced</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-refresh"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Restock Queue</span><div class="mm-table-kpi-value">3 Suites <span class="mm-table-kpi-sub">#101, #204, #301</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-bolt"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Folio Sync Latency</span><div class="mm-table-kpi-value">Instant <span class="mm-table-kpi-sub">Bar Store Linked</span></div></div></div>
 </div>
 <div class="mm-panel tw-p-5">
  <h2 class="tw-m-0 tw-mb-3 tw-text-base tw-font-semibold"><i class="fa fa-plus-circle"></i> Record Suite Minibar Consumption &amp; Auto-Post to Room Folio</h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:14px">
   <div>
    <label class="mm-label" for="mb-room-select">In-House Suite &amp; Active Folio</label>
    <select id="mb-room-select" class="form-control">
     <option value="#101|Tanvir Ahmed Chowdhury|#BK-2026-1094" selected>Room #101 — Tanvir Ahmed Chowdhury (#BK-2026-1094)</option>
     <option value="#204|Brig. Gen. Mahmudul Hasan|#BK-2026-1088">Suite #204 — Brig. Gen. Mahmudul Hasan (#BK-2026-1088)</option>
     <option value="#301|Amb. Kenji Takahashi|#BK-2026-1091">Suite #301 — Amb. Kenji Takahashi (#BK-2026-1091)</option>
     <option value="#403|Rubana Huq|#BK-2026-1095">Suite #403 — Rubana Huq (#BK-2026-1095)</option>
    </select>
   </div>
   <div>
    <label class="mm-label" for="mb-item-select">Consumed Minibar Item (Bar / Store SKU)</label>
    <select id="mb-item-select" class="form-control">
     <option value="Artisanal Roasted Cashews &amp; Almonds (150g)|650" selected>Artisanal Roasted Cashews &amp; Almonds (150g) — ৳ 650</option>
     <option value="Perrier Sparkling Mineral Water (330ml)|450">Perrier Sparkling Mineral Water (330ml) — ৳ 450</option>
     <option value="Belgian 70% Dark Chocolate Bar|800">Belgian 70% Dark Chocolate Bar — ৳ 800</option>
     <option value="Cold-Pressed Organic Mango Nectar|380">Cold-Pressed Organic Mango Nectar — ৳ 380</option>
     <option value="Nespresso Heritage Gold Capsules (4-Pack)|720">Nespresso Heritage Gold Capsules (4-Pack) — ৳ 720</option>
    </select>
   </div>
   <div>
    <label class="mm-label" for="mb-qty">Quantity Consumed</label>
    <input type="number" id="mb-qty" class="form-control" min="1" max="10" value="2">
   </div>
  </div>
  <div id="mb-post-banner" class="alert alert-success" style="display:none;margin-bottom:12px;font-size:12.5px;">
   <i class="fa fa-check-circle"></i> <span id="mb-post-text">Posted to Room #101 Folio (#BK-2026-1094)! Bar inventory decremented and housekeeping restock queued.</span>
  </div>
  <button type="button" id="mb-btn-post" class="mm-button"><i class="fa fa-check"></i> Auto-Post Consumption to Guest Folio</button>
 </div>
 <div class="mm-panel tw-p-5">
  <div class="mm-table-scroll">
   <table class="mm-data-table table table-bordered table-striped" id="mb-ledger-table">
    <thead><tr><th>Time</th><th>Suite &amp; Folio</th><th>Guest Name</th><th>Minibar Item</th><th>Qty</th><th class="text-right">Posted Amount</th><th>Status</th></tr></thead>
    <tbody id="mb-ledger-tbody">
     <tr><td>13:40</td><td><strong>#204</strong> (<code>#BK-2026-1088</code>)</td><td>Brig. Gen. Mahmudul Hasan</td><td>Perrier Sparkling Mineral Water (330ml)</td><td>2</td><td class="text-right"><strong>৳ 900</strong></td><td><span class="mm-badge mm-badge-paid">Posted to Folio</span></td></tr>
     <tr><td>12:15</td><td><strong>#301</strong> (<code>#BK-2026-1091</code>)</td><td>Amb. Kenji Takahashi</td><td>Belgian 70% Dark Chocolate Bar</td><td>1</td><td class="text-right"><strong>৳ 800</strong></td><td><span class="mm-badge mm-badge-paid">Posted to Folio</span></td></tr>
    </tbody>
   </table>
  </div>
 </div>
 </div></section>
 <script>(function(){
  var btn=document.getElementById("mb-btn-post");
  if(btn)btn.addEventListener("click",function(){
   var rm=(document.getElementById("mb-room-select").value||"").split("|");
   var it=(document.getElementById("mb-item-select").value||"").split("|");
   var qty=Math.max(1,parseInt(document.getElementById("mb-qty").value||"1",10));
   var amt=(parseInt(it[1]||"650",10))*qty;
   var tb=document.getElementById("mb-ledger-tbody");
   if(tb){
    var tr=document.createElement("tr");
    tr.innerHTML="<td>14:20</td><td><strong>"+rm[0]+"</strong> (<code>"+rm[2]+"</code>)</td><td>"+rm[1]+"</td><td>"+it[0]+"</td><td>"+qty+"</td><td class=\\"text-right\\"><strong>৳ "+amt.toLocaleString()+"</strong></td><td><span class=\\"mm-badge mm-badge-paid\\">Posted to Folio</span></td>";
    tb.insertBefore(tr,tb.firstChild);
   }
   var b=document.getElementById("mb-post-banner"),bt=document.getElementById("mb-post-text");
   if(bt)bt.textContent="Posted "+qty+"x "+it[0]+" (৳ "+amt.toLocaleString()+") directly to "+rm[0]+" Folio ("+rm[2]+" · "+rm[1]+").";
   if(b)b.style.display="block";
  });
 })();</script>`;
 return shellWith(body,'','','Minibar & In-Room Consumption Auto-Post (F15)',['Hotel','Housekeeping','Minibar Auto-Post']);
}
function cityLedgerPage(){
 const arRows=[
  ['#CL-101','UNICEF Bangladesh (Country Office)','৳ 500,000','৳ 142,000','৳ 112,000','৳ 30,000','৳ 0',28,'is-emerald','Current (Net 30)','paid'],
  ['#CL-102','World Bank Group (Dhaka Mission)','৳ 600,000','৳ 185,000','৳ 185,000','৳ 0','৳ 0',31,'is-emerald','Current (Net 30)','paid'],
  ['#CL-103','Mohammadi Group Ltd.','৳ 450,000','৳ 128,000','৳ 98,000','৳ 30,000','৳ 0',28,'is-emerald','Current (Net 30)','paid'],
  ['#CL-104','Horizon Air International (Crew Account)','৳ 500,000','৳ 160,000','৳ 110,000','৳ 50,000','৳ 0',32,'','Statement Sent','partial'],
  ['#CL-105','Apex Footwear &amp; Corporate Events','৳ 250,000','৳ 70,000','৳ 40,000','৳ 0','৳ 30,000',28,'is-violet','60d Reminder','unpaid'],
 ];
 const trs=arRows.map(([code,corp,limit,bal,b30,b60,b90,pct,cls,st,badge])=>'<tr><td><code>'+code+'</code></td><td><strong>'+corp+'</strong><div class="tw-text-xs tw-text-muted">Credit Limit: '+limit+'</div></td><td class="text-right"><strong>'+bal+'</strong></td><td class="text-right">'+b30+'</td><td class="text-right">'+b60+'</td><td class="text-right">'+b90+'</td><td><div class="mm-share-cell"><span>'+pct+'% Used</span><span class="mm-share-bar"><span class="mm-share-fill '+cls+'" style="width:'+pct+'%"></span></span></div></td><td><span class="mm-badge mm-badge-'+badge+'">'+st+'</span></td></tr>').join('');
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-building-o"></i> Corporate AR / City Ledger &amp; Credit Aging Report (Plan B · F20)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Manage corporate direct-bill accounts, credit limits, 30/60/90-day aging buckets, and automated Statement of Account (SOA) dispatch.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <a href="/preview/payment-collection" class="mm-button mm-button-secondary"><i class="fa fa-money"></i> Payment Collection</a>
   <button type="button" id="cl-btn-send-soa" class="mm-button" onclick="var b=document.getElementById('cl-soa-banner');if(b)b.style.display='block';"><i class="fa fa-envelope-o"></i> Dispatch Monthly SOA &amp; Reminders</button>
  </div>
 </header>
 <div id="cl-soa-banner" class="alert alert-success" style="display:none;font-size:12.5px;">
  <i class="fa fa-check-circle"></i> <strong>Monthly Statements of Account (SOA) Dispatched!</strong> PDF statements emailed to all 5 active corporate billing controllers.
 </div>
 <div class="mm-table-kpi-grid" aria-label="City ledger summary">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-building-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Total City Ledger AR</span><div class="mm-table-kpi-value">৳ 685,000 <span class="mm-table-kpi-sub">5 Corporate Accounts</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">0–30 Days (Current)</span><div class="mm-table-kpi-value">৳ 545,000 <span class="mm-table-kpi-sub">79.6% Current</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-clock-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">31–60 Days Bucket</span><div class="mm-table-kpi-value">৳ 110,000 <span class="mm-table-kpi-sub">16.0% Invoiced</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-exclamation-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">61–90+ Days Overdue</span><div class="mm-table-kpi-value">৳ 30,000 <span class="mm-table-kpi-sub">4.4% Follow-Up</span></div></div></div>
 </div>
 <div class="mm-panel tw-p-5"><div class="mm-table-scroll"><table class="mm-data-table table table-bordered table-striped"><thead><tr><th>Account</th><th>Corporate Entity &amp; Credit Limit</th><th class="text-right">Total AR Balance</th><th class="text-right">0–30 Days</th><th class="text-right">31–60 Days</th><th class="text-right">61–90+ Days</th><th>Credit Utilization</th><th>Aging Status</th></tr></thead><tbody>${trs}</tbody></table></div></div>
 </div></section>`;
 return shellWith(body,'','','Corporate AR / City Ledger & Aging (F20)',['Account','City Ledger','AR Aging']);
}
function managerBiPage(){
 const body=`<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
 <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
  <div class="mm-page-title">
   <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-bar-chart"></i> Manager BI: Occupancy, ADR, RevPAR &amp; Channel Mix (Plan B · F29)</h1>
   <p class="tw-m-0 tw-text-sm tw-text-muted">Executive hospitality intelligence across all 9 day-1 Plan B KPIs: Occupancy %, ADR, RevPAR, Direct-vs-OTA Mix, Pickup Pace, F&amp;B Attach Rate, and AR Days.</p>
  </div>
  <div class="tw-flex tw-flex-wrap tw-gap-2">
   <button type="button" class="mm-preview-pill is-active" data-bi-period="today">Today (14 Oct 2026)</button>
   <button type="button" class="mm-preview-pill" data-bi-period="mtd">MTD (Oct 2026)</button>
   <button type="button" class="mm-preview-pill" data-bi-period="q4">Q4 Forecast</button>
   <button type="button" class="mm-button mm-button-secondary" onclick="window.print()"><i class="fa fa-print"></i> Print Executive BI Brief</button>
  </div>
 </header>
 <div class="mm-table-kpi-grid" aria-label="Manager BI core KPIs">
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">1. Occupancy Rate</span><div class="mm-table-kpi-value" id="bi-kpi-occ">90.0% <span class="mm-table-kpi-sub">18 of 20 Suites Sold</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-line-chart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">2. ADR (Avg Daily Rate)</span><div class="mm-table-kpi-value" id="bi-kpi-adr">৳ 7,450 <span class="mm-table-kpi-sub">+8.4% vs Last Month</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-bolt"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">3. RevPAR</span><div class="mm-table-kpi-value" id="bi-kpi-revpar">৳ 6,705 <span class="mm-table-kpi-sub">৳ 134,100 Room Rev/Day</span></div></div></div>
  <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-cutlery"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">4. F&amp;B Attach &amp; NPS</span><div class="mm-table-kpi-value" id="bi-kpi-fnb">76.4% · 94 NPS <span class="mm-table-kpi-sub">19d Avg AR Settlement</span></div></div></div>
 </div>
 <div class="mm-panel tw-p-5">
  <h2 class="tw-m-0 tw-mb-3 tw-text-base tw-font-semibold"><i class="fa fa-pie-chart"></i> Suite Category RevPAR, Pickup Pace &amp; Direct-vs-OTA Mix Breakdown</h2>
  <div class="mm-table-scroll">
   <table class="mm-data-table table table-bordered table-striped">
    <thead><tr><th>Suite Category</th><th>Inventory</th><th>Occupied</th><th>ADR</th><th>RevPAR</th><th>Occupancy &amp; Revenue Share</th><th>Direct vs OTA Mix</th></tr></thead>
    <tbody>
     <tr><td><strong>Floor 1 · Deluxe &amp; Superior Rooms (#101–#106)</strong></td><td>6 Rooms</td><td>5 / 6</td><td>৳ 4,350</td><td><strong>৳ 3,625</strong></td><td><div class="mm-share-cell"><span>83.3% Occ</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:83.3%"></span></span></div></td><td>50% Direct / 50% OTA</td></tr>
     <tr><td><strong>Floor 2 · Heritage &amp; Royal Family Suites (#201–#206)</strong></td><td>6 Suites</td><td>5 / 6</td><td>৳ 6,850</td><td><strong>৳ 5,708</strong></td><td><div class="mm-share-cell"><span>83.3% Occ</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:83.3%"></span></span></div></td><td>60% Direct / 40% OTA</td></tr>
     <tr><td><strong>Floor 3 · Diplomatic &amp; Presidential Suites (#301–#305)</strong></td><td>5 Suites</td><td>5 / 5</td><td>৳ 8,900</td><td><strong>৳ 8,900</strong></td><td><div class="mm-share-cell"><span>100% Sold Out</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td>80% Corporate Direct</td></tr>
     <tr><td><strong>Floor 4 · Royal Penthouse &amp; Grand Presidential (#401–#403)</strong></td><td>3 Suites</td><td>3 / 3</td><td>৳ 13,200</td><td><strong>৳ 13,200</strong></td><td><div class="mm-share-cell"><span>100% Sold Out</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:100%"></span></span></div></td><td>100% VIP Direct</td></tr>
    </tbody>
   </table>
  </div>
 </div>
 </div></section>
 <script>(function(){
  var data={
   today:{occ:"90.0% <span class=\\"mm-table-kpi-sub\\">18 of 20 Suites Sold</span>",adr:"৳ 7,450 <span class=\\"mm-table-kpi-sub\\">+8.4% vs Last Month</span>",rev:"৳ 6,705 <span class=\\"mm-table-kpi-sub\\">৳ 134,100 Room Rev/Day</span>",fnb:"76.4% · 94 NPS <span class=\\"mm-table-kpi-sub\\">19d Avg AR Settlement</span>"},
   mtd:{occ:"87.5% <span class=\\"mm-table-kpi-sub\\">245 Room-Nights MTD</span>",adr:"৳ 7,280 <span class=\\"mm-table-kpi-sub\\">+6.9% vs Budget</span>",rev:"৳ 6,370 <span class=\\"mm-table-kpi-sub\\">৳ 1,783,600 MTD Rev</span>",fnb:"74.8% · 92 NPS <span class=\\"mm-table-kpi-sub\\">1.8% Cancel/No-Show</span>"},
   q4:{occ:"92.4% <span class=\\"mm-table-kpi-sub\\">Peak Season Pace +14/d</span>",adr:"৳ 7,890 <span class=\\"mm-table-kpi-sub\\">Peak Corporate BAR</span>",rev:"৳ 7,290 <span class=\\"mm-table-kpi-sub\\">+12.1% YoY Projected</span>",fnb:"81.0% · 95 NPS <span class=\\"mm-table-kpi-sub\\">Banquet Gala Season</span>"}
  };
  document.querySelectorAll("[data-bi-period]").forEach(function(b){
   b.addEventListener("click",function(){
    document.querySelectorAll("[data-bi-period]").forEach(function(x){x.classList.remove("is-active");});
    b.classList.add("is-active");
    var d=data[b.getAttribute("data-bi-period")]||data.today;
    document.getElementById("bi-kpi-occ").innerHTML=d.occ;
    document.getElementById("bi-kpi-adr").innerHTML=d.adr;
    document.getElementById("bi-kpi-revpar").innerHTML=d.rev;
    document.getElementById("bi-kpi-fnb").innerHTML=d.fnb;
   });
  });
 })();</script>`;
 return shellWith(body,'','','Manager BI: Occupancy, ADR & RevPAR (F29)',['Hotel','Reports','Manager BI']);
}
const {buildPlanBPages}=require(path.join(root,'tools/plan-b-features.cjs'));
const specialPages={
 ...buildPlanBPages(shellWith),
 '/preview/draft-booking-pdf':{fn:draftBookingPdfPage,title:'Whole Booking Preview — Draft Reservation Summary PDF',crumbs:['Hotel','Bookings','Draft Summary PDF']},
 '/preview/kiosk-checkin':{fn:kioskCheckinPage,title:'Self-Service Kiosk Check-In & Digital Reg. Card (Plan B · F10)',crumbs:['Hotel','Front Desk','Kiosk Check-In']},
 '/preview/room-move':{fn:roomMovePage,title:'In-Stay Room Move & Folio Transfer (Plan B · F9)',crumbs:['Hotel','Bookings','Room Move']},
 '/preview/minibar-folio':{fn:minibarFolioPage,title:'In-Room Minibar Consumption Auto-Post (Plan B · F15)',crumbs:['Hotel','Housekeeping','Minibar Auto-Post']},
 '/preview/city-ledger':{fn:cityLedgerPage,title:'Corporate AR / City Ledger & Aging Report (Plan B · F20)',crumbs:['Account','City Ledger','AR Aging']},
 '/preview/manager-bi':{fn:managerBiPage,title:'Manager BI: Occupancy, ADR, RevPAR & Channel Mix (Plan B · F29)',crumbs:['Hotel','Reports','Manager BI']},
 '/preview/wiki':{fn:wikiPage,title:'User Documentation, Wiki & Help (EN / বাংলা)',crumbs:['System','Documentation','Wiki & Help']},
 '/preview/help':{fn:wikiPage,title:'Help Center & SOP Checklists (EN / বাংলা)',crumbs:['System','Documentation','Help']},
 '/preview/sop-print':{fn:sopPrintPage,title:'Printable Bilingual SOP Cheat Sheet (EN / বাংলা)',crumbs:['System','Documentation','Printable SOP']},
 '/preview/onboarding':{fn:onboardingPage,title:'Role-Based Staff Onboarding Hub (EN / বাংলা)',crumbs:['System','Documentation','Onboarding']},
 '/preview/rooms':{fn:roomsDirectoryPage,title:'Hotel Rooms & Suite Inventory (20 Rooms)',crumbs:['Hotel','Rooms','Directory']},
 '/preview/guests':{fn:guestsDirectoryPage,title:'Registered Guests & VIP CRM Directory (20 Guests)',crumbs:['Hotel','Guests','Directory']},
 '/preview/bookings':{fn:bookingsDirectoryPage,title:'Room Bookings & Active Reservations (20 Bookings)',crumbs:['Hotel','Bookings','List']},
 '/preview/tape-chart':{fn:tapeChartPage,title:'7-Day Reservation Tape Chart (Plan B · F6)',crumbs:['Hotel','Bookings','Tape Chart']},
 '/preview/housekeeping-board':{fn:housekeepingBoardPage,title:'Housekeeping & Maintenance SLA Board (F13/F14)',crumbs:['Hotel','Housekeeping','SLA Board']},
 '/preview/concierge-requests':{fn:conciergeRequestsPage,title:'Concierge & Guest Requests Desk (F12)',crumbs:['Hotel','Concierge','Requests']},
 '/preview/channel-manager':{fn:channelManagerPage,title:'OTA Channel Manager & iCal Sync (F2)',crumbs:['Hotel','Distribution','Channel Manager']},
 '/preview/rate-plans':{fn:ratePlansPage,title:'Seasonal Rate Plans & Promo Engine (F3)',crumbs:['Hotel','Distribution','Rate Plans']},
 '/preview/ui-kit':{fn:uiKitPage,title:'UI Component Library (/ui-kit)',crumbs:['System','Design System','UI Kit']},
 '/preview/booking-drawers':{fn:bookingDrawersPage,title:'Front-Desk & POS Slide-Over Drawers (W3.9)',crumbs:['Hotel','Bookings','Drawers']},
 '/preview/system-settings':{fn:systemSettingsPage,title:'System Settings (W6.5)',crumbs:['System','Settings']},
 '/preview/system-sync':{fn:systemSyncPage,title:'Cloud Data Sync v2 (W6.5)',crumbs:['System','Cloud Sync']},
 '/preview/login':{fn:loginPage,title:'Staff Login (W6.5 Auth)',crumbs:['System','Auth','Login']},
 '/preview/password-reset':{fn:passwordResetPage,title:'Password Reset (W6.5 Auth)',crumbs:['System','Auth','Password Reset']},
 '/preview/register':{fn:registerPage,title:'Register Staff Account (W6.5 Auth)',crumbs:['System','Auth','Register']},
 '/preview/news-notices':{fn:newsNoticesPage,title:'Staff & Guest Notice Board',crumbs:['News & Events','Notices']},
 '/preview/news-notice-create':{fn:newsNoticeCreatePage,title:'Publish New Notice',crumbs:['News & Events','Notices','Create']},
 '/preview/sms-apis':{fn:smsApisPage,title:'SMS Gateway APIs',crumbs:['News & Events','SMS','Gateways']},
 '/preview/sms-send':{fn:smsSendPage,title:'Guest & Corporate SMS Dispatch',crumbs:['News & Events','SMS','Dispatch']},
 '/preview/email-templates':{fn:emailTemplatesPage,title:'Branded Hospitality Email Templates (B3)',crumbs:['System','Email Templates']},
 '/preview/global-companies':{fn:globalCompaniesPage,title:'Enterprise Companies & Properties',crumbs:['Global Setup','Companies']},
 '/preview/global-groups':{fn:globalGroupsPage,title:'Enterprise Group Setup',crumbs:['Global Setup','Groups']},
 '/preview/global-id-cards':{fn:globalIdCardsPage,title:'Staff & VIP ID Card Designer',crumbs:['Global Setup','ID Cards']},
 '/preview/activity-log':{fn:activityLogPage,title:'System Audit & Activity Log',crumbs:['System','Activity Log']},
 '/preview/attendance-devices':{fn:attendanceDevicesPage,title:'Biometric Attendance Devices',crumbs:['System','Attendance Devices']},
 '/preview/payment-schedules':{fn:paymentSchedulesPage,title:'Software & AMC Payment Schedules',crumbs:['System','Payment Schedules']},
};
function moduleOf(p){
 if(p.startsWith('/preview/public-')) return 'Public Storefront';
 if(p.startsWith('/preview/acc-')) return 'Account & Finance';
 if(p.startsWith('/preview/rst-')) return 'Restaurant & POS';
 if(p.startsWith('/preview/rsi-')) return 'Kitchen & Inventory';
 if(p.startsWith('/preview/bar-')) return 'Bar & Lounge';
 if(p.startsWith('/preview/gs-')) return 'General Store';
 if(p.startsWith('/preview/bq-')||p.startsWith('/preview/hservice-')) return 'Banquet & Services';
 if(p.startsWith('/preview/web-')) return 'Website CMS';
 if(specialPages[p]&&specialPages[p].crumbs&&specialPages[p].crumbs[0]==='Hotel') return 'Hotel & Front Desk';
 if(p.startsWith('/preview/perm-')||p.startsWith('/preview/news-')||p.startsWith('/preview/sms-')||specialPages[p]) return 'Permissions & System';
 return 'Hotel & Front Desk';
}
function listPage(extra){
 const pubEntries=Object.keys(pubPages).map(p=>[p,'Public Storefront · '+p.replace('/preview/public-','').replace(/-/g,' ').replace(/\b\w/g,c=>c.toUpperCase()),'Public hotel website screen rendered on frontend.layouts.mm-web with --web-* brand tokens and frosted-glass cards.']);
 const specEntries=Object.entries(specialPages).map(([p,v])=>[p,v.title,'Interactive W1.6 / W6.5 showcase screen in Light & Dark Mode.']);
 const items=[['/','Dashboard and room booking board','Room board with the stay date range, quick chips and summary cards.'],...specEntries,...Object.entries(pages).map(([p,v])=>[p,v.title,p==='/preview/checkout'?'Night +/- recalculates totals, discount, paid amount and full payment (real calculation script).':p==='/preview/payment-collection'?'Search a guest, review unpaid invoices, enter a payment or tick full payment (real script).':p.startsWith('/preview/report-')?'Hotel report rendered from the real Blade view and export table with sample rows; the filter bar sits above the results.':p.startsWith('/preview/setup-')?'Hotel setup screen rendered from the real Blade view with sample records.':p.startsWith('/preview/perm-')?'Permission screen rendered from the real Blade view with sample data; the access matrices keep their checkbox scripts.':p.startsWith('/preview/hservice-')?'Hotel service screen rendered from the real Blade view with sample data; the sale form keeps its calculation script.':p==='/preview/night-audit'?'Closed audit days with totals and the shared export table.':p==='/preview/night-audit-generate'?'Review the day\'s collections and generate the audit (10-second delay kept from the legacy form).':p==='/preview/invoice'?'Printable booking invoice; the Print button prints the document only.':'Renovated ERP screen with 4-card executive KPI strip, frosted-glass panels, and Slide-Over App Drawer support.']),...pubEntries];
 const groups=['All','Hotel & Front Desk','Restaurant & POS','Kitchen & Inventory','Bar & Lounge','General Store','Account & Finance','Banquet & Services','Website CMS','Permissions & System','Public Storefront'];
 const counts={All:items.length};
 items.forEach(([p])=>{const m=moduleOf(p);counts[m]=(counts[m]||0)+1;});
 const pills=groups.map((g,i)=>'<button type="button" class="mm-preview-pill'+(i===0?' is-active':'')+'" data-mod-filter="'+g+'">'+g+' <span style="opacity:.75">('+(counts[g]||0)+')</span></button>').join('');
 const cards=items.map(([p,t,d])=>{const m=moduleOf(p);return '<a href="'+p+'" class="mm-preview-card" data-mod="'+m+'" data-search="'+(t+' '+m+' '+p+' '+d).toLowerCase().replace(/"/g,'')+'"><div class="mm-preview-card-head"><span class="mm-preview-card-title">'+t+'</span><span class="mm-preview-card-tag">'+m+'</span></div><p class="mm-preview-card-desc">'+d+'</p><span class="mm-preview-card-route">'+p+'</span></a>';}).join('');
 const body=(extra?'<div class="mm-panel tw-p-4" style="margin-bottom:16px"><strong>'+extra+'</strong></div>':'')+
 '<section class="mm-ui"><div class="tw-space-y-4 tw-py-2"><header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2"><div class="mm-page-title"><h1 class="tw-m-0 tw-font-semibold tw-text-ink">Interactive 300-Screen Preview Explorer</h1><p class="tw-m-0 tw-text-sm tw-text-muted">Browse, filter by module, or press Ctrl+K / Cmd+K anywhere to jump across all '+items.length+' renovated screens.</p></div><div class="tw-flex tw-flex-wrap tw-gap-2"><a href="/preview/plan-b" class="mm-button"><i class="fa fa-rocket"></i> Plan B Hub (34/34)</a><a href="/preview/wiki" class="mm-button mm-button-secondary"><i class="fa fa-book"></i> Wiki &amp; Help (EN / বাংলা)</a><a href="/preview/ui-kit" class="mm-button mm-button-secondary"><i class="fa fa-cubes"></i> UI Component Kit</a><a href="/preview/public-home" class="mm-button mm-button-secondary"><i class="fa fa-globe"></i> Public Storefront</a><a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-map-o"></i> Booking Wizard</a><a href="/preview/rst-pos" class="mm-button mm-button-secondary"><i class="fa fa-cutlery"></i> Restaurant POS</a></div></header>'+
 '<div class="mm-table-kpi-grid" aria-label="Preview explorer summary"><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-th-large"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Wired Preview Routes</span><div class="mm-table-kpi-value">'+items.length+' Screens <span class="mm-table-kpi-sub">100% Interactive</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-bar-chart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Executive KPI Strips</span><div class="mm-table-kpi-value">264 / 264 <span class="mm-table-kpi-sub">Admin Screens</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-columns"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Slide-Over App Drawers</span><div class="mm-table-kpi-value">29 / 29 <span class="mm-table-kpi-sub">Modals Upgraded</span></div></div></div><div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-keyboard-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Power Shortcuts</span><div class="mm-table-kpi-value">Ctrl+K &amp; g-Chords <span class="mm-table-kpi-sub">Press ? for Help</span></div></div></div></div>'+
 '<div class="mm-panel tw-p-5"><div class="mm-preview-hub-bar"><div class="input-group" style="max-width:420px;flex:1 1 280px"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="search" id="mm-preview-search" class="form-control" placeholder="Filter '+items.length+' screens by title, module, or /preview route..."></div><span id="mm-preview-count" class="mm-badge mm-badge-paid">'+items.length+' screens shown</span></div><div class="mm-preview-filter-pills" id="mm-preview-pills">'+pills+'</div><div class="mm-preview-grid" id="mm-preview-grid">'+cards+'</div></div></div></section>'+
 '<script>(function(){var q=document.getElementById("mm-preview-search"),pills=document.querySelectorAll("[data-mod-filter]"),cards=document.querySelectorAll(".mm-preview-card"),cnt=document.getElementById("mm-preview-count"),mod="All";function run(){var s=(q&&q.value||"").toLowerCase().trim(),n=0;for(var i=0;i<cards.length;i++){var c=cards[i],ok=(mod==="All"||c.getAttribute("data-mod")===mod)&&(!s||c.getAttribute("data-search").indexOf(s)>=0);c.style.display=ok?"":"none";if(ok)n++;}if(cnt)cnt.textContent=n+" screens shown";}if(q)q.addEventListener("input",run);for(var j=0;j<pills.length;j++){pills[j].addEventListener("click",function(){for(var k=0;k<pills.length;k++)pills[k].classList.remove("is-active");this.classList.add("is-active");mod=this.getAttribute("data-mod-filter");run();});}})();</script>';
 return shellWith(body,'','','Screens');
}
function menuLinks(page,currentPath){
 const cur=(currentPath||'').replace(/\/+$/,'')||'/';
 const hiddenAll='<li style="display:none"><a href="/preview">Preview Explorer</a><ul class="submenu">'+
  Object.entries(specialPages).map(([p,v])=>'<li><a href="'+p+'">'+v.title+'</a></li>').join('')+
  Object.entries(pages).map(([p,v])=>'<li><a href="'+p+'">'+v.title+' ('+p.replace('/preview/','')+')</a></li>').join('')+
  Object.keys(pubPages).map(p=>'<li><a href="'+p+'">Public Storefront: '+p.replace('/preview/public-','')+'</a></li>').join('')+
  '</ul></li>';

 const sections=[
  {
   heading:'Overview & Hubs',
   items:[
    {type:'link',href:'/dashboard',icon:'fa-tachometer',label:'Dashboard',aliases:['/','/home','/dashboard','/preview/dashboard']},
    {type:'group',icon:'fa-compass',label:'Command Hubs',children:[
     {type:'link',href:'/preview/plan-a',label:'Plan A (9 Core Workflows)'},
     {type:'link',href:'/preview/plan-b',label:'Plan B (All 34 Features)'},
     {type:'link',href:'/preview',label:'All 360 Screens Explorer'},
    ]},
   ],
  },
  {
   heading:'Hospitality & Front Desk',
   items:[
    {type:'group',icon:'fa-building-o',label:'Front Desk',children:[
     {type:'subgroup',label:'Bookings & Tape Chart',links:[
      ['/preview/booking-next','3-Step Booking Wizard'],
      ['/preview/tape-chart','7-Day Tape Chart'],
      ['/preview/bookings','Active Bookings (20)'],
      ['/preview/draft-booking-pdf','Draft Summary A4 PDF'],
      ['/preview/group-contracts','Group & Event Blocks'],
     ]},
     {type:'subgroup',label:'In-House & Checkout',links:[
      ['/preview/folio-settlement','Split-Folio Settlement'],
      ['/preview/checkout','Checkout & Folio Payment'],
      ['/preview/payment-collection','Guest Payment Collection'],
      ['/preview/room-move','Room Move & Upgrade'],
      ['/preview/kiosk-checkin','Self-Service Kiosk'],
      ['/preview/invoice','Booking Tax Invoice'],
     ]},
     {type:'subgroup',label:'Night Audit & Day Lock',links:[
      ['/preview/night-audit-hardening','Night-Audit Day Lock'],
      ['/preview/night-audit','Night Audit Register'],
      ['/preview/report-night-closing','Night Closing Report'],
     ]},
    ]},
    {type:'group',icon:'fa-address-book-o',label:'Guests & CRM 360°',children:[
     {type:'link',href:'/preview/guest-crm',label:'Guest & Corporate CRM 360°'},
     {type:'link',href:'/preview/guests',label:'Guests Directory (20 VIPs)'},
     {type:'link',href:'/preview/city-ledger',label:'Corporate City Ledger AR'},
     {type:'link',href:'/preview/loyalty-coupons',label:'Loyalty & Coupon Engine'},
     {type:'link',href:'/preview/hotel-sms',label:'Guest SMS Dispatch'},
    ]},
    {type:'group',icon:'fa-bed',label:'Rooms & Setup',children:[
     {type:'subgroup',label:'Rooms & Housekeeping',links:[
      ['/preview/rooms','Rooms Directory (20 Suites)'],
      ['/preview/housekeeping-board','Housekeeping SLA Board'],
      ['/preview/concierge-requests','Concierge & Lost/Found'],
      ['/preview/minibar-folio','Minibar Auto-Post'],
      ['/preview/maintenance-tickets','Maintenance Tickets'],
     ]},
     {type:'subgroup',label:'Rates & Distribution',links:[
      ['/preview/room-settings','Room Settings & Pricing'],
      ['/preview/rate-plans','Seasonal Rate Plans'],
      ['/preview/dynamic-pricing','Dynamic Yield Pricing'],
      ['/preview/channel-manager','OTA Channel Manager'],
      ['/preview/inventory-guards','OOO & Inventory Guards'],
     ]},
     {type:'subgroup',label:'Categories & Tax Rules',links:[
      ['/preview/category-create','Add Room Category'],
      ['/preview/setup-amenities','Room Amenities Setup'],
      ['/preview/setup-vat','VAT & Service Charge'],
      ['/preview/setup-registration-terms','Registration Terms'],
     ]},
    ]},
    {type:'group',icon:'fa-calendar',label:'Banquet & Services',children:[
     {type:'subgroup',label:'Banquet Hall & Events',links:[
      ['/preview/bq-booking-index','Banquet Bookings'],
      ['/preview/bq-booking-create','New Banquet Booking'],
      ['/preview/bq-rooms-index','Banquet Halls & Venues'],
      ['/preview/bq-category-index','Hall Categories'],
      ['/preview/bq-aminities-index','Hall Amenities'],
     ]},
     {type:'subgroup',label:'Concierge & Spa Services',links:[
      ['/preview/hservice-sales','Hotel Service Sales'],
      ['/preview/hservice-sale-create','New Service Sale'],
      ['/preview/hservice-services','Services Catalog'],
      ['/preview/upsell-marketplace','Spa & Upsell Add-Ons'],
     ]},
    ]},
   ],
  },
  {
   heading:'F&B POS & Inventory',
   items:[
    {type:'group',icon:'fa-cutlery',label:'Restaurant & Bar POS',children:[
     {type:'subgroup',label:'POS & Thermal KOT',links:[
      ['/preview/pos-unified','Unified Split-Bill POS'],
      ['/preview/pos-kot-print','80mm Thermal KOT Studio'],
      ['/preview/rst-pos','Restaurant POS (sales-v2)'],
      ['/preview/bar-pos','Skyline Bar POS (sales-v2)'],
     ]},
     {type:'subgroup',label:'Tables & Kitchen (KDS)',links:[
      ['/preview/rst-tables','Restaurant Floor Tables'],
      ['/preview/bar-tables','Bar & Lounge Tables'],
      ['/preview/rst-kitchen-board','Kitchen Display Board'],
      ['/preview/rst-kitchen-list','Kitchen Order Queue'],
     ]},
     {type:'subgroup',label:'F&B Sales & Audit',links:[
      ['/preview/rst-sales-list','Restaurant Sales List'],
      ['/preview/rst-report-sales','Restaurant Sales Report'],
      ['/preview/rst-audit-list','Restaurant Night Audit'],
      ['/preview/rst-purchase-create','Kitchen Purchase Order'],
     ]},
    ]},
    {type:'group',icon:'fa-cubes',label:'Kitchen & Store Stock',children:[
     {type:'subgroup',label:'Kitchen Inventory',links:[
      ['/preview/rsi-inventory-report','Kitchen Stock Valuation'],
      ['/preview/rsi-products','Products & Ingredients'],
      ['/preview/rsi-categories','Kitchen Categories'],
      ['/preview/rsi-suppliers','F&B Suppliers'],
      ['/preview/rsi-adjustments','Stock Adjustments'],
      ['/preview/rsi-production-requisitions','Kitchen Requisitions'],
     ]},
     {type:'subgroup',label:'General Store (PO/GRN)',links:[
      ['/preview/gs-stock-in-hand','Store Stock in Hand'],
      ['/preview/gs-items','Store Items Master'],
      ['/preview/gs-purchases','Purchase Orders (PO)'],
      ['/preview/gs-gr-list','Department Requisitions'],
     ]},
    ]},
   ],
  },
  {
   heading:'Finance, BI & Admin',
   items:[
    {type:'group',icon:'fa-money',label:'Accounts & Finance',children:[
     {type:'subgroup',label:'Financial Statements',links:[
      ['/preview/acc-balance-sheet','Balance Sheet & Builder'],
      ['/preview/acc-income-statement','Income Statement (P&L)'],
      ['/preview/acc-trial-balance','Trial Balance Report'],
      ['/preview/acc-cash-flow','Cash Flow Statement'],
      ['/preview/acc-ratio-analysis','Financial Ratio Analysis'],
     ]},
     {type:'subgroup',label:'Vouchers & Ledgers',links:[
      ['/preview/acc-chart-of-account','Chart of Accounts (GL)'],
      ['/preview/acc-account-ledger','General Account Ledger'],
      ['/preview/acc-payments','Payment Vouchers'],
      ['/preview/acc-receives','Receive Vouchers'],
      ['/preview/acc-journals','Journal Vouchers'],
      ['/preview/acc-fund-transfers','Contra & Fund Transfers'],
     ]},
     {type:'subgroup',label:'Gateways & Tax',links:[
      ['/preview/payment-gateway','Payment Gateway (F1)'],
      ['/preview/gateway-reconciliation','Gateway Reconciliation'],
      ['/preview/e-invoicing','Mushak-6.3 E-Invoicing'],
      ['/preview/acc-opening-balances-data','Opening Balances'],
     ]},
    ]},
    {type:'group',icon:'fa-bar-chart',label:'Manager BI & Reports',children:[
     {type:'link',href:'/preview/manager-bi',label:'Manager BI: ADR & RevPAR'},
     {type:'link',href:'/preview/report-expected-arrival',label:'Expected Arrivals'},
     {type:'link',href:'/preview/report-expected-departure',label:'Expected Departures'},
     {type:'link',href:'/preview/report-in-house-guest',label:'In-House Guests'},
     {type:'link',href:'/preview/report-today-check-in',label:'Today Check-In Ledger'},
     {type:'link',href:'/preview/report-vat-report-day',label:'Daily & Monthly VAT'},
     {type:'link',href:'/preview/report-all-reports',label:'All Hotel Reports'},
    ]},
    {type:'group',icon:'fa-globe',label:'Website & Storefront',children:[
     {type:'link',href:'/preview/public-home',label:'Public Hotel Storefront'},
     {type:'link',href:'/preview/public-search-all-room',label:'Public Suite Search'},
     {type:'link',href:'/preview/public-booking-cart',label:'Public Booking Cart'},
     {type:'link',href:'/preview/public-food-menu',label:'Public Dining Menu'},
     {type:'link',href:'/preview/web-banner-index',label:'CMS Homepage Banners'},
     {type:'link',href:'/preview/web-gallery-index',label:'CMS Photo Gallery'},
     {type:'link',href:'/preview/web-pages-index',label:'CMS Content Pages'},
     {type:'link',href:'/preview/web-settings',label:'Website Brand Settings'},
    ]},
    {type:'group',icon:'fa-cogs',label:'System, RBAC & Docs',children:[
     {type:'subgroup',label:'System & RBAC Security',links:[
      ['/preview/system-settings','System Settings'],
      ['/preview/perm-access-create','Role & Permission Matrix'],
      ['/preview/perm-users-index','Permitted Staff Users'],
      ['/preview/audit-compliance','2FA, Audit & Compliance'],
      ['/preview/admin-i18n','Bilingual i18n Manager'],
      ['/preview/import-export-hub','Import / Export Hub'],
      ['/preview/login','Staff Login (W6.5 Auth)'],
     ]},
     {type:'subgroup',label:'Docs, SOP & UI Kit',links:[
      ['/preview/wiki','User Wiki & Help (EN/বাংলা)'],
      ['/preview/sop-print','Printable A4 SOP Sheet'],
      ['/preview/onboarding','Staff Onboarding Hub'],
      ['/preview/ui-kit','UI Component Kit (/ui-kit)'],
      ['/preview/booking-drawers','Slide-Over App Drawers'],
     ]},
    ]},
   ],
  },
 ];

 let subSeq=0,matchedActive=false;
 const htmlParts=[];
 for(const sec of sections){
  htmlParts.push('<li class="mm-nav-section" role="presentation">'+sec.heading+'</li>');
  for(const item of sec.items){
   if(item.type==='link'){
    const isAct=!matchedActive&&(item.aliases||[item.href]).includes(cur);
    if(isAct) matchedActive=true;
    htmlParts.push('<li'+(isAct?' class="active"':'')+'><a href="'+item.href+'"'+(isAct?' aria-current="page"':'')+'><i class="menu-icon fa '+item.icon+'"></i><span class="menu-text">'+item.label+'</span></a><b class="arrow"></b></li>');
   }else if(item.type==='group'){
    let groupActive=false,totalLinks=0;
    const childHtml=item.children.map(ch=>{
     if(ch.type==='link'){
      totalLinks++;
      const isLeafAct=!matchedActive&&ch.href===cur;
      if(isLeafAct){matchedActive=true;groupActive=true;}
      return '<li'+(isLeafAct?' class="active"':'')+'><a href="'+ch.href+'"'+(isLeafAct?' aria-current="page"':'')+'><span class="menu-text">'+ch.label+'</span></a><b class="arrow"></b></li>';
     }
     totalLinks+=ch.links.length;
     let subActive=false;
     const l3Html=ch.links.map(([href,lbl])=>{
      const isL3Act=!matchedActive&&href===cur;
      if(isL3Act){matchedActive=true;subActive=true;groupActive=true;}
      return '<li'+(isL3Act?' class="active"':'')+'><a href="'+href+'"'+(isL3Act?' aria-current="page"':'')+'><span class="menu-text">'+lbl+'</span></a><b class="arrow"></b></li>';
     }).join('');
     const sId='mm-prev-sub-'+(++subSeq);
     return '<li class="'+(subActive?'open active':'')+'"><a href="#" class="dropdown-toggle" aria-expanded="'+(subActive?'true':'false')+'"><span class="menu-text">'+ch.label+'</span><span class="mm-nav-count">'+ch.links.length+'</span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b><ul class="submenu'+(subActive?' nav-show':'')+'" id="'+sId+'"'+(subActive?' style="display:block"':'')+'>'+l3Html+'</ul></li>';
    }).join('');
    const gId='mm-prev-sub-'+(++subSeq);
    htmlParts.push('<li class="'+(groupActive?'open active':'')+'"><a href="#" class="dropdown-toggle" aria-expanded="'+(groupActive?'true':'false')+'"><i class="menu-icon fa '+item.icon+'"></i><span class="menu-text">'+item.label+'</span><span class="mm-nav-count">'+totalLinks+'</span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b><ul class="submenu'+(groupActive?' nav-show':'')+'" id="'+gId+'"'+(groupActive?' style="display:block"':'')+'>'+childHtml+'</ul></li>');
   }
  }
 }
 const navScript='<script>(function(){var m=document.getElementById("mm-primary-menu");if(!m||m.dataset.mmPrevBound)return;m.dataset.mmPrevBound="1";m.addEventListener("click",function(e){var a=e.target&&e.target.closest("a.dropdown-toggle");if(!a||!m.contains(a))return;e.preventDefault();e.stopPropagation();var li=a.parentElement,sub=li&&li.querySelector(":scope > ul.submenu"),parentUl=li&&li.parentElement;if(!li||!sub)return;var isOpen=li.classList.contains("open")||sub.style.display==="block";if(!isOpen&&parentUl){Array.from(parentUl.children).forEach(function(sib){if(sib!==li&&sib.classList.contains("open")){sib.classList.remove("open");var sSub=sib.querySelector(":scope > ul.submenu"),sA=sib.querySelector(":scope > a.dropdown-toggle");if(sSub){sSub.classList.remove("nav-show");sSub.style.display="none";}if(sA)sA.setAttribute("aria-expanded","false");}});}li.classList.toggle("open",!isOpen);sub.classList.toggle("nav-show",!isOpen);sub.style.display=isOpen?"none":"block";a.setAttribute("aria-expanded",String(!isOpen));},true);})();</script>';
 return page.replace(/<ul class="nav nav-list" id="mm-primary-menu"[^>]*>[\s\S]*?<\/ul>(?=<\/div>)/,'<ul class="nav nav-list" id="mm-primary-menu" aria-label="Main navigation">'+htmlParts.join('')+hiddenAll+'</ul>').replace('</body>',navScript+'</body>');
}
const pubPages={'/preview/public-home':'frontend/home.html','/preview/public-rooms':'frontend/room_view.html','/preview/public-room-view':'frontend/room_view.html','/preview/public-booking-cart':'frontend/booking_cart.html','/preview/public-search-all-room':'frontend/search_all_room.html','/preview/public-search-room':'frontend/search_room.html','/preview/public-guest-register':'frontend/guest_register.html','/preview/public-booking-register':'frontend/booking_register.html','/preview/public-food-menu':'frontend/food_menu.html','/preview/public-bar-menu':'frontend/bar_menu.html','/preview/public-terms':'frontend/terms.html','/preview/public-privacy-policy':'frontend/privacy_policy.html','/preview/public-single-page-view':'frontend/single_page_view.html'};
function pubScreen(route){
 const bar='<div style="padding:8px 16px;background:#0f172a;color:#e2e8f0;font:600 12px/1.4 system-ui,sans-serif;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px;border-bottom:1px solid rgba(148,163,184,0.25)"><div><strong>M&amp;M Heritage Hotel · Public Storefront (W7)</strong></div><div style="display:flex;flex-wrap:wrap;gap:10px"><a href="/preview/public-home" style="color:#93c5fd;text-decoration:none">Home</a><a href="/preview/public-rooms" style="color:#93c5fd;text-decoration:none">Room View</a><a href="/preview/public-search-all-room" style="color:#93c5fd;text-decoration:none">All Rooms</a><a href="/preview/public-booking-cart" style="color:#93c5fd;text-decoration:none">Booking Cart</a><a href="/preview/public-guest-register" style="color:#93c5fd;text-decoration:none">Guest Register</a><a href="/preview/public-food-menu" style="color:#93c5fd;text-decoration:none">Food Menu</a><a href="/preview/public-bar-menu" style="color:#93c5fd;text-decoration:none">Bar Menu</a><a href="/preview/web-banner-index" style="color:#fcd34d;text-decoration:none">CMS Admin →</a></div></div>';
 return fix(pubPages[route]).replace(/<body([^>]*)>/i,'<body$1>'+bar);
}
const defRoute=(()=>{const raw=process.env.DEFAULT_PATH||process.env.DEFAULT_SCREEN||(process.env.DEFAULT_PREVIEW?'/preview/'+process.env.DEFAULT_PREVIEW.replace(/^\/?(preview\/)?/,''):'');if(raw==='/preview'||raw==='preview')return '/preview';const d=raw?('/preview/'+raw.replace(/^\/?(preview\/)?/,'')):'';return (pages[d]||pubPages[d]||specialPages[d])?d:'';})();
function renderDashboardPage(url){
 let page=compose('dashboard.html').replace(/<li><span>Hotel<\/span><\/li>\s*<li><span>Booking<\/span><\/li>\s*<li><span aria-current="page">Create<\/span><\/li>/,'<li><span aria-current="page">Dashboard</span></li>');
 const picked=url.searchParams.get('booking_date');
 if(picked&&/^\d\d\/\d\d\/\d{4} - \d\d\/\d\d\/\d{4}$/.test(picked)){
  page=page.replace(/(name="booking_date" id="available_date" value=")[^"]*"/,'$1'+picked+'"').replace(/(name="booking_availabe" value=")[^"]*"/,'$1'+picked+'"');
 }
 const dashBanner='<div role="note" id="dash-ops-strip" style="padding:12px 16px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a;border-radius:10px;display:flex;flex-direction:column;gap:10px"><div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:8px"><div><strong><i class="fa fa-tachometer"></i> Front-Desk Command Center (Core Workflow #1)</strong> · <span class="mm-badge mm-badge-paid">Business Date: 2026-10-14 · Night Audit: Open</span> <span class="mm-badge mm-badge-partial">8 Arrivals · 4 Departures · 1 Credit Alert</span> — <a href="/preview">All 360 Screens</a></div><div style="display:flex;flex-wrap:wrap;gap:6px"><button type="button" data-mm-theme-toggle aria-pressed="false" aria-label="Dark theme" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-moon-o"></i> Dark / Light</button><a href="/preview/plan-a" class="mm-button" style="min-height:30px;padding:3px 10px"><i class="fa fa-sitemap"></i> Plan A (9/9)</a><a href="/preview/plan-b" class="mm-button" style="min-height:30px;padding:3px 10px"><i class="fa fa-rocket"></i> Plan B (34/34)</a><a href="/preview/room-settings" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-sliders"></i> Room Settings</a><a href="/preview/folio-settlement" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-columns"></i> Split-Folio</a><a href="/preview/pos-unified" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-cutlery"></i> Unified POS</a><a href="/preview/rooms" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-bed"></i> 20 Rooms</a><a href="/preview/tape-chart" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-calendar"></i> Tape Chart</a><a href="/preview/booking-next" class="mm-button mm-button-secondary" style="min-height:30px;padding:3px 10px"><i class="fa fa-calendar-plus-o"></i> Wizard</a></div></div></div>';
 return page.replace('<section class="mm-ui mm-dashboard">',dashBanner+'<section class="mm-ui mm-dashboard">');
}
http.createServer((req,res)=>{
 const url=new URL(req.url,'http://preview');
 const isStatic=url.pathname.startsWith('/assets/')||url.pathname.startsWith('/frontend/')||url.pathname.startsWith('/food_menu/');
 if(!isStatic){
  try{
   res.writeHead(200,{'Content-Type':types['.html'],'Cache-Control':'no-store'});
   const p=url.pathname.replace(/\/+$/,'')||'/';
   let page,resolvedPath=p;
   if(req.method!=='GET'&&req.method!=='HEAD') page=listPage('Form submitted. This is a sample preview, so nothing was saved.');
   else if(p==='/'&&defRoute){resolvedPath=defRoute;if(defRoute==='/preview')page=listPage();else if(pubPages[defRoute])return res.end(pubScreen(defRoute));else if(specialPages[defRoute])page=specialPages[defRoute].fn();else page=screen(defRoute);}
   else if(pubPages[p]) return res.end(pubScreen(p));
   else if(specialPages[p]) page=specialPages[p].fn();
   else if(p==='/ui-kit'){resolvedPath='/preview/ui-kit';page=uiKitPage();}
   else if(p==='/wiki'||p==='/help'){resolvedPath='/preview/wiki';page=wikiPage();}
   else if(p==='/sop-print'){resolvedPath='/preview/sop-print';page=sopPrintPage();}
   else if(p==='/onboarding'){resolvedPath='/preview/onboarding';page=onboardingPage();}
   else if(p==='/tape-chart'){resolvedPath='/preview/tape-chart';page=tapeChartPage();}
   else if(p==='/rooms'||p==='/hotel/rooms'||p==='/website/rooms'){resolvedPath='/preview/rooms';page=roomsDirectoryPage();}
   else if(p==='/guests'||p==='/hotel/guests'){resolvedPath='/preview/guests';page=guestsDirectoryPage();}
   else if(p==='/bookings'||p==='/hotel/booking'||p==='/hotel/bookings'||p==='/preview/booking-list'){resolvedPath='/preview/bookings';page=bookingsDirectoryPage();}
   else if(p==='/hotel/house-keeping'){resolvedPath='/preview/housekeeping-board';page=housekeepingBoardPage();}
   else if(p==='/report/expected-arrival'||p==='/hotel/reports/expected-arrival'){resolvedPath='/preview/report-expected-arrival';page=screen('/preview/report-expected-arrival');}
   else if(p==='/report/expected-departure'||p==='/hotel/reports/expected-departure'){resolvedPath='/preview/report-expected-departure';page=screen('/preview/report-expected-departure');}
   else if(p==='/report/in-house-guest'||p==='/hotel/reports/in-house-guest'){resolvedPath='/preview/report-in-house-guest';page=screen('/preview/report-in-house-guest');}
   else if(p==='/login'||p==='/logout'){resolvedPath='/preview/login';page=loginPage();}
   else if(p==='/password'){resolvedPath='/preview/perm-change-password';page=screen('/preview/perm-change-password');}
   else if(p==='/notifications'){resolvedPath='/preview/news-notices';page=newsNoticesPage();}
   else if(p==='/system-settings'){resolvedPath='/preview/system-settings';page=systemSettingsPage();}
   else if(p==='/'||p==='/home'||p==='/dashboard'||p==='/preview/dashboard'){resolvedPath='/dashboard';page=renderDashboardPage(url);}
   else if(p==='/hotel/booking/booking-next'||p==='/hotel/booking/create'){resolvedPath='/preview/booking-next';page=screen('/preview/booking-next');}
   else if(p==='/restaurant'||p==='/restaurant/sales-v2/create'||p==='/rst/sales-v2/create'||p==='/pos'){resolvedPath='/preview/rst-pos';page=screen('/preview/rst-pos');}
   else if(p==='/bar'||p==='/bar/sales-v2/create'||p==='/bar-pos'){resolvedPath='/preview/bar-pos';page=screen('/preview/bar-pos');}
   else if(p==='/restaurant/sales'||p==='/restaurant/sales/create'||p==='/rst/sales/create'){resolvedPath='/preview/rst-sales-list';page=screen('/preview/rst-sales-list');}
   else if(p==='/restaurant/products'){resolvedPath='/preview/rsi-products';page=screen('/preview/rsi-products');}
   else if(p==='/bar/sales'||p==='/bar/sales/create'){resolvedPath='/preview/bar-sales-create';page=screen('/preview/bar-sales-create');}
   else if(p==='/bar/stock'){resolvedPath='/preview/bar-report-inventory';page=screen('/preview/bar-report-inventory');}
   else if(p==='/banquet/reservations'||p==='/banquet/packages'){resolvedPath='/preview/bq-booking-index';page=screen('/preview/bq-booking-index');}
   else if(p==='/services'||p==='/services/charges'){resolvedPath='/preview/hservice-services';page=screen('/preview/hservice-services');}
   else if(p==='/store/purchases'){resolvedPath='/preview/gs-purchases';page=screen('/preview/gs-purchases');}
   else if(p==='/store/requisitions'||p==='/preview/gs-requisitions'){resolvedPath='/preview/gs-gr-list';page=screen('/preview/gs-gr-list');}
   else if(p==='/preview/admin-2fa'){resolvedPath='/preview/audit-compliance';page=specialPages['/preview/audit-compliance'].fn();}
   else if(p==='/preview/perm-access'){resolvedPath='/preview/perm-access-create';page=screen('/preview/perm-access-create');}
   else if(p==='/finance/ledger'){resolvedPath='/preview/acc-account-ledger';page=screen('/preview/acc-account-ledger');}
   else if(p==='/finance/payments'){resolvedPath='/preview/acc-payments';page=screen('/preview/acc-payments');}
   else if(p==='/website/pages'){resolvedPath='/preview/web-pages-index';page=screen('/preview/web-pages-index');}
   else if(p==='/groups'){resolvedPath='/preview/global-groups';page=globalGroupsPage();}
   else if(p==='/companies'){resolvedPath='/preview/global-companies';page=globalCompaniesPage();}
   else if(p==='/permissions'){resolvedPath='/preview/perm-access-create';page=screen('/preview/perm-access-create');}
   else if(p==='/permissions/users'){resolvedPath='/preview/perm-users-index';page=screen('/preview/perm-users-index');}
   else if(p==='/hotel/booking-purpose'){resolvedPath='/preview/room-settings';page=specialPages['/preview/room-settings'].fn();}
   else if(pages[p]) page=screen(p);
   else if(p==='/preview') page=listPage();
   else page=listPage('“'+p.replace(/[<>&"]/g,'')+'” is not part of this visual preview.');
   return res.end(menuLinks(page,resolvedPath));
  }catch(e){res.writeHead(500);return res.end(String(e));}
 }
 const file=path.resolve(root,'public','.'+decodeURIComponent(url.pathname));
 if(!file.startsWith(root+'/public/assets/')&&!file.startsWith(root+'/public/frontend/')&&!file.startsWith(root+'/public/food_menu/')){res.writeHead(403);return res.end();}
 fs.readFile(file,(err,data)=>{
  if(err){res.writeHead(404);return res.end('Not found');}
  const ext=path.extname(file);
  const hdrs={'Content-Type':types[ext]||'application/octet-stream','Cache-Control':'no-store'};
  if(ext==='.pdf'){
   hdrs['Content-Disposition']=(url.searchParams.get('download')==='1'?'attachment':'inline')+'; filename="MM-Heritage-Draft-Booking-Summary.pdf"';
   hdrs['Content-Length']=String(data.length);
  }
  res.writeHead(200,hdrs);res.end(data);
 });
}).listen(Number(process.env.PORT)||3000,'0.0.0.0');
