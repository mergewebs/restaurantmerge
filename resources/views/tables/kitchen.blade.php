<x-app-layout>
    <style>
        .kds { color:#362a22; }
        .kds h1,.kds h2,.kds h3,.kds strong { color:inherit; }
        .kds-panel { background:#fffdf9; border:1px solid #e6d9cc; box-shadow:0 8px 24px rgba(73,47,31,.06); }
        .kds-ticket { background:#fff; border:1px solid #e5d8ca; box-shadow:0 8px 20px rgba(73,47,31,.07); }
        .kds-ticket.urgent { border-color:#ef4444; box-shadow:0 0 0 1px rgba(239,68,68,.2),0 12px 30px rgba(0,0,0,.25); }
        .kds-scroll::-webkit-scrollbar { width:7px; }.kds-scroll::-webkit-scrollbar-thumb { background:#d4c2b2; border-radius:99px; }
        .kds .kds-title { color:#362a22!important; }
        .kds .kds-muted { color:#8c7b6e!important; }
        .kds .station-active { border-color:#bd5738; background:#bd5738; color:#fff!important; }
        .kds .station-inactive { border-color:#dfd3c8; background:#fff; color:#6f6054!important; }
        .kds .station-inactive:hover { background:#f8efe6; border-color:#cdb8a7; }
        .kds .wait-normal { background:#f1ece6; color:#59493e; }
        .kds .wait-warning { background:#f59e0b; color:#2f251d; }
        .kds .wait-urgent { background:#dc2626; color:#fff; }
        .kds .item-pending { border-color:#e4d8cd; background:#faf7f3; color:#382c24; }
        .kds .item-preparing { border-color:#f3bd55; background:#fff8e7; color:#5e4215; }
        .kds .item-ready { border-color:#75c892; background:#edf9f1; color:#205d36; }
        .kds .station-kitchen { background:#fff0e8; color:#ae4d2f; }.kds .station-bar { background:#f3eefe; color:#7350a6; }.kds .station-desserts { background:#fff0f5; color:#a84268; }.kds .station-drinks { background:#eaf7fd; color:#28789b; }
        @keyframes newPulse { 50% { opacity:.6; } }.kds-new { animation:newPulse 1.5s ease-in-out infinite; }
    </style>

    <div class="kds -m-3 min-h-[calc(100vh-5rem)] bg-[#f6efe6] p-3 sm:-m-5 sm:p-4" x-data="kitchenDisplay()" x-init="init()">
        <header class="kds-panel sticky top-1 z-30 rounded-2xl p-3 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#bd5738] text-xl text-white">♨</div>
                    <div><h1 class="kds-title text-lg font-black uppercase tracking-wide sm:text-xl">Kitchen Display</h1><p class="kds-muted text-[11px] font-bold"><span class="mr-1 inline-block h-2 w-2 rounded-full" :class="connected?'bg-emerald-500':'bg-amber-500'"></span><span x-text="connected ? 'Live kitchen updates' : 'Reconnecting…'"></span></p></div>
                </div>
                <div class="flex items-center gap-2"><span class="rounded-lg bg-[#f1e8df] px-3 py-2 text-xs font-black text-[#59483c]"><span x-text="visibleOrders.length"></span> tickets</span><a href="{{ route('tables.index') }}" class="rounded-lg border border-[#dacbbd] bg-white px-3 py-2 text-xs font-black text-[#59483c]">Tables</a></div>
            </div>
            <div class="mt-3 flex gap-1.5 overflow-x-auto pb-0.5">
                <template x-for="option in stations" :key="option.value"><button type="button" @click="station=option.value" class="whitespace-nowrap rounded-lg border px-3 py-2 text-xs font-black uppercase tracking-wide transition" :class="station===option.value?'station-active':'station-inactive'" x-text="option.label + ' ' + stationCount(option.value)"></button></template>
            </div>
        </header>

        <div x-show="newAlert" x-transition class="mt-3 rounded-xl border border-orange-400 bg-orange-500/20 px-4 py-3 text-sm font-black text-orange-100">New kitchen ticket received</div>

        <section x-show="visibleOrders.length===0" class="kds-panel mt-3 rounded-2xl px-5 py-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f5e8dc] text-2xl text-[#bd5738]">✓</div>
            <h2 class="kds-title mt-3 text-lg font-black">Kitchen is clear</h2>
            <p class="kds-muted mt-1 text-sm font-semibold" x-text="station==='all' ? 'New tickets will appear here automatically.' : 'No active tickets for this station.'"></p>
        </section>

        <main x-show="visibleOrders.length>0" class="mt-3 grid gap-3 xl:grid-cols-3">
            <template x-for="lane in lanes" :key="lane.status">
                <section class="kds-panel rounded-2xl p-2.5">
                    <div class="mb-2 flex items-center justify-between px-1 py-1">
                        <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full" :class="lane.dot"></span><h2 class="text-sm font-black uppercase tracking-widest" x-text="lane.label"></h2></div>
                        <span class="rounded-full bg-[#f1e8df] px-2.5 py-1 text-xs font-black text-[#59483c]" x-text="ordersFor(lane.status).length"></span>
                    </div>
                    <div class="kds-scroll grid max-h-[calc(100vh-13rem)] gap-2.5 overflow-y-auto pr-0.5 md:grid-cols-2 xl:grid-cols-1">
                        <template x-for="order in ordersFor(lane.status)" :key="order.id">
                            <article class="kds-ticket overflow-hidden rounded-xl" :class="waitMinutes(order)>=20?'urgent':''">
                                <div class="flex items-start justify-between gap-2 border-b border-[#eadfd5] p-3">
                                    <div><div class="flex flex-wrap items-center gap-2"><h3 class="text-lg font-black" x-text="order.source_label"></h3><span x-show="waitMinutes(order)<3 && order.kitchen_status==='pending'" class="kds-new rounded-full bg-orange-500 px-2 py-0.5 text-[9px] font-black uppercase">New</span></div><p class="mt-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-400" x-text="orderTypeLabel(order.order_type)+' · Order #'+order.id"></p></div>
                                    <div class="rounded-lg px-2.5 py-1.5 text-right" :class="waitTone(order)"><strong class="block text-lg leading-none" x-text="waitMinutes(order)+'m'"></strong><span class="text-[8px] font-black uppercase">waiting</span></div>
                                </div>

                                <div x-show="order.notes" class="mx-3 mt-3 rounded-lg border border-amber-400 bg-amber-50 px-3 py-2 text-xs font-black text-amber-900"><span class="mr-1">NOTE:</span><span x-text="order.notes"></span></div>
                                <div x-show="orderAllergies(order).length" class="mx-3 mt-2 rounded-lg border-2 border-red-500 bg-red-50 px-3 py-2 text-sm font-black text-red-800"><span class="mr-1">⚠ ALLERGY:</span><span x-text="orderAllergies(order).join(', ')"></span></div>

                                <div class="space-y-1.5 p-3">
                                    <template x-for="item in itemsFor(order)" :key="item.id">
                                        <button type="button" @click="advanceItem(item)" class="w-full rounded-lg border p-2.5 text-left transition" :class="itemTone(item.status)">
                                            <div class="flex items-start gap-2"><span class="min-w-8 rounded-md bg-black/10 px-1.5 py-1 text-center text-sm font-black" x-text="item.quantity+'×'"></span><div class="min-w-0 flex-1"><div class="flex items-start justify-between gap-2"><strong class="text-sm leading-tight" x-text="item.name"></strong><span class="rounded px-1.5 py-0.5 text-[8px] font-black uppercase tracking-wide" :class="stationTone(item.station)" x-text="item.station"></span></div><p x-show="item.note" class="mt-1 text-xs font-black text-amber-800" x-text="'↳ '+item.note"></p><p x-show="item.allergens" class="mt-1 text-[11px] font-black text-red-700" x-text="'⚠ '+item.allergens"></p></div></div>
                                        </button>
                                    </template>
                                </div>

                                <div class="border-t border-[#eadfd5] p-3">
                                    <button x-show="order.kitchen_status==='pending'" @click="setOrderStatus(order,'in_preparation')" class="w-full rounded-lg bg-amber-500 py-3 text-sm font-black uppercase text-slate-950">Start preparing</button>
                                    <button x-show="order.kitchen_status==='in_preparation'" @click="setOrderStatus(order,'ready')" class="w-full rounded-lg bg-emerald-500 py-3 text-sm font-black uppercase text-slate-950">Mark ready</button>
                                    <button x-show="order.kitchen_status==='ready'" @click="setOrderStatus(order,'completed')" class="w-full rounded-lg bg-sky-500 py-3 text-sm font-black uppercase text-white">Complete ticket</button>
                                </div>
                            </article>
                        </template>
                        <div x-show="ordersFor(lane.status).length===0" class="rounded-xl border border-dashed border-[#dfd3c8] bg-[#faf7f3] p-6 text-center text-xs font-bold text-[#9a897c]" x-text="'No '+lane.label.toLowerCase()+' tickets'"></div>
                    </div>
                </section>
            </template>
        </main>
    </div>

    <script>
        function kitchenDisplay() {
            return {
                orders: @json($initialOrders), version: Number(@json($initialKitchenVersion)), station: 'all', connected: true, newAlert: false, timer: null, clock: Date.now(),
                stations: [{value:'all',label:'All stations'},{value:'kitchen',label:'Kitchen'},{value:'bar',label:'Bar'},{value:'desserts',label:'Desserts'},{value:'drinks',label:'Drinks'}],
                lanes: [{status:'pending',label:'New orders',dot:'bg-orange-400'},{status:'in_preparation',label:'Preparing',dot:'bg-amber-400'},{status:'ready',label:'Ready',dot:'bg-emerald-400'}],
                init() { this.normalize(); this.timer=setInterval(()=>this.refresh(),4000); setInterval(()=>this.clock=Date.now(),30000); },
                normalize() { this.orders=(this.orders||[]).map(o=>({...o,items:(o.items||[]).map(i=>({...i,station:i.station||'kitchen'}))})); },
                get visibleOrders() { return this.orders.filter(o=>this.station==='all'||o.items.some(i=>i.station===this.station)); },
                itemsFor(order) { return this.station==='all' ? order.items : order.items.filter(i=>i.station===this.station); },
                ordersFor(status) { return this.visibleOrders.filter(o=>o.kitchen_status===status); },
                stationCount(value) { return value==='all' ? this.orders.length : this.orders.filter(o=>o.items.some(i=>i.station===value)).length; },
                waitMinutes(order) { this.clock; const time=new Date(order.created_at).getTime(); return Number.isFinite(time)?Math.max(0,Math.floor((Date.now()-time)/60000)):0; },
                waitTone(order) { const m=this.waitMinutes(order); return m>=20?'wait-urgent':(m>=10?'wait-warning':'wait-normal'); },
                orderTypeLabel(type) { return ({dine_in:'Dine-in',takeaway:'Takeaway',pickup:'Pickup',delivery:'Delivery'})[type]||'Dine-in'; },
                orderAllergies(order) { return [...new Set(order.items.flatMap(i=>(i.allergens||'').split(',')).map(v=>v.trim()).filter(Boolean))]; },
                itemTone(status) { return status==='ready'?'item-ready':(status==='in_preparation'?'item-preparing':'item-pending'); },
                stationTone(station) { return ({kitchen:'station-kitchen',bar:'station-bar',desserts:'station-desserts',drinks:'station-drinks'})[station]||'station-kitchen'; },
                async refresh(force=false) { try { const res=await fetch(`{{ route('tables.kitchen.snapshot') }}?since_version=${force?0:this.version}`,{headers:{Accept:'application/json'}}); if(!res.ok) throw Error(); const data=await res.json(); this.connected=true; if(data.changed){const previous=new Set(this.orders.map(o=>o.id));this.orders=data.orders;this.version=Number(data.version);this.normalize();if(this.orders.some(o=>!previous.has(o.id))){this.newAlert=true;setTimeout(()=>this.newAlert=false,3500);}} } catch(e){this.connected=false;} },
                async patch(url,status) { const res=await fetch(url,{method:'PATCH',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({status})}); if(!res.ok) throw Error('Update failed'); await this.refresh(true); },
                async setOrderStatus(order,status) { try { await this.patch(@json(route('tables.orders.kitchen-status.update',['order'=>'__ID__'])).replace('__ID__',order.id),status); } catch(e){this.connected=false;} },
                async advanceItem(item) { const next=item.status==='pending'?'in_preparation':(item.status==='in_preparation'?'ready':'pending'); try { await this.patch(@json(route('tables.items.kitchen-status.update',['item'=>'__ID__'])).replace('__ID__',item.id),next); } catch(e){this.connected=false;} }
            }
        }
    </script>
</x-app-layout>
