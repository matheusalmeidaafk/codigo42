import {test} from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import fs from 'node:fs';
const source = name => fs.readFileSync(new URL('../web/public/assets/js/'+name+'.js', import.meta.url),'utf8');
function storage(){ const data=new Map();return {getItem:k=>data.get(k)??null,setItem:(k,v)=>data.set(k,String(v)),removeItem:k=>data.delete(k)}; }
function element(){return {textContent:'',innerHTML:'',style:{},classList:{add(){},remove(){},toggle(){}},appendChild(){},append(){},addEventListener(){},setAttribute(){}};}
function context(){const nodes=new Map(); const events={}; const ctx={URL,URLSearchParams,Date,JSON,Number,Math,Set,console,atob,sessionStorage:storage(),localStorage:storage(),location:{protocol:'http:',hostname:'local',origin:'http://local',pathname:'/pages/home.php',search:''},alert(){},confirm:()=>true,document:{readyState:'complete',getElementById:id=>nodes.get(id)||null,querySelectorAll:()=>[],createElement:element,addEventListener:(n,f)=>{events[n]=f}}};ctx.window=ctx;ctx.addEventListener=()=>{};return {ctx:vm.createContext(ctx),nodes,events};}
test('Retorno aceita páginas locais e rejeita destinos externos ou login em loop',()=>{
 const {ctx}=context();vm.runInContext(source('auth'),ctx);
 for(const input of ['https://evil.example/','//evil.example/','/pages/acesso.php','javascript:alert(1)'])assert.equal(ctx.Codigo42Auth.returnUrl(input),'/pages/home.php');
 assert.equal(ctx.Codigo42Auth.returnUrl('/pages/carrinho.php'),'/pages/carrinho.php');
 assert.equal(ctx.Codigo42Auth.returnUrl('/pages/produto.php?id=7'),'/pages/produto.php?id=7');
});
test('Sessão expirada é removida',()=>{
 const {ctx}=context();const token='x.'+Buffer.from(JSON.stringify({tipo:'USER',exp:1})).toString('base64url')+'.x';ctx.sessionStorage.setItem('codigo42_auth',JSON.stringify({token}));vm.runInContext(source('auth'),ctx);assert.equal(ctx.Codigo42Auth.current(),null);assert.equal(ctx.sessionStorage.getItem('codigo42_auth'),null);
});
test('Carrinho converte preços, agrupa variações, limita estoque e persiste remoções',()=>{
 const {ctx,events,nodes}=context();for(const id of ['carrinhoItensLista','carrinhoSubtotal','carrinhoTotalItens','carrinhoDesconto'])nodes.set(id,element());vm.runInContext(source('carrinho'),ctx);events.DOMContentLoaded();
 assert.equal(ctx.addToCart({id:'1:7',name:'Camiseta',price:'10',oldPrice:'12',stock:3,quantity:2}),true);
 assert.equal(ctx.addToCart({id:'1:7',name:'Camiseta',price:'10',stock:3,quantity:2}),false);
 ctx.updateItemQuantity(0,1);ctx.updateItemQuantity(0,1);
 let saved=JSON.parse(ctx.localStorage.getItem('ecommerce_cart'));assert.equal(saved[0].quantity,3);assert.equal(saved[0].price,10);assert.match(nodes.get('carrinhoSubtotal').textContent,/30,00/);
 ctx.addToCart({id:'1:8',name:'Outra cor',price:20,stock:2,quantity:1});assert.equal(JSON.parse(ctx.localStorage.getItem('ecommerce_cart')).length,2);
 ctx.removeItem(0);saved=JSON.parse(ctx.localStorage.getItem('ecommerce_cart'));assert.equal(saved[0].id,'1:8');ctx.removeItem(0);assert.equal(ctx.localStorage.getItem('ecommerce_cart'),'[]');
});
test('Carrinho tolera conteúdo persistido inválido',()=>{
 const {ctx,events}=context();ctx.localStorage.setItem('ecommerce_cart','{"invalid":true}');vm.runInContext(source('carrinho'),ctx);events.DOMContentLoaded();ctx.addToCart({id:'2',price:5});assert.equal(JSON.parse(ctx.localStorage.getItem('ecommerce_cart')).length,1);
});
