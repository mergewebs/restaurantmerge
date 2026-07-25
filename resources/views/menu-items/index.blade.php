<x-app-layout>
@php
    $allProducts = $menuItems->flatten();
    $editingId = old('editing_product_id') ?: session('edit_menu_item_id');
    $editingProductOnLoad = $editingId ? $allProducts->firstWhere('id', (int) $editingId) : null;
    $availableCount = $allProducts->where('availability_status', 'available')->where('is_active', true)->count();
    $soldOutCount = $allProducts->where('availability_status', 'sold_out')->count();
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
        .menu-page { --ink:#30261f; --muted:#8d7e72; --line:#eadfd2; --paper:#fffdf9; --cream:#f8f1e7; --spice:#bd5738; }
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
        [x-cloak] { display:none!important; }
        @media(max-width:640px){.menu-page .modal-panel{max-height:96dvh;border-radius:1.25rem}.menu-page input:not([type=checkbox]):not([type=file]),.menu-page select,.menu-page textarea{font-size:1rem}}
    </style>

    <div class="menu-page space-y-3" x-data="{
        addModal: {{ ($errors->any() && !$editingId) ? 'true' : 'false' }},
        editModal: {{ $editingProductOnLoad ? 'true' : 'false' }},
        categoryModal: false,
        editingProduct: @js($editingProductOnLoad ? $productPayload($editingProductOnLoad) : []),
        editProduct(product) { this.editingProduct = {...product, is_active:Boolean(product.is_active), remove_image:false}; this.editModal=true; this.$nextTick(() => { if(this.$refs.editImage) this.$refs.editImage.value=''; }); }
    }">
        <header class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="eyebrow">Restaurant Menu</p>
                <div class="flex items-baseline gap-3"><h1 class="text-2xl font-black tracking-tight text-[#30261f]">Menu Management</h1><span class="text-xs font-bold text-[#9a897b]">{{ $allProducts->count() }} items</span></div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="tag tag-live">{{ $availableCount }} available</span>
                @if($soldOutCount)<span class="tag tag-sold">{{ $soldOutCount }} sold out</span>@endif
                <button type="button" @click="categoryModal=true" class="rounded-xl border border-[#dfd1c3] bg-white px-3 py-2 text-xs font-black text-[#6d5a4d] hover:bg-[#faf4ec]">Categories</button>
                <button type="button" @click="addModal=true" class="rounded-xl bg-[#bd5738] px-4 py-2 text-xs font-black text-white shadow-sm hover:bg-[#a94b30]">+ Add menu item</button>
            </div>
        </header>

        <nav class="menu-card flex gap-1.5 overflow-x-auto rounded-xl p-2" aria-label="Menu categories">
            @foreach($categories as $category)
                <a href="#category-{{ $category->id }}" class="whitespace-nowrap rounded-lg px-2.5 py-1.5 text-xs font-extrabold {{ $category->is_active ? 'bg-[#f7ede2] text-[#7d4633]' : 'bg-slate-100 text-slate-400' }}">{{ $category->name }}</a>
            @endforeach
        </nav>

        <main class="grid gap-3 xl:grid-cols-2">
            @forelse($menuItems as $categoryName => $items)
                @php($categoryRecord = $categories->firstWhere('name', $categoryName))
                <section id="category-{{ $categoryRecord?->id }}" class="menu-card overflow-hidden rounded-2xl">
                    <div class="flex items-center justify-between border-b border-[#eee4da] bg-[#faf5ed] px-3.5 py-2.5">
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
                                    <div class="mt-1 flex items-center gap-2 text-[11px]"><strong class="text-[#bd5738]">€{{ number_format((float)$item->price,2) }}</strong>@if($item->allergens)<span class="truncate text-[#9b8b7e]">Allergens: {{ $item->allergens }}</span>@elseif($item->description)<span class="truncate text-[#9b8b7e]">{{ $item->description }}</span>@endif</div>
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

        <div x-show="categoryModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-3">
            <div class="modal-shade absolute inset-0" @click="categoryModal=false"></div>
            <div class="modal-panel relative w-full max-w-xl rounded-2xl p-5" @click.stop @keydown.escape.window="categoryModal=false">
                <div class="mb-3 flex items-center justify-between"><div><p class="eyebrow">Menu sections</p><h2 class="text-lg font-black text-[#30261f]">Categories</h2></div><button @click="categoryModal=false" class="text-2xl text-[#9b8b7e]">&times;</button></div>
                <form action="{{ route('menu-categories.store') }}" method="POST" class="mb-3 grid grid-cols-[1fr_72px_auto] gap-2">@csrf
                    <input name="name" required placeholder="New category">
                    <input name="sort_order" type="number" min="0" value="{{ $categories->count() }}" aria-label="Order">
                    <button class="rounded-xl bg-[#bd5738] px-3 text-xs font-black text-white">Add</button>
                    <input type="hidden" name="is_active" value="1">
                </form>
                <div class="max-h-[55vh] space-y-1.5 overflow-y-auto">
                    @foreach($categories as $category)
                        <form action="{{ route('menu-categories.update', $category) }}" method="POST" class="grid grid-cols-[1fr_64px_80px_36px] items-center gap-2 rounded-xl border border-[#eee4da] bg-white p-2">@csrf @method('PUT')
                            <input name="name" value="{{ $category->name }}" required><input name="sort_order" type="number" min="0" value="{{ $category->sort_order }}">
                            <label class="flex items-center gap-1 text-[10px] font-black uppercase text-[#8d7e72]"><input type="checkbox" name="is_active" value="1" {{ $category->is_active?'checked':'' }}> Active</label>
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
