<x-app-layout>
    <div data-async-region="menu-items-index" data-async-mutations="true">
        @if(session('success'))
            <div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
    @php
        $allProducts = $menuItems->flatten();
        $activeCount = $allProducts->where("is_active", true)->count();
        $hiddenCount = $allProducts->where("is_active", false)->count();
    @endphp

    <style>
        /* ---- Form inputs ---- */
        .products-page .field-label {
            display: block;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #64748b;
            margin-bottom: 0.32rem;
        }
        .products-page input[type="text"],
        .products-page input[type="number"],
        .products-page select,
        .products-page textarea {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 11px;
            padding: 0.65rem 0.9rem;
            color: #0f172a;
            font-size: 0.875rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .products-page input[type="text"]:focus,
        .products-page input[type="number"]:focus,
        .products-page select:focus,
        .products-page textarea:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            background: #fff;
        }
        .products-page textarea { resize: vertical; }
        .products-page select { cursor: pointer; }

        /* ---- Modal overlay ---- */
        .modal-overlay {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(15,23,42,0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
        }
        .modal-box {
            background: #fff;
            border-radius: 24px;
            width: 100%;
            max-width: 600px;
            max-height: 90dvh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0,0,0,0.18), 0 8px 20px rgba(0,0,0,0.08);
            animation: modalIn 0.22s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.94) translateY(12px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            position: sticky; top: 0; background: #fff; z-index: 1;
            border-radius: 24px 24px 0 0;
        }
        .modal-close-btn {
            width: 2rem; height: 2rem; border-radius: 50%;
            background: #f1f5f9; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #94a3b8; transition: background 0.15s, color 0.15s;
            flex-shrink: 0;
        }
        .modal-close-btn:hover { background: #e2e8f0; color: #475569; }
        .modal-body { padding: 1.5rem; }

        /* ---- Stats ---- */
        .stat-tile { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 0.85rem 1rem; text-align: center; min-width: 64px; }
        .st-label { font-size: 0.58rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; color: #94a3b8; margin-bottom: 0.28rem; }
        .st-value { font-size: 1.45rem; font-weight: 900; color: #0f172a; line-height: 1; }
        .stat-tile.t-active { border-color: rgba(16,185,129,0.25); background: rgba(16,185,129,0.05); }
        .stat-tile.t-active .st-value { color: #10b981; }

        /* ---- Section eyebrow ---- */
        .eyebrow { font-size: 0.58rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.3em; color: #6366f1; margin-bottom: 0.18rem; }

        /* ---- Category pill ---- */
        .cat-pill { display: inline-flex; align-items: center; background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.15); color: #6366f1; border-radius: 999px; font-size: 0.58rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.16em; padding: 0.2rem 0.6rem; }

        /* ---- Badges ---- */
        .bdg-live   { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #059669; border-radius: 999px; font-size: 0.56rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.13em; padding: 0.13rem 0.52rem; }
        .bdg-hidden { background: #f1f5f9; border: 1px solid #e2e8f0; color: #94a3b8; border-radius: 999px; font-size: 0.56rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.13em; padding: 0.13rem 0.52rem; }


        /* ---- Product accordion rows ---- */
        .prod-row { border-bottom: 1px solid #f1f5f9; }
        .prod-row:last-child { border-bottom: none; }
        .prod-row > summary { list-style: none; cursor: pointer; }
        .prod-row > summary::-webkit-details-marker { display: none; }
        .prod-row > summary:hover > div { background: #f8fafc; }
        .prod-row[open] > summary > div { background: #f8fafc; }

        /* Chevron */
        .row-chev { width: 1.5rem; height: 1.5rem; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; flex-shrink: 0; transition: background 0.15s, color 0.15s, transform 0.2s; }
        details[open] .row-chev { background: rgba(99,102,241,0.1); color: #6366f1; transform: rotate(180deg); }

        /* Thumbnail */
        .prod-thumb { width: 46px; height: 46px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .prod-thumb-ph { font-size: 0.8rem; font-weight: 900; color: #cbd5e1; text-transform: uppercase; }

        /* Toggle checkbox */
        .toggle-lbl { display: flex; align-items: center; gap: 0.65rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 11px; padding: 0.65rem 0.9rem; cursor: pointer; transition: border-color 0.15s; color: #334155; font-size: 0.85rem; font-weight: 600; user-select: none; }
        .toggle-lbl:hover { border-color: #6366f1; }
        .toggle-lbl input[type="checkbox"] { width: 1rem; height: 1rem; border-radius: 5px; accent-color: #6366f1; flex-shrink: 0; cursor: pointer; }

        /* Delete btn */
        .btn-del { border-radius: 11px; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.06); color: #dc2626; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; padding: 0.65rem 1.1rem; transition: background 0.15s; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; justify-content: center; }
        .btn-del:hover { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.35); }

        @media (max-width: 640px) {
            .products-page input[type="text"],
            .products-page input[type="number"],
            .products-page select,
            .products-page textarea { font-size: 1rem; }
            .modal-box { max-height: 95dvh; border-radius: 20px; }
        }
    </style>

    {{-- ========================================================= --}}
    {{-- ROOT Alpine scope                                         --}}
    {{-- ========================================================= --}}
    <div class="products-page space-y-5"
         x-data="{
             addModal: {{ $errors->any() ? 'true' : 'false' }}
         }">

        {{-- ---- Page header ---- --}}
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <p class="eyebrow">Owner Panel</p>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">Products</h1>
                <p class="mt-1 text-sm text-slate-400 max-w-md">Manage what is shown on the public site. Upload photos and set visibility.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                {{-- Stats --}}
                <div class="stat-tile"><div class="st-label">Total</div><div class="st-value">{{ $allProducts->count() }}</div></div>
                <div class="stat-tile t-active"><div class="st-label">Active</div><div class="st-value">{{ $activeCount }}</div></div>
                <div class="stat-tile"><div class="st-label">Hidden</div><div class="st-value">{{ $hiddenCount }}</div></div>
                <div class="stat-tile"><div class="st-label">Cats</div><div class="st-value">{{ count($categoryOptions) }}</div></div>
                {{-- Add button --}}
                <button @click="addModal = true"
                    class="btn-koya ml-2 h-full flex items-center gap-2 whitespace-nowrap px-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="hidden sm:inline">Add Product</span>
                    <span class="sm:hidden">Add</span>
                </button>
            </div>
        </div>

        {{-- ---- Products list ---- --}}
        <section class="space-y-4">
            <div>
                <p class="eyebrow">Menu Catalog</p>
                <h2 class="text-base font-black text-slate-800">Existing Products <span class="text-slate-400 font-semibold text-sm">- tap a row to edit</span></h2>
            </div>

            @forelse($menuItems as $category => $items)
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    {{-- Category header --}}
                    <div class="flex items-center justify-between gap-3 px-4 sm:px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-[9px] bg-indigo-50 border border-indigo-100 flex items-center justify-center">
                                <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-800">{{ $category }}</h3>
                        </div>
                        <span class="cat-pill">{{ $items->count() }} items</span>
                    </div>

                    {{-- Rows --}}
                    <div>
                        @foreach($items as $item)
                            <details class="prod-row">
                                <summary>
                                    <div class="flex items-center gap-3 px-4 sm:px-5 py-3.5 transition-colors">
                                        <div class="prod-thumb">
                                            @if($item->image_thumbnail_url)
                                                <img src="{{ $item->image_thumbnail_url }}" alt="{{ $item->name }}" loading="lazy" width="46" height="46">
                                            @else
                                                <span class="prod-thumb-ph">{{ \Illuminate\Support\Str::of($item->name)->substr(0,2)->upper() }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-1.5 mb-0.5">
                                                <span class="text-sm font-black text-slate-800 truncate max-w-[140px] sm:max-w-none">{{ $item->name }}</span>
                                                @if($item->is_active)<span class="bdg-live">Live</span>@else<span class="bdg-hidden">Hidden</span>@endif
                                                
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="font-black text-indigo-600">{{ number_format((float)$item->price, 2) }} EUR</span>
                                                @if($item->description)<span class="text-slate-400">{{ $item->description }}</span>@endif
                                            </div>
                                        </div>
                                        <div class="row-chev">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </summary>

                                {{-- Edit form --}}
                                <div class="border-t border-slate-100 bg-slate-50/50 p-4 sm:p-5">
                                    <form action="{{ route('menu-items.update', $item) }}" method="POST" enctype="multipart/form-data"
                                        class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="field-label" for="name-{{ $item->id }}">Name</label>
                                            <input id="name-{{ $item->id }}" type="text" name="name" value="{{ $item->name }}" required>
                                        </div>
                                        <div>
                                            <label class="field-label" for="cat-{{ $item->id }}">Category</label>
                                            <select id="cat-{{ $item->id }}" name="category" required>
                                                @foreach($categoryOptions as $cat)<option value="{{ $cat }}" {{ $item->category===$cat?'selected':'' }}>{{ $cat }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="field-label" for="price-{{ $item->id }}">Price</label>
                                            <input id="price-{{ $item->id }}" type="number" step="0.01" min="0" name="price" value="{{ $item->price }}" required>
                                        </div>
                                        <div>
                                            <label class="field-label" for="sort-{{ $item->id }}">Display order</label>
                                            <input id="sort-{{ $item->id }}" type="number" min="0" max="999" name="sort_order" value="{{ $item->sort_order }}">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="field-label" for="desc-{{ $item->id }}">Description</label>
                                            <textarea id="desc-{{ $item->id }}" name="description" rows="2">{{ $item->description }}</textarea>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="field-label">Replace image</label>
                                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-3">
                                                <input id="img-{{ $item->id }}" type="file" name="image" accept="image/*"
                                                    class="w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-black file:text-indigo-600 file:cursor-pointer">
                                                @if($item->image_url)
                                                    <label class="toggle-lbl mt-3"><input type="checkbox" name="remove_image" value="1"><span>Remove current image</span></label>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sm:col-span-2 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                            <label class="toggle-lbl"><input type="checkbox" name="is_active" value="1" {{ $item->is_active?'checked':'' }}><span>Visible on public page</span></label>
                                        </div>
                                        <div class="sm:col-span-2 flex flex-col sm:flex-row gap-3 sm:justify-end pt-1">
                                            <button type="submit" form="del-{{ $item->id }}" class="btn-del"
                                                onclick="return confirm('Delete this product? This cannot be undone.')">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </button>
                                            <button type="submit" class="btn-koya sm:w-auto px-6">Save Changes</button>
                                        </div>
                                    </form>
                                    <form id="del-{{ $item->id }}" action="{{ route('menu-items.destroy', $item) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-2xl p-10 text-center shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-400">No products yet. Click "Add Product" to create the first one.</p>
                </div>
            @endforelse
        </section>

        {{-- ============================================================ --}}
        {{-- ADD PRODUCT MODAL                                            --}}
        {{-- ============================================================ --}}
            <div x-show="addModal"
                 class="fixed inset-0 z-[100] flex items-center justify-center px-4"
                 style="display: none;"
                 x-cloak>
                <div @click="addModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                     x-show="addModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     @keydown.escape.window="addModal = false"
                     @click.stop>

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800">Add New Product</h3>
                        <button @click="addModal = false" class="text-gray-400 hover:text-slate-600 transition-colors" aria-label="Close">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                        @csrf

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-name">Product Name</label>
                            <input id="product-name" type="text" name="name" value="{{ old('name') }}" placeholder="KOYA Smash" required
                                class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-category">Category</label>
                                <select id="product-category" name="category" required
                                    class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 transition-all">
                                    @foreach($categoryOptions as $cat)
                                        <option value="{{ $cat }}" {{ old('category','Smash Burger')===$cat?'selected':'' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-sort">Display Order</label>
                                <input id="product-sort" type="number" min="0" max="999" name="sort_order" value="{{ old('sort_order',0) }}" placeholder="0"
                                    class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block" for="product-price">Price (EUR)</label>
                            <input id="product-price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="0.00" required
                                class="w-full bg-white border-2 border-indigo-100 text-slate-800 text-2xl font-black px-4 py-4 rounded-2xl outline-none focus:border-indigo-500 transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-description">Description</label>
                            <textarea id="product-description" name="description" rows="3" placeholder="Short description for customers"
                                class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all resize-y">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-image">Product Image</label>
                            <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-4">
                                <input id="product-image" type="file" name="image" accept="image/*"
                                    class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-xs file:font-black file:text-indigo-600 file:cursor-pointer hover:file:bg-indigo-100">
                                <p class="mt-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">JPG / PNG / WEBP - max 5 MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="toggle-lbl rounded-2xl">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active',true)?'checked':'' }}>
                                <span>Visible on public page</span>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="button" @click="addModal = false"
                                class="flex-1 bg-gray-50 border border-gray-200 text-slate-600 rounded-2xl py-4 text-xs font-black uppercase tracking-widest hover:bg-gray-100 transition-all active:scale-95">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-[2] btn-koya py-4 shadow-xl shadow-koya/20 text-white font-bold uppercase tracking-widest">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

    </div>{{-- /products-page --}}
    </div>
</x-app-layout>












