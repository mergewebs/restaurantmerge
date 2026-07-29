<x-app-layout>
@php
    $allProducts = $menuItems->flatten();
    $editingId = old('editing_product_id') ?: session('edit_menu_item_id');
    $editingProductOnLoad = $editingId ? $allProducts->firstWhere('id', (int) $editingId) : null;
    $availableCount = $allProducts->where('availability_status', 'available')->where('is_active', true)->count();
    $soldOutCount = $allProducts->where('availability_status', 'sold_out')->count();
    $lowStockCount = $allProducts->filter(fn ($item) => $item->stock_quantity !== null && $item->stock_quantity <= $item->low_stock_threshold)->count();
    $productPayload = fn ($item) => [
        'id' => $item->id,
        'name' => $item->name,
        'category' => $item->category,
        'description' => $item->description ?? '',
        'ingredients' => $item->ingredients ?? '',
        'allergens' => $item->allergens ?? '',
        'price' => (string) $item->price,
        'dine_in_price' => $item->dine_in_price === null ? '' : (string) $item->dine_in_price,
        'takeaway_price' => $item->takeaway_price === null ? '' : (string) $item->takeaway_price,
        'delivery_price' => $item->delivery_price === null ? '' : (string) $item->delivery_price,
        'stock_quantity' => $item->stock_quantity,
        'low_stock_threshold' => $item->low_stock_threshold,
        'availability_status' => $item->availability_status,
        'item_kind' => $item->item_kind,
        'station' => $item->station,
        'sort_order' => $item->sort_order,
        'is_active' => (bool) $item->is_active,
        'image_url' => $item->image_url,
        'update_url' => route('menu-items.update', $item),
        'delete_url' => route('menu-items.destroy', $item),
        'options_url' => route('menu-items.options.store', $item),
        'options' => $item->options->map(fn ($option) => [
            'id' => $option->id,
            'type' => $option->type,
            'name' => $option->name,
            'price_adjustment' => (string) $option->price_adjustment,
            'destroy_url' => route('menu-items.options.destroy', [$item, $option]),
        ])->values(),
    ];
@endphp

<div data-async-region="menu-items-index" data-async-mutations="true">
    @if(session('success'))<div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>@endif

    <style>
        .admin-service-heading{display:none!important}.menu-page { --ink:#30261f; --muted:#8d7e72; --line:#eadfd2; --paper:#fffdf9; --cream:#f8f1e7; --spice:#bd5738; }
        .menu-page .menu-card { background:var(--paper); border:1px solid var(--line); box-shadow:0 8px 24px rgba(66,45,31,.05); }
        .menu-page .eyebrow { color:var(--spice); font-size:.61rem; font-weight:900; letter-spacing:.2em; text-transform:uppercase; }
        .menu-page .field-label { display:block; margin-bottom:.28rem; color:#8a796b; font-size:.62rem; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
        .menu-page input:not([type=checkbox]):not([type=file]), .menu-page select, .menu-page textarea { width:100%; border:1px solid #e7ddd2; border-radius:.75rem; background:#fff; color:var(--ink); padding:.62rem .75rem; font-size:.82rem; }
        .menu-page input:focus, .menu-page select:focus, .menu-page textarea:focus { outline:none; border-color:#c46a4c; box-shadow:0 0 0 3px rgba(189,87,56,.1); }
        .menu-page textarea { resize:vertical; }
        .menu-page .modal-shade { background:rgba(35,27,22,.62); backdrop-filter:blur(5px); }
        .menu-page .modal-panel { max-height:92dvh; overflow-y:auto; background:#fffdf9; box-shadow:0 28px 70px rgba(28,20,15,.28); }
        .menu-page .tag { display:inline-flex; align-items:center; border-radius:999px; padding:.2rem .52rem; font-size:.58rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
        .tag-live { background:#eaf6ed; color:#287345; }.tag-sold { background:#fae9e3; color:#ad4428; }.tag-special { background:#fff2cd; color:#8c6112; }.tag-hidden { background:#eeeae6; color:#887b71; }
        .menu-page .food-row:hover { background:#fcf6ee; }
        .menu-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:1rem 1.1rem;border-radius:1.35rem;color:#fffaf1;background:radial-gradient(circle at 88% 12%,rgba(215,166,74,.24),transparent 28%),linear-gradient(135deg,#261b16,#3a2921);box-shadow:0 16px 36px rgba(48,31,23,.16)}.menu-hero:after{content:'';position:absolute;right:-3rem;top:-5rem;width:12rem;height:12rem;border:1px solid rgba(255,255,255,.08);border-radius:50%;pointer-events:none}.menu-hero>*{position:relative;z-index:1}.menu-hero-actions{display:flex;gap:.4rem}.menu-hero-button{display:flex;min-height:42px;align-items:center;justify-content:center;gap:.35rem;border:1px solid rgba(255,255,255,.13);border-radius:.75rem;background:rgba(255,255,255,.08);padding:.5rem .65rem;color:#fff!important;font-size:.64rem;font-weight:900}.menu-hero-button.primary{border-color:var(--spice);background:var(--spice)}.menu-summary{display:flex;gap:.35rem;margin-top:.5rem}.menu-summary span{border:1px solid rgba(255,255,255,.11);border-radius:999px;background:rgba(255,255,255,.07);padding:.23rem .45rem;color:rgba(255,250,241,.74);font-size:.55rem;font-weight:800}.menu-summary strong{color:#fff}
        .category-rail{display:flex;gap:.4rem;overflow-x:auto;padding:.45rem;scrollbar-width:none;scroll-snap-type:x proximity}.category-rail::-webkit-scrollbar{display:none}.category-filter{flex:none;min-height:38px;scroll-snap-align:start;border:1px solid var(--line);border-radius:.7rem;background:#fff;padding:.42rem .68rem;color:#746257;font-size:.65rem;font-weight:900}.category-filter.active{border-color:var(--spice);background:var(--spice);color:#fff}.menu-section-head{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eee4da;background:#faf5ed;padding:.6rem .75rem}.stock-note{display:inline-flex;align-items:center;gap:.25rem;color:#8b796c;font-size:.55rem;font-weight:800}.stock-note.low{color:#b04b3e}.stock-note:before{content:'';height:.35rem;width:.35rem;border-radius:50%;background:currentColor}
        .category-editor-form{display:grid;grid-template-columns:minmax(0,1fr) 58px 72px 36px;align-items:center;gap:.4rem;border:1px solid #eee4da;border-radius:.75rem;background:#fff;padding:.45rem}.category-editor-form input{min-height:38px!important;padding:.4rem .5rem!important;font-size:.68rem!important}.modal-close{display:flex;height:40px;width:40px;align-items:center;justify-content:center;border-radius:50%;background:#f2ede8;color:#75665c;font-size:1.4rem}
        [x-cloak] { display:none!important; }
        @media(min-width:1024px){.menu-hero{padding:1.15rem 1.25rem}}
        @media(max-width:767px){.menu-page{margin-inline:-.25rem}.menu-hero h1{font-size:1.4rem}.menu-hero .hero-copy{display:none}.menu-hero-button span{display:none}.menu-page .modal-wrap{align-items:flex-end!important;padding:0!important}.menu-page .modal-panel{max-height:92dvh;border-radius:1.35rem 1.35rem 0 0!important}.menu-page input:not([type=checkbox]):not([type=file]),.menu-page select,.menu-page textarea{font-size:.78rem!important}.menu-page .food-row{padding:.55rem .65rem}.menu-page .food-row>div:first-child{height:2.6rem;width:2.6rem}.category-editor-form{grid-template-columns:minmax(0,1fr) 52px 34px}.category-editor-form .category-active{display:none}.menu-summary span:last-child{display:none}.menu-basic-grid{grid-template-columns:minmax(0,1fr) 105px!important}.menu-basic-grid>div:first-child{grid-column:1/-1}.menu-meta-grid,.menu-copy-grid{grid-template-columns:1fr 1fr!important}.menu-stock-grid{grid-template-columns:1fr 1fr!important}.menu-stock-grid>div:first-child{grid-column:1/-1}.menu-page .modal-panel>section{padding:.75rem!important}}
    </style>

    <div class="menu-page space-y-3" x-data="{
        addModal: {{ ($errors->any() && !$editingId) ? 'true' : 'false' }},
        editModal: {{ $editingProductOnLoad ? 'true' : 'false' }},
        categoryModal: false,
        activeCategory: @js($categories->first()?->name ?? 'all'),
        isDesktop: window.innerWidth >= 768,
        editingProduct: @js($editingProductOnLoad ? $productPayload($editingProductOnLoad) : []),
        init() { window.addEventListener('resize', () => this.isDesktop = window.innerWidth >= 768); },
        editProduct(product) { this.editingProduct = {...product, is_active:Boolean(product.is_active), remove_image:false}; this.editModal=true; this.$nextTick(() => { if(this.$refs.editImage) this.$refs.editImage.value=''; }); }
    }">
        <header class="menu-hero">
            <div><p class="text-[9px] font-black uppercase tracking-[.18em] text-[#e7bd72]">Restaurant menu</p><h1 class="text-2xl font-black text-[#fffaf1]">Menu Management</h1><p class="hero-copy text-xs text-white/55">Dishes, pricing and availability at a glance.</p><div class="menu-summary"><span><strong>{{ $allProducts->count() }}</strong> items</span><span><strong>{{ $availableCount }}</strong> available</span><span><strong>{{ $soldOutCount }}</strong> sold out</span><span><strong>{{ $lowStockCount }}</strong> low stock</span></div></div>
            <div class="menu-hero-actions"><button type="button" @click="categoryModal=true" class="menu-hero-button"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M5 7h14M5 12h14M5 17h9"/></svg><span>Categories</span></button><button type="button" @click="addModal=true" class="menu-hero-button primary"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-width="2.2" d="M12 5v14M5 12h14"/></svg><span>Add item</span></button></div>
        </header>

        <nav class="menu-card category-rail rounded-xl" aria-label="Menu categories">
            <button type="button" @click="activeCategory='all'" class="category-filter" :class="activeCategory==='all'?'active':''">All {{ $allProducts->count() }}</button>
            @foreach($categories as $category)<button type="button" @click="activeCategory=@js($category->name)" class="category-filter {{ !$category->is_active?'opacity-50':'' }}" :class="activeCategory===@js($category->name)?'active':''">{{ $category->name }} {{ $menuItems->get($category->name)?->count() ?? 0 }}</button>@endforeach
        </nav>

        <main class="grid gap-3 xl:grid-cols-2">
            @forelse($menuItems as $categoryName => $items)
                @php($categoryRecord = $categories->firstWhere('name', $categoryName))
                <section x-show="isDesktop || activeCategory==='all' || activeCategory===@js($categoryName)" id="category-{{ $categoryRecord?->id }}" class="menu-card overflow-hidden rounded-2xl">
                    <div class="menu-section-head">
                        <h2 class="text-sm font-black text-[#3b2e26]">{{ $categoryName }}</h2>
                        <span class="text-[10px] font-black uppercase tracking-wider text-[#a49283]">{{ $items->count() }} items</span>
                    </div>
                    <div class="divide-y divide-[#f0e8df]">
                        @foreach($items as $item)
                            <button type="button" @click="editProduct(@js($productPayload($item)))" class="food-row flex w-full items-center gap-3 px-3 py-2.5 text-left transition">
                                <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl border border-[#eadfd3] bg-[#f5ede4]">
                                    @if($item->image_thumbnail_url)<img src="{{ $item->image_thumbnail_url }}" alt="" class="h-full w-full object-cover" loading="lazy">@else<div class="flex h-full items-center justify-center text-xs font-black text-[#b9a89a]">{{ Str::upper(Str::substr($item->name,0,2)) }}</div>@endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5"><span class="truncate text-sm font-black text-[#3b2e26]">{{ $item->name }}</span>
                                        @if(!$item->is_active)<span class="tag tag-hidden">Hidden</span>@elseif($item->availability_status === 'sold_out')<span class="tag tag-sold">Sold out</span>@else<span class="tag tag-live">Available</span>@endif
                                        @if($item->item_kind !== 'regular')<span class="tag tag-special">{{ str_replace('_',' ', $item->item_kind) }}</span>@endif
                                    </div>
                                    <div class="mt-1 flex items-center gap-2 text-[11px]"><strong class="text-[#bd5738]">&euro;{{ number_format((float)$item->price,2) }}</strong>@if($item->stock_quantity !== null)<span class="stock-note {{ $item->stock_quantity <= $item->low_stock_threshold ? 'low':'' }}">{{ $item->stock_quantity }} stock</span>@elseif($item->allergens)<span class="truncate text-[#9b8b7e]">Allergens: {{ $item->allergens }}</span>@elseif($item->description)<span class="truncate text-[#9b8b7e]">{{ $item->description }}</span>@endif</div>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-[#b8a99d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"/></svg>
                            </button>
                        @endforeach
                    </div>
                </section>
            @empty
                <div class="menu-card col-span-full rounded-2xl p-10 text-center text-sm font-bold text-[#9b8b7e]">No menu items yet.</div>
            @endforelse
        </main>

        <div x-show="categoryModal" x-cloak class="modal-wrap fixed inset-0 z-[100] flex items-center justify-center p-3">
            <div class="modal-shade absolute inset-0" @click="categoryModal=false"></div>
            <div class="modal-panel relative w-full max-w-xl rounded-2xl p-5" @click.stop @keydown.escape.window="categoryModal=false">
                <div class="mb-3 flex items-center justify-between"><div><p class="eyebrow">Menu sections</p><h2 class="text-lg font-black text-[#30261f]">Categories</h2></div><button @click="categoryModal=false" class="modal-close">&times;</button></div>
                <form action="{{ route('menu-categories.store') }}" method="POST" class="mb-3 grid grid-cols-[1fr_72px_auto] gap-2">@csrf
                    <input name="name" required placeholder="New category">
                    <input name="sort_order" type="number" min="0" value="{{ $categories->count() }}" aria-label="Order">
                    <button class="rounded-xl bg-[#bd5738] px-3 text-xs font-black text-white">Add</button>
                    <input type="hidden" name="is_active" value="1">
                </form>
                <div class="max-h-[55vh] space-y-1.5 overflow-y-auto">
                    @foreach($categories as $category)
                        <form action="{{ route('menu-categories.update', $category) }}" method="POST" class="category-editor-form">@csrf @method('PUT')
                            <input name="name" value="{{ $category->name }}" required><input name="sort_order" type="number" min="0" value="{{ $category->sort_order }}">
                            <label class="category-active flex items-center gap-1 text-[10px] font-black uppercase text-[#8d7e72]"><input type="checkbox" name="is_active" value="1" {{ $category->is_active?'checked':'' }}> Active</label>
                            <button title="Save" class="text-lg text-[#bd5738]">✓</button>
                        </form>
                        @if(!$category->items()->exists())<form action="{{ route('menu-categories.destroy', $category) }}" method="POST" class="-mt-10 ml-auto mr-11 w-6">@csrf @method('DELETE')<button title="Delete empty category" class="text-sm text-red-400">×</button></form>@endif
                    @endforeach
                </div>
            </div>
        </div>

        @include('menu-items._form_modal', ['mode' => 'add'])
        @include('menu-items._form_modal', ['mode' => 'edit'])
    </div>
</div>
</x-app-layout>
