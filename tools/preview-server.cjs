const http=require('http'),fs=require('fs'),path=require('path');
const root=path.resolve(__dirname,'..');
const {compose}=require(path.join(root,'tools/browser/compose-fixture.cjs'));
const types={'.html':'text/html; charset=utf-8','.css':'text/css','.js':'text/javascript','.woff2':'font/woff2','.woff':'font/woff','.ttf':'font/ttf','.png':'image/png','.svg':'image/svg+xml'};
const note='<div role="note" style="padding:12px;margin:0 0 16px;border:1px solid #c5d7ee;background:#edf4ff;color:#24436a"><strong>Visual preview \u00b7 sample data only</strong><br>Admin shell, header, footer and room board design. Not connected to Laravel, bookings or the hotel database. Menu destinations are demonstration links.</div>';

const fix=n=>fs.readFileSync(path.join(root,'tools/fixtures',n),'utf8');
const pages={
 '/preview/checkout':{file:'preview/checkout.html',title:'Checkout and payment',crumbs:['Hotel','Booking','Checkout']},
 '/preview/payment-collection':{file:'preview/payment-collection.html',title:'Payment collection',crumbs:['Hotel','Booking','Payment collection']},
 '/preview/invoice':{file:'preview/invoice.html',title:'Booking invoice',crumbs:['Hotel','Booking','Invoice']},
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
 const items=[['/','Dashboard and room booking board','Room board with the stay date range, quick chips and summary cards.'],...Object.entries(pages).map(([p,v])=>[p,v.title,p==='/preview/checkout'?'Night +/- recalculates totals, discount, paid amount and full payment (real calculation script).':p==='/preview/payment-collection'?'Search a guest, review unpaid invoices, enter a payment or tick full payment (real script).':p==='/preview/invoice'?'Printable booking invoice; the Print button prints the document only.':'Real category form with sample amenities.'])];
 const body=(extra?'<div class="mm-panel tw-p-4" style="margin-bottom:16px"><strong>'+extra+'</strong></div>':'')+'<section class="mm-ui"><div class="mm-panel tw-p-5"><h2 style="margin-top:0">Screens available in this preview</h2><p>This preview is a static sample, not the Laravel app, so only the screens below are wired up. Other menu items are demonstration links.</p><ul style="line-height:2">'+items.map(([p,t,d])=>'<li><a href="'+p+'"><strong>'+t+'</strong></a> — '+d+'</li>').join('')+'</ul></div></section>';
 return shellWith(body,'','','Screens');
}
function menuLinks(page){
 const nav='<li><a href="/preview"><i class="menu-icon fa fa-eye"></i><span class="menu-text">Preview screens</span></a><b class="arrow"></b></li><li><a href="/preview/checkout"><i class="menu-icon fa fa-credit-card"></i><span class="menu-text">Checkout and payment</span></a><b class="arrow"></b></li><li><a href="/preview/payment-collection"><i class="menu-icon fa fa-money"></i><span class="menu-text">Payment collection</span></a><b class="arrow"></b></li><li><a href="/preview/invoice"><i class="menu-icon fa fa-print"></i><span class="menu-text">Booking invoice</span></a><b class="arrow"></b></li><li><a href="/preview/category-create"><i class="menu-icon fa fa-bed"></i><span class="menu-text">Add room category</span></a><b class="arrow"></b></li>';
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
