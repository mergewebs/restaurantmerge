<x-app-layout>
    <style>
        summary.menu-summary {
            list-style: none;
        }

        summary.menu-summary::-webkit-details-marker {
            display: none;
        }

        .menu-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 92, 0, 0.5) rgba(255, 255, 255, 0.08);
        }

        .menu-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .menu-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 9999px;
        }

        .menu-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 92, 0, 0.6);
            border-radius: 9999px;
        }

        .menu-checkbox {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .menu-toggle {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            padding: 0.6rem 0.7rem;
            font-size: 0.74rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.76);
            transition: border-color .2s ease, background-color .2s ease, color .2s ease;
            cursor: pointer;
        }

        .menu-toggle:hover {
            border-color: rgba(255, 255, 255, 0.26);
            background: rgba(255, 255, 255, 0.08);
        }

        .menu-toggle-switch {
            position: relative;
            width: 1.85rem;
            height: 1.1rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: background-color .2s ease, border-color .2s ease;
            flex: none;
        }

        .menu-toggle-switch::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 2px;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.92);
            transform: translate(0, -50%);
            transition: transform .2s ease, background-color .2s ease;
        }

        .menu-toggle input:checked + .menu-toggle-switch {
            background: rgba(255, 92, 0, 0.45);
            border-color: rgba(255, 92, 0, 0.62);
        }

        .menu-toggle input:checked + .menu-toggle-switch::after {
            transform: translate(0.72rem, -50%);
            background: #fff;
        }

        .menu-toggle input:checked ~ .menu-toggle-text {
            color: rgba(255, 255, 255, 0.95);
        }

        .menu-label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.65);
        }

        .menu-field {
            width: 100%;
            border-radius: 0.95rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            padding: 0.72rem 0.85rem;
            font-size: 0.85rem;
            font-weight: 600;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
        }

        .menu-field::placeholder {
            color: rgba(255, 255, 255, 0.38);
            font-weight: 500;
        }

        .menu-field:focus {
            border-color: rgba(255, 92, 0, 0.58);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 0 3px rgba(255, 92, 0, 0.16);
        }

        .menu-help {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.46);
            margin-top: 0.32rem;
        }
    </style>

    @php
        $allItems = $menuItems->flatten();
        $activeCount = $allItems->where('is_active', true)->count();
        $packageCount = $allItems->where('is_package', true)->count();
    @endphp

    <div class="space-y-4" x-data="{ showCreateModal: {{ old('create_form') ? 'true' : 'false' }}, createIsPackage: {{ old('is_package') ? 'true' : 'false' }} }" @keydown.escape.window="showCreateModal = false">
        @if(session('success'))
            <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 bg-red-500/10 border border-red-500/20 text-red-300 rounded-2xl text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="glass rounded-3xl p-5 border border-white/10">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl font-black uppercase tracking-wide">Menu Management</h1>
                </div>
                <div class="flex items-center gap-2 flex-wrap justify-end">
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-white/15 bg-white/5 text-white/70">{{ $allItems->count() }} Items</span>
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-500/20 bg-emerald-500/10 text-emerald-300">{{ $activeCount }} Active</span>
                    <span class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-500/20 bg-amber-500/10 text-amber-300">{{ $packageCount }} Packages</span>
                    <button type="button" @click="showCreateModal = true" class="btn-koya px-4 py-2.5 text-[10px] uppercase tracking-widest font-black">
                        Add Menu Item
                    </button>
                </div>
            </div>
        </div>

        <div class="glass rounded-3xl p-3 border border-white/10">
                <div class="max-h-[80vh] overflow-y-auto pr-1 menu-scroll space-y-3">
                    @forelse($menuItems as $category => $items)
                        <details class="rounded-2xl border border-white/10 bg-white/5 mt-1" open>
                            <summary class="menu-summary cursor-pointer flex items-center justify-between gap-2 px-3 py-2.5 border-b border-white/10">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-koya shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="text-[11px] font-black uppercase tracking-widest text-koya">{{ $category }}</span>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/50">{{ $items->count() }} items</span>
                            </summary>

                            <div class="p-2.5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-2">
                                @foreach($items as $item)
                                    <div class="rounded-xl border border-white/10 bg-black/20" x-data="{ openEdit: false, editIsPackage: {{ $item->is_package ? 'true' : 'false' }} }">
                                        <div class="flex items-start justify-between gap-2 px-2.5 py-2">
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-white truncate">{{ $item->name }}</p>
                                                <div class="mt-1 flex items-center gap-1.5">
                                                    <span class="px-1.5 py-0.5 rounded-md border border-emerald-500/25 bg-emerald-500/10 text-[9px] font-black uppercase tracking-wider text-emerald-300 {{ $item->is_active ? '' : 'hidden' }}">Active</span>
                                                    <span class="px-1.5 py-0.5 rounded-md border border-amber-500/25 bg-amber-500/10 text-[9px] font-black uppercase tracking-wider text-amber-300 {{ $item->is_package ? '' : 'hidden' }}">Package</span>
                                                </div>
                                            </div>
                                            <button type="button"
                                                    @click="openEdit = !openEdit"
                                                    class="shrink-0 w-7 h-7 rounded-lg border border-white/20 bg-white/5 flex items-center justify-center text-white/80 hover:text-koya hover:border-koya/40 transition-colors"
                                                    title="Edit item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 14l-4 1 1-4 7.5-7.5z"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="px-2.5 pb-2.5">
                                            <div class="flex items-center justify-between gap-2">
                                                <p class="text-sm font-black text-koya">&euro;{{ number_format((float) $item->price, 2) }}</p>
                                                <p class="text-[10px] text-white/50">Sort {{ $item->sort_order }}</p>
                                            </div>
                                        </div>

                                        <div x-show="openEdit" x-cloak class="px-2.5 pb-2.5 pt-2 border-t border-white/10">
                                            @if($item->is_package && $item->package_details)
                                                <div class="mb-2 rounded-lg border border-amber-500/20 bg-amber-500/5 p-2 text-[11px] text-amber-200">
                                                    {{ $item->package_details }}
                                                </div>
                                            @endif

                                            <form action="{{ route('menu-items.update', $item) }}" method="POST" class="grid grid-cols-1 gap-1.5">
                                                @csrf
                                                @method('PUT')

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                                    <div>
                                                        <label class="menu-label" for="name-{{ $item->id }}">Name</label>
                                                        <input id="name-{{ $item->id }}" class="menu-field" type="text" name="name" value="{{ $item->name }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="menu-label" for="category-{{ $item->id }}">Category</label>
                                                        <select id="category-{{ $item->id }}" class="menu-field" name="category" required>
                                                            @foreach($categoryOptions as $categoryOption)
                                                                <option value="{{ $categoryOption }}" {{ $item->category === $categoryOption ? 'selected' : '' }}>
                                                                    {{ $categoryOption }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-1.5">
                                                    <div>
                                                        <label class="menu-label" for="price-{{ $item->id }}">Price</label>
                                                        <input id="price-{{ $item->id }}" class="menu-field" type="number" step="0.01" min="0" name="price" value="{{ $item->price }}" required>
                                                    </div>
                                                    <div>
                                                        <label class="menu-label" for="sort-{{ $item->id }}">Sort</label>
                                                        <input id="sort-{{ $item->id }}" class="menu-field" type="number" min="0" max="999" name="sort_order" value="{{ $item->sort_order }}" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-1.5">
                                                    <label class="menu-toggle">
                                                        <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="menu-checkbox">
                                                        <span class="menu-toggle-switch"></span>
                                                        <span class="menu-toggle-text">Active</span>
                                                    </label>
                                                    <label class="menu-toggle">
                                                        <input type="checkbox" name="is_package" value="1" x-model="editIsPackage" class="menu-checkbox">
                                                        <span class="menu-toggle-switch"></span>
                                                        <span class="menu-toggle-text">Package</span>
                                                    </label>
                                                </div>

                                                <div x-show="editIsPackage" x-cloak>
                                                    <label class="menu-label" for="package-{{ $item->id }}">Package Details</label>
                                                    <textarea id="package-{{ $item->id }}" class="menu-field" name="package_details" rows="2" placeholder="Package details">{{ $item->package_details }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-2 gap-1.5 pt-0.5">
                                                    <button type="submit" class="btn-koya w-full py-2 text-[10px]">Save</button>
                                                    <button type="submit" form="delete-menu-item-{{ $item->id }}" class="w-full py-2 rounded-2xl border border-red-500/20 bg-red-500/10 text-red-400 font-black uppercase text-[10px] tracking-widest">Delete</button>
                                                </div>
                                            </form>

                                            <form id="delete-menu-item-{{ $item->id }}" action="{{ route('menu-items.destroy', $item) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @empty
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
                            <p class="text-sm text-white/60">No menu items yet. Create your first one with the Add Menu Item button.</p>
                        </div>
                    @endforelse
                </div>
        </div>

        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" @click="showCreateModal = false"></div>

            <div x-show="showCreateModal" x-transition class="relative w-full max-w-xl glass rounded-[30px] border border-white/10 p-6 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base sm:text-lg font-black uppercase tracking-widest text-koya">Add Menu Item</h2>
                        <p class="text-[11px] text-white/50 mt-1">Create polished menu entries with pricing and package options.</p>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="w-9 h-9 rounded-xl border border-white/15 bg-white/5 text-white/70 hover:text-white hover:border-white/30 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('menu-items.store') }}" method="POST" class="mt-5 grid grid-cols-1 gap-3">
                    @csrf
                    <input type="hidden" name="create_form" value="1">

                    <div>
                        <label class="menu-label" for="menu-item-name">Item Name</label>
                        <input id="menu-item-name" class="menu-field" type="text" name="name" value="{{ old('name') }}" placeholder="Cheeseburger Deluxe" required>
                    </div>

                    <div>
                        <label class="menu-label" for="menu-item-category">Category</label>
                        <select id="menu-item-category" class="menu-field" name="category" required>
                            @foreach($categoryOptions as $categoryOption)
                                <option value="{{ $categoryOption }}" {{ old('category', 'Starters') === $categoryOption ? 'selected' : '' }}>
                                    {{ $categoryOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="menu-label" for="menu-item-price">Price</label>
                            <input id="menu-item-price" class="menu-field" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="menu-label" for="menu-item-sort">Sort</label>
                            <input id="menu-item-sort" class="menu-field" type="number" min="0" max="999" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <label class="menu-toggle">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="menu-checkbox">
                            <span class="menu-toggle-switch"></span>
                            <span class="menu-toggle-text">Active</span>
                        </label>
                        <label class="menu-toggle">
                            <input type="checkbox" name="is_package" value="1" x-model="createIsPackage" {{ old('is_package') ? 'checked' : '' }} class="menu-checkbox">
                            <span class="menu-toggle-switch"></span>
                            <span class="menu-toggle-text">Package</span>
                        </label>
                    </div>

                    <div x-show="createIsPackage" x-cloak>
                        <label class="menu-label" for="menu-item-package-details">Package Details</label>
                        <textarea id="menu-item-package-details" class="menu-field" name="package_details" rows="2" placeholder="Starter + main + drink">{{ old('package_details') }}</textarea>
                        <p class="menu-help">Shown only when package is enabled.</p>
                    </div>

                    <div class="pt-1 flex items-center gap-2">
                        <button type="button" @click="showCreateModal = false" class="w-1/3 py-3 rounded-2xl border border-white/15 bg-white/5 text-white/80 text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn-koya w-2/3 py-3">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
