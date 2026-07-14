<x-app-layout>
    <div data-async-region="users-index" data-async-mutations="true">
        @if(session('success'))
            <div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif
        <div x-data="{
            showCreateModal: {{ $errors->any() && !old('user_id') ? 'true' : 'false' }},
            showEditModal: {{ $errors->any() && old('user_id') ? 'true' : 'false' }},
            editingUser: {
                id: '{{ old('user_id') }}',
                name: '{{ old('name') }}',
                role: '{{ old('role') }}'
            },
            editUser(user) {
                this.editingUser = {
                    id: user.id,
                    name: user.name,
                    role: user.role
                };
                this.showEditModal = true;
            }
        }" class="space-y-8">

            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-blue-500 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.4)]"></div>
                    <div>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">Staff Members</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-0.5">Manage access levels and passcodes</p>
                    </div>
                </div>
                <button @click="showCreateModal = true" class="w-10 h-10 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 hover:scale-110 active:scale-95 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                @foreach($users as $user)
                    <div class="glass p-4 rounded-[24px] border border-white/5 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center text-koya font-black text-xl border border-white/10 uppercase">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-white font-bold text-lg leading-tight">{{ $user->name }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $user->isAdmin() ? 'bg-koya/20 text-koya' : 'bg-white/10 text-gray-400' }}">
                                        {{ $user->role }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 font-medium uppercase tracking-[0.15em] mt-1">
                                    Last login:
                                    <span class="text-gray-400 normal-case tracking-normal">
                                        {{ $user->last_login_at?->format('d/m/Y H:i') ?? 'Never' }}
                                    </span>
                                </p>
                                <p class="text-[11px] text-gray-500 font-medium">{{ __('Protected with a 6-digit passcode') }}</p>
                            </div>
                        </div>
                        <button @click="editUser({{ $user->toJson() }})" class="p-2.5 bg-white/5 rounded-2xl hover:bg-white/10 transition-colors border border-white/5">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <div x-show="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;" x-cloak>
                <div @click="showCreateModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="glass w-full max-w-md rounded-[32px] p-8 relative border border-white/10" x-show="showCreateModal" x-transition>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">New Staff Member</h3>
                        <button @click="showCreateModal = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    @if ($errors->any() && !old('user_id'))
                        <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
                            <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label>Display Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div>
                            <label>Role</label>
                            <select name="role" required>
                                <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Waiter</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label>6-Digit Passcode</label>
                                <input type="password" name="passcode" inputmode="numeric" pattern="[0-9]*" maxlength="6" required>
                            </div>
                            <div>
                                <label>Confirm</label>
                                <input type="password" name="passcode_confirmation" inputmode="numeric" pattern="[0-9]*" maxlength="6" required>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full btn-koya py-4">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;" x-cloak>
                <div @click="showEditModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="glass w-full max-w-md rounded-[32px] p-8 relative border border-white/10" x-show="showEditModal" x-transition>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Edit Staff</h3>
                        <button @click="showEditModal = false" class="text-gray-400"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>

                    @if ($errors->any() && old('user_id'))
                        <div class="mb-4 bg-red-500/20 border border-red-500/50 p-3 rounded-xl text-xs text-red-200">
                            <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                        </div>
                    @endif

                    <form :action="@js(route('users.update', ['user' => '__USER__'])).replace('__USER__', editingUser.id)" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" :value="editingUser.id">

                        <div>
                            <label>Display Name</label>
                            <input type="text" name="name" x-model="editingUser.name" required>
                        </div>
                        <div>
                            <label>Role</label>
                            <select name="role" x-model="editingUser.role" required>
                                <option value="waiter">Waiter</option>
                                <option value="admin">Admin</option>
                                <option value="owner">Owner</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Change Passcode (Leave blank to keep current)</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label>New Passcode</label>
                                    <input type="password" name="passcode" inputmode="numeric" pattern="[0-9]*" maxlength="6">
                                </div>
                                <div>
                                    <label>Confirm</label>
                                    <input type="password" name="passcode_confirmation" inputmode="numeric" pattern="[0-9]*" maxlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button"
                                    @click="if(confirm('Delete this user? This cannot be undone.')) $refs.deleteUserForm.submit()"
                                    class="flex-1 bg-red-500/10 text-red-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500/20 transition-all border border-red-500/20 active:scale-95">
                                Delete
                            </button>
                            <button type="submit" class="flex-[3] btn-koya py-4">Save Changes</button>
                        </div>
                    </form>

                    <form :action="@js(route('users.destroy', ['user' => '__USER__'])).replace('__USER__', editingUser.id)" method="POST" x-ref="deleteUserForm" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
