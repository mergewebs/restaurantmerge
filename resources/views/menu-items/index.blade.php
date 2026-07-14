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
        $editingProductOnError = old('editing_product_id')
            ? $allProducts->firstWhere('id', (int) old('editing_product_id'))
            : null;
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
        .prod-row > summary, .prod-row > button { list-style: none; cursor: pointer; }
        .prod-row > summary::-webkit-details-marker { display: none; }
        .prod-row > summary:hover > div, .prod-row > button:hover > div { background: #f8fafc; }
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

        /* Dedicated product visibility control; isolated from global input sizing. */
        .product-visibility-toggle {
            position: relative;
            width: 100%;
            text-transform: none !important;
            letter-spacing: normal !important;
        }
        .product-visibility-toggle input[type="checkbox"] {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            min-width: 1px !important;
            min-height: 1px !important;
            margin: 0 !important;
            padding: 0 !important;
            opacity: 0;
            pointer-events: none;
        }
        .product-visibility-toggle span {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            width: 100%;
            color: #334155;
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.25;
            text-transform: none !important;
            letter-spacing: normal !important;
        }
        .product-visibility-toggle span::before {
            content: '';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.25rem;
            height: 1.25rem;
            flex: 0 0 1.25rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.4rem;
            background: #ffffff;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 900;
            transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
        }
        .product-visibility-toggle input[type="checkbox"]:checked + span::before {
            content: '✓';
            border-color: #4f46e5;
            background: #4f46e5;
        }
        .product-visibility-toggle input[type="checkbox"]:focus-visible + span::before {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.18);
        }

        /* Delete btn */
        .btn-del { border-radius: 11px; border: 1px solid rgba(239,68,68,0.2); background: rgba(239,68,68,0.06); color: #dc2626; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; padding: 0.65rem 1.1rem; transition: background 0.15s; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; justify-content: center; }
        .btn-del:hover { background: rgba(239,68,68,0.12); border-color: rgba(239,68,68,0.35); }

        @media (max-width: 640px) {
            .products-page input[type="text"],
            .products-page input[type="number"],
            .products-page select,
            .products-page textarea { font-size: 1rem; }
            .modal-box { max-height: 95dvh; border-radius: 20px; }

            :is(.product-add-modal, .product-edit-modal) .product-modal-panel {
                overflow-x: hidden !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-modal-header {
                position: sticky;
                top: -0.875rem;
                z-index: 20;
                margin: -0.875rem -0.875rem 0.75rem !important;
                padding: 0.75rem 0.875rem;
                border-bottom: 1px solid #e2e8f0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(12px);
            }
            :is(.product-add-modal, .product-edit-modal) .product-modal-form > :not([hidden]) ~ :not([hidden]) {
                margin-top: 0.65rem !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-modal-form input:not([type="checkbox"]):not([type="file"]),
            :is(.product-add-modal, .product-edit-modal) .product-modal-form select {
                min-height: 2.75rem !important;
                border-radius: 0.75rem !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-modal-form textarea {
                min-height: 4.5rem !important;
                max-height: 7rem;
                border-radius: 0.75rem !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-upload {
                padding: 0.65rem !important;
                border-radius: 0.875rem !important;
            }
            .fixed.inset-0.z-\[100\]:is(.product-add-modal, .product-edit-modal) .product-upload input[type="file"] {
                display: block;
                width: 100% !important;
                max-width: 100% !important;
                min-height: 0 !important;
                padding: 0.25rem !important;
                overflow: hidden;
                font-size: 0.7rem !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-upload p {
                margin-top: 0.4rem !important;
                font-size: 0.55rem !important;
            }
            :is(.product-add-modal, .product-edit-modal) .product-visibility-toggle {
                display: flex !important;
                width: 100%;
                min-height: 2.75rem;
                align-items: center !important;
                gap: 0.65rem !important;
                margin: 0 !important;
                padding: 0.65rem 0.8rem !important;
                border-radius: 0.875rem !important;
                font-size: 0.8rem !important;
                line-height: 1.2;
            }

            :is(.product-add-modal, .product-edit-modal) .product-visibility-toggle span {
                display: flex;
                align-items: center;
                gap: 0.65rem;
                min-width: 0;
                color: #334155;
                font-size: 0.8rem;
                font-weight: 700;
                text-transform: none !important;
                letter-spacing: normal !important;
                white-space: normal;
            }
            :is(.product-add-modal, .product-edit-modal) .product-modal-actions {
                position: sticky;
                bottom: -0.875rem;
                z-index: 20;
                margin: 0.75rem -0.875rem -0.875rem !important;
                padding: 0.75rem 0.875rem max(0.75rem, env(safe-area-inset-bottom)) !important;
                border-top: 1px solid #e2e8f0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(12px);
            }
            .fixed.inset-0.z-\[100\]:is(.product-add-modal, .product-edit-modal) .product-modal-actions button {
                min-height: 2.75rem !important;
                padding: 0.65rem 0.75rem !important;
                border-radius: 0.875rem !important;
                font-size: 0.7rem !important;
                white-space: nowrap;
            }
        }
    </style>

    {{-- ========================================================= --}}
    {{-- ROOT Alpine scope                                         --}}
    {{-- ========================================================= --}}
    <div class="products-page space-y-5"
         x-data="{
             addModal: {{ ($errors->any() && !old('editing_product_id')) ? 'true' : 'false' }},
             editModal: {{ ($errors->any() && old('editing_product_id')) ? 'true' : 'false' }},
             editingProduct: {
                 id: @js(old('editing_product_id')),
                 name: @js(old('name', '')),
                 category: @js(old('category', '')),
                 price: @js(old('price', '')),
                 sort_order: @js(old('sort_order', 0)),
                 description: @js(old('description', '')),
                 is_active: @js((bool) old('is_active', false)),
                 image_url: @js($editingProductOnError?->image_url),
                 remove_image: @js((bool) old('remove_image', false)),
                 update_url: @js($editingProductOnError ? route('menu-items.update', $editingProductOnError) : ''),
                 delete_url: @js($editingProductOnError ? route('menu-items.destroy', $editingProductOnError) : '')
             },
             editProduct(product) {
                 this.editingProduct = {
                     ...product,
                     is_active: Boolean(product.is_active),
                     remove_image: false
                 };
                 this.editModal = true;
                 const imageInput = document.getElementById('edit-product-image');
                 if (imageInput) imageInput.value = '';
             }
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
                            <div class="prod-row">
                                <button type="button"
                                    @click="editProduct(@js([
                                        "id" => $item->id,
                                        "name" => $item->name,
                                        "category" => $item->category,
                                        "price" => (string) $item->price,
                                        "sort_order" => $item->sort_order,
                                        "description" => $item->description ?? "",
                                        "is_active" => (bool) $item->is_active,
                                        "image_url" => $item->image_url,
                                        "update_url" => route("menu-items.update", $item),
                                        "delete_url" => route("menu-items.destroy", $item),
                                    ]))"
                                    class="block w-full text-left"
                                    aria-label="Edit {{ $item->name }}">
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
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536M9 15l6.586-6.586a2 2 0 112.828 2.828L11.828 17.828 8 19l1-4z"/></svg>
                                        </div>
                                    </div>
                                </button>

                                </div>
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
                 class="product-add-modal fixed inset-0 z-[100] flex items-center justify-center px-4"
                 style="display: none;"
                 x-cloak>
                <div @click="addModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="product-modal-panel glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                     x-show="addModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-90"
                     @keydown.escape.window="addModal = false"
                     @click.stop>

                    <div class="product-modal-header flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800">Add New Product</h3>
                        <button @click="addModal = false" class="text-gray-400 hover:text-slate-600 transition-colors" aria-label="Close">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data" class="product-modal-form space-y-4 text-left">
                        @csrf

                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="product-name">Product Name</label>
                            <input id="product-name" type="text" name="name" value="{{ old('name') }}" placeholder="KOYA Smash" required
                                class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
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
                            <div class="product-upload rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-4">
                                <input id="product-image" type="file" name="image" accept="image/*"
                                    class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-xs file:font-black file:text-indigo-600 file:cursor-pointer hover:file:bg-indigo-100">
                                <p class="mt-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">JPG / PNG / WEBP - max 5 MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="product-visibility-toggle toggle-lbl rounded-2xl">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active',true)?'checked':'' }}>
                                <span>Visible on public page</span>
                            </label>
                        </div>

                        <div class="product-modal-actions flex flex-row gap-3 pt-2">
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

        {{-- ============================================================ --}}
        {{-- EDIT PRODUCT MODAL                                           --}}
        {{-- ============================================================ --}}
        <div x-show="editModal"
             class="product-edit-modal fixed inset-0 z-[100] flex items-center justify-center px-4"
             style="display: none;"
             x-cloak>
            <div @click="editModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="product-modal-panel glass w-full max-w-2xl rounded-[32px] p-8 relative border border-white/10"
                 x-show="editModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 @keydown.escape.window="editModal = false"
                 @click.stop>

                <div class="product-modal-header flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Edit Product</h3>
                    <button type="button" @click="editModal = false" class="text-gray-400 hover:text-slate-600 transition-colors" aria-label="Close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="editingProduct.update_url" method="POST" enctype="multipart/form-data" class="product-modal-form space-y-4 text-left">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="editing_product_id" :value="editingProduct.id">

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="edit-product-name">Product Name</label>
                        <input id="edit-product-name" type="text" name="name" x-model="editingProduct.name" placeholder="KOYA Smash" required
                            class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="edit-product-category">Category</label>
                            <select id="edit-product-category" name="category" x-model="editingProduct.category" required
                                class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 transition-all">
                                @foreach($categoryOptions as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="edit-product-sort">Display Order</label>
                            <input id="edit-product-sort" type="number" min="0" max="999" name="sort_order" x-model="editingProduct.sort_order" placeholder="0"
                                class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block" for="edit-product-price">Price (EUR)</label>
                        <input id="edit-product-price" type="number" step="0.01" min="0" name="price" x-model="editingProduct.price" placeholder="0.00" required
                            class="w-full bg-white border-2 border-indigo-100 text-slate-800 text-2xl font-black px-4 py-4 rounded-2xl outline-none focus:border-indigo-500 transition-all shadow-sm">
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="edit-product-description">Description</label>
                        <textarea id="edit-product-description" name="description" rows="3" x-model="editingProduct.description" placeholder="Short description for customers"
                            class="w-full bg-white border border-gray-200 text-slate-800 text-sm px-4 py-3 rounded-2xl outline-none focus:border-indigo-500 focus:bg-white transition-all resize-y"></textarea>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block" for="edit-product-image">Product Image</label>
                        <div class="product-upload rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-4">
                            <div x-show="editingProduct.image_url" class="mb-3 flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-2.5">
                                <img :src="editingProduct.image_url || ''" :alt="editingProduct.name"
                                     class="h-12 w-12 shrink-0 rounded-lg border border-slate-200 object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs font-bold text-slate-700">Current product image</p>
                                    <label class="product-visibility-toggle toggle-lbl mt-2">
                                        <input type="checkbox" name="remove_image" value="1" x-model="editingProduct.remove_image">
                                        <span>Remove current image</span>
                                    </label>
                                </div>
                            </div>
                            <input id="edit-product-image" x-ref="editProductImage" type="file" name="image" accept="image/*"
                                class="w-full text-sm text-slate-500 file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2.5 file:text-xs file:font-black file:text-indigo-600 file:cursor-pointer hover:file:bg-indigo-100">
                            <p class="mt-2 text-[10px] font-bold uppercase tracking-widest text-slate-400">JPG / PNG / WEBP - max 5 MB</p>
                        </div>
                    </div>

                    <div>
                        <label class="product-visibility-toggle toggle-lbl rounded-2xl">
                            <input type="checkbox" name="is_active" value="1" x-model="editingProduct.is_active">
                            <span>Visible on public page</span>
                        </label>
                    </div>

                    <div class="product-modal-actions flex flex-row gap-3 pt-2">
                        <button type="button"
                            @click="if (confirm('Delete this product? This cannot be undone.')) $refs.editProductDeleteForm.submit()"
                            class="btn-del flex-1">
                            Delete
                        </button>
                        <button type="submit"
                            class="flex-[2] btn-koya py-4 shadow-xl shadow-koya/20 text-white font-bold uppercase tracking-widest">
                            Save Changes
                        </button>
                    </div>
                </form>

                <form x-ref="editProductDeleteForm" :action="editingProduct.delete_url" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>{{-- /products-page --}}
    </div>
</x-app-layout>












