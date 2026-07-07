<x-app-layout>
    <div x-data="{ 
        showAddModal: false,
        showEditModal: false,
        editingCategory: { id: '', name: '', type: '' },
        editCategory(category) {
            this.editingCategory = { ...category };
            this.showEditModal = true;
        }
    }" class="space-y-6">
        {{-- ─── Header Section ─────────────────────────── --}}
        <div class="flex items-center justify-between border-b border-white/5 pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-indigo-500 rounded-full shadow-[0_0_15px_rgba(99,102,241,0.4)]"></div>
                <div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Payment Categories</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Manage Finance Classifications</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('finance.history') }}" class="w-10 h-10 bg-white/5 border border-white/10 text-white rounded-2xl flex items-center justify-center hover:bg-white/10 transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
                <button @click="showAddModal = true" class="w-10 h-10 bg-indigo-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20 hover:scale-110 active:scale-95 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-xs font-bold uppercase tracking-wider">
                {{ session('success') }}
            </div>
        @endif

        {{-- ─── Categories List ────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Income Categories --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    <h4 class="text-xs font-black text-white uppercase tracking-widest">Income Categories</h4>
                </div>
                <div class="space-y-3">
                    @forelse($categories->where('type', 'income') as $category)
                        <div class="glass p-4 rounded-3xl border border-white/5 flex items-center justify-between group hover:border-white/10 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-green-500/10 text-green-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-white">{{ $category->name }}</span>
                            </div>
                            <button @click="editCategory({{ $category->toJson() }})" class="p-2 bg-white/5 border border-white/10 rounded-xl transition-all hover:bg-white/10 shadow-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="glass p-8 rounded-3xl border border-white/5 text-center text-white/20 uppercase text-[10px] font-black tracking-widest">
                            No income categories
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Expense Categories --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <h4 class="text-xs font-black text-white uppercase tracking-widest">Expense Categories</h4>
                </div>
                <div class="space-y-3">
                    @forelse($categories->where('type', 'expense') as $category)
                        <div class="glass p-4 rounded-3xl border border-white/5 flex items-center justify-between group hover:border-white/10 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-white">{{ $category->name }}</span>
                            </div>
                            <button @click="editCategory({{ $category->toJson() }})" class="p-2 bg-white/5 border border-white/10 rounded-xl transition-all hover:bg-white/10 shadow-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="glass p-8 rounded-3xl border border-white/5 text-center text-white/20 uppercase text-[10px] font-black tracking-widest">
                            No expense categories
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ─── Add Category Modal ─────────────────────── --}}
        <div x-show="showAddModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
            <div @click="showAddModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="glass w-full max-w-md rounded-[32px] p-8 relative border border-white/10" x-show="showAddModal" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Add Category</h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('finance.categories.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category Name</label>
                        <input type="text" name="name" required placeholder="e.g. Utilities, Salary, Sales..."
                               class="w-full glass p-4 rounded-2xl border border-white/10 text-white outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                        <select name="type" required class="w-full glass p-4 rounded-2xl border border-white/10 text-white outline-none focus:border-indigo-500">
                            <option value="expense" class="bg-gray-900">Expense</option>
                            <option value="income" class="bg-gray-900">Income</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-indigo-500 py-4 rounded-2xl text-white font-bold uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">Save Category</button>
                </form>
            </div>
        </div>

        {{-- ─── Edit Category Modal ────────────────────── --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
            <div @click="showEditModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="glass w-full max-w-md rounded-[32px] p-8 relative border border-white/10" x-show="showEditModal" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Edit Category</h3>
                    <button @click="showEditModal = false" class="text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="@js(route('finance.categories.update', ['category' => '__CATEGORY__'])).replace('__CATEGORY__', editingCategory.id)" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Category Name</label>
                        <input type="text" name="name" x-model="editingCategory.name" required
                               class="w-full glass p-4 rounded-2xl border border-white/10 text-white outline-none focus:border-indigo-500 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Type</label>
                        <select name="type" x-model="editingCategory.type" required class="w-full glass p-4 rounded-2xl border border-white/10 text-white outline-none focus:border-indigo-500">
                            <option value="expense" class="bg-gray-900">Expense</option>
                            <option value="income" class="bg-gray-900">Income</option>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" 
                                @click="if(confirm('Delete this category?')) $refs.deleteCategoryForm.action = @js(route('finance.categories.destroy', ['category' => '__CATEGORY__'])).replace('__CATEGORY__', editingCategory.id); $refs.deleteCategoryForm.submit()"
                                class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                            Delete
                        </button>
                        <button type="submit" class="flex-[3] bg-indigo-500 py-4 rounded-2xl text-white font-bold uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">Update Category</button>
                    </div>
                </form>
                <form method="POST" x-ref="deleteCategoryForm" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

