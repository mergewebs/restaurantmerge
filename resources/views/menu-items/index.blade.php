<x-app-layout>
    @php
        $allProducts = $menuItems->flatten();
        $activeCount = $allProducts->where('is_active', true)->count();
    @endphp

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-500/25 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-3xl border border-red-500/25 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                <ul class="space-y-1 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="glass rounded-[28px] border border-white/10 p-5 sm:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.28em] text-koya/70">Owner Panel</p>
                    <h1 class="mt-2 text-2xl font-black text-white">Products</h1>
                    <p class="mt-1 max-w-2xl text-sm text-white/55">Create, update, and remove the products shown on the public site. Product photos uploaded here are used on the customer-facing product cards.</p>
                    <p class="mt-3 max-w-2xl rounded-2xl border border-koya/20 bg-koya/10 px-4 py-3 text-sm font-semibold text-koya/90">Production seeding now adds missing default products from the seeder without deleting the current menu. After that, the owner can manage products here without needing controller or developer changes.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/45">Total</p>
                        <p class="mt-1 text-xl font-black text-white">{{ $allProducts->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-emerald-300/70">Active</p>
                        <p class="mt-1 text-xl font-black text-emerald-300">{{ $activeCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 col-span-2 sm:col-span-1">
                        <p class="text-[10px] font-black uppercase tracking-[0.24em] text-white/45">Categories</p>
                        <p class="mt-1 text-xl font-black text-white">{{ count($categoryOptions) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="glass rounded-[28px] border border-white/10 p-5 sm:p-6" x-data="{ createIsPackage: {{ old('is_package') ? 'true' : 'false' }} }">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black text-white">Add New Product</h2>
                    <p class="text-sm text-white/50">Simple form for the owner. Uploading an image is optional but recommended.</p>
                </div>
            </div>

            <form action="{{ route('menu-items.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @csrf

                <div class="lg:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="product-name">Product name</label>
                        <input id="product-name" type="text" name="name" value="{{ old('name') }}" placeholder="KOYA Smash" required>
                    </div>
                    <div>
                        <label for="product-category">Category</label>
                        <select id="product-category" name="category" required>
                            @foreach($categoryOptions as $categoryOption)
                                <option value="{{ $categoryOption }}" {{ old('category', 'Smash Burger') === $categoryOption ? 'selected' : '' }}>{{ $categoryOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="product-price">Price</label>
                    <input id="product-price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                </div>
                <div>
                    <label for="product-sort-order">Display order</label>
                    <input id="product-sort-order" type="number" min="0" max="999" name="sort_order" value="{{ old('sort_order', 0) }}" placeholder="0">
                </div>

                <div class="lg:col-span-2">
                    <label for="product-description">Description</label>
                    <textarea id="product-description" name="description" rows="3" placeholder="Short description for customers">{{ old('description') }}</textarea>
                </div>

                <div class="lg:col-span-2 rounded-3xl border border-dashed border-white/15 bg-white/[0.03] p-4">
                    <label for="product-image">Product image</label>
                    <input id="product-image" type="file" name="image" accept="image/*" class="file:mr-4 file:rounded-xl file:border-0 file:bg-koya file:px-4 file:py-2 file:font-black file:text-[#17110c] file:cursor-pointer">
                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/35">JPG, PNG, WEBP. Up to 5 MB.</p>
                </div>

                <div class="lg:col-span-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-5 w-5 rounded border-white/20 bg-transparent">
                        <span>Visible on public page</span>
                    </label>
                    <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white sm:col-span-2">
                        <input type="checkbox" name="is_package" value="1" x-model="createIsPackage" {{ old('is_package') ? 'checked' : '' }} class="h-5 w-5 rounded border-white/20 bg-transparent">
                        <span>This is a package / combo</span>
                    </label>
                </div>

                <div class="lg:col-span-2" x-show="createIsPackage" x-cloak>
                    <label for="product-package-details">Package details</label>
                    <textarea id="product-package-details" name="package_details" rows="2" placeholder="For example: burger + fries + drink">{{ old('package_details') }}</textarea>
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit" class="btn-koya w-full sm:w-auto min-w-[220px]">Create Product</button>
                </div>
            </form>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black text-white">Existing Products</h2>
                    <p class="text-sm text-white/50">Tap any product card to edit or delete it.</p>
                </div>
            </div>

            @forelse($menuItems as $category => $items)
                <div class="glass rounded-[28px] border border-white/10 p-4 sm:p-5">
                    <div class="mb-4 flex items-center justify-between gap-3 border-b border-white/10 pb-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.28em] text-koya/70">Category</p>
                            <h3 class="mt-1 text-lg font-black text-white">{{ $category }}</h3>
                        </div>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[10px] font-black uppercase tracking-[0.24em] text-white/60">{{ $items->count() }} products</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($items as $item)
                            <details class="rounded-[24px] border border-white/10 bg-black/20 p-4" x-data="{ editIsPackage: {{ $item->is_package ? 'true' : 'false' }} }">
                                <summary class="cursor-pointer list-none">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex items-center gap-4 min-w-0">
                                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                                                @if($item->image_thumbnail_url)
                                                    <img src="{{ $item->image_thumbnail_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" loading="lazy" decoding="async" width="80" height="80">
                                                @else
                                                    <div class="flex h-full w-full items-center justify-center text-lg font-black uppercase text-white/35">
                                                        {{ \Illuminate\Support\Str::of($item->name)->substr(0, 2)->upper() }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h4 class="text-base font-black text-white">{{ $item->name }}</h4>
                                                    @if($item->is_active)
                                                        <span class="rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-emerald-300">Visible</span>
                                                    @else
                                                        <span class="rounded-full border border-white/10 bg-white/5 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-white/55">Hidden</span>
                                                    @endif
                                                    @if($item->is_package)
                                                        <span class="rounded-full border border-amber-500/20 bg-amber-500/10 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.2em] text-amber-300">Package</span>
                                                    @endif
                                                </div>
                                                <p class="mt-1 text-sm text-white/45">{{ $item->description ?: 'No description yet.' }}</p>
                                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-[0.18em] text-white/40">
                                                    <span>€{{ number_format((float) $item->price, 2) }}</span>
                                                    <span>Order {{ $item->sort_order }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm font-bold text-koya">
                                            <span>Edit</span>
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </summary>

                                <form action="{{ route('menu-items.update', $item) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid grid-cols-1 gap-4 border-t border-white/10 pt-5 lg:grid-cols-2">
                                    @csrf
                                    @method('PUT')

                                    <div class="lg:col-span-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="name-{{ $item->id }}">Product name</label>
                                            <input id="name-{{ $item->id }}" type="text" name="name" value="{{ $item->name }}" required>
                                        </div>
                                        <div>
                                            <label for="category-{{ $item->id }}">Category</label>
                                            <select id="category-{{ $item->id }}" name="category" required>
                                                @foreach($categoryOptions as $categoryOption)
                                                    <option value="{{ $categoryOption }}" {{ $item->category === $categoryOption ? 'selected' : '' }}>{{ $categoryOption }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="price-{{ $item->id }}">Price</label>
                                        <input id="price-{{ $item->id }}" type="number" step="0.01" min="0" name="price" value="{{ $item->price }}" required>
                                    </div>
                                    <div>
                                        <label for="sort-order-{{ $item->id }}">Display order</label>
                                        <input id="sort-order-{{ $item->id }}" type="number" min="0" max="999" name="sort_order" value="{{ $item->sort_order }}">
                                    </div>

                                    <div class="lg:col-span-2">
                                        <label for="description-{{ $item->id }}">Description</label>
                                        <textarea id="description-{{ $item->id }}" name="description" rows="3">{{ $item->description }}</textarea>
                                    </div>

                                    <div class="lg:col-span-2 rounded-3xl border border-dashed border-white/15 bg-white/[0.03] p-4">
                                        <label for="image-{{ $item->id }}">Replace product image</label>
                                        <input id="image-{{ $item->id }}" type="file" name="image" accept="image/*" class="file:mr-4 file:rounded-xl file:border-0 file:bg-koya file:px-4 file:py-2 file:font-black file:text-[#17110c] file:cursor-pointer">
                                        @if($item->image_url)
                                            <label class="mt-3 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white">
                                                <input type="checkbox" name="remove_image" value="1" class="h-5 w-5 rounded border-white/20 bg-transparent">
                                                <span>Remove current image</span>
                                            </label>
                                        @endif
                                    </div>

                                    <div class="lg:col-span-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white">
                                            <input type="checkbox" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }} class="h-5 w-5 rounded border-white/20 bg-transparent">
                                            <span>Visible on public page</span>
                                        </label>
                                        <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white sm:col-span-2">
                                            <input type="checkbox" name="is_package" value="1" x-model="editIsPackage" {{ $item->is_package ? 'checked' : '' }} class="h-5 w-5 rounded border-white/20 bg-transparent">
                                            <span>This is a package / combo</span>
                                        </label>
                                    </div>

                                    <div class="lg:col-span-2" x-show="editIsPackage" x-cloak>
                                        <label for="package-details-{{ $item->id }}">Package details</label>
                                        <textarea id="package-details-{{ $item->id }}" name="package_details" rows="2">{{ $item->package_details }}</textarea>
                                    </div>

                                    <div class="lg:col-span-2 flex flex-col gap-3 sm:flex-row sm:justify-end">
                                        <button type="submit" class="btn-koya w-full sm:w-auto min-w-[180px]">Save Changes</button>
                                        <button type="submit" form="delete-product-{{ $item->id }}" class="w-full sm:w-auto min-w-[180px] rounded-2xl border border-red-500/25 bg-red-500/10 px-6 py-4 text-sm font-black uppercase tracking-[0.22em] text-red-300 transition hover:bg-red-500/15">Delete Product</button>
                                    </div>
                                </form>

                                <form id="delete-product-{{ $item->id }}" action="{{ route('menu-items.destroy', $item) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </details>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="glass rounded-[28px] border border-white/10 p-8 text-center text-white/55">
                    No products yet. Use the form above to add the first one.
                </div>
            @endforelse
        </section>
    </div>
</x-app-layout>


