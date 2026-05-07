<x-app-layout>
    <x-slot name="header">
        Restaurant Config
    </x-slot>

    <style>
        .compact-input {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
            border-radius: 12px !important;
            font-size: 0.875rem !important;
        }
        .slot-group {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 20px;
        }
    </style>

    <div class="space-y-6 pb-12">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl text-sm font-bold uppercase tracking-wider">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl text-sm font-bold uppercase tracking-wider">
                {{ session('error') }}
            </div>
        @endif

        <!-- Weekly Schedule -->
        @if(auth()->user()->isUserAdmin())
        <div class="glass rounded-[32px] p-4 lg:p-8">
            <h3 class="text-xl font-black uppercase tracking-tight mb-6 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-koya rounded-full"></span>
                Weekly Schedule
            </h3>

            <form action="{{ route('restaurant.schedule.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    @endphp
                    @foreach($schedules as $schedule)
                        <div class="p-4 lg:p-6 rounded-[24px] bg-white/5 border border-white/5" x-data="{ closed: {{ $schedule->is_closed ? 'true' : 'false' }} }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="font-black text-lg uppercase tracking-widest text-white">{{ $days[$schedule->day_of_week] }}</span>
                                    <span x-show="closed" class="px-2 py-0.5 bg-red-500/20 text-red-500 text-[10px] font-black uppercase rounded-lg">Closed</span>
                                    <span x-show="!closed" class="px-2 py-0.5 bg-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase rounded-lg">Open</span>
                                </div>
                                
                                <div class="flex items-center gap-3 bg-white/5 p-2 px-4 rounded-2xl border border-white/5">
                                    <input type="checkbox" name="schedules[{{ $schedule->id }}][is_closed]" id="closed_{{ $schedule->id }}" value="1" class="w-5 h-5 !p-0 !bg-transparent rounded border-white/20 text-koya focus:ring-koya" x-model="closed" {{ $schedule->is_closed ? 'checked' : '' }}>
                                    <label for="closed_{{ $schedule->id }}" class="!mb-0 !pl-0 text-[10px] font-black uppercase tracking-widest">Mark as Closed</label>
                                </div>
                            </div>

                            <div x-show="!closed" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="slot-group">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-koya mb-3 opacity-60">Session 1</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-[9px] mb-1">Open</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][open_time_1]" value="{{ substr($schedule->open_time_1, 0, 5) }}" class="compact-input">
                                        </div>
                                        <div>
                                            <label class="text-[9px] mb-1">Close</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][close_time_1]" value="{{ substr($schedule->close_time_1, 0, 5) }}" class="compact-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="slot-group">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-koya mb-3 opacity-60">Session 2</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-[9px] mb-1">Open</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][open_time_2]" value="{{ substr($schedule->open_time_2, 0, 5) }}" class="compact-input">
                                        </div>
                                        <div>
                                            <label class="text-[9px] mb-1">Close</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][close_time_2]" value="{{ substr($schedule->close_time_2, 0, 5) }}" class="compact-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="slot-group">
                                    <div class="text-[10px] font-black uppercase tracking-widest text-koya mb-3 opacity-60">Session 3</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="text-[9px] mb-1">Open</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][open_time_3]" value="{{ substr($schedule->open_time_3, 0, 5) }}" class="compact-input">
                                        </div>
                                        <div>
                                            <label class="text-[9px] mb-1">Close</label>
                                            <input type="time" name="schedules[{{ $schedule->id }}][close_time_3]" value="{{ substr($schedule->close_time_3, 0, 5) }}" class="compact-input">
                                        </div>
                                    </div>
                                </div>

                                <div class="slot-group flex flex-col justify-center">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-koya mb-3 opacity-60">Max Capacity</label>
                                    <input type="number" name="schedules[{{ $schedule->id }}][max_party_size]" value="{{ $schedule->max_party_size }}" min="1" class="compact-input">
                                    <span class="text-[8px] mt-1 text-white/20 italic">Per time slot</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn-koya !py-3 !px-8 text-sm">
                        Update All Schedules
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Schedule Exceptions -->
        <div class="glass rounded-[32px] p-4 lg:p-8">
            <h3 class="text-xl font-black uppercase tracking-tight mb-6 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-koya rounded-full"></span>
                Exceptions & Holidays
            </h3>

            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Add Exception Form -->
                <div class="lg:col-span-4" x-data="{ closed: false }">
                    <div class="p-6 rounded-[24px] bg-white/5 border border-white/10">
                        <h4 class="text-sm font-black uppercase tracking-widest text-white mb-6">Add New Exception</h4>
                        <form action="{{ route('restaurant.exceptions.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label>Date</label>
                                <input type="date" name="date" required min="{{ date('Y-m-d') }}" class="compact-input">
                            </div>

                            <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                                <input type="checkbox" name="is_closed" id="exc_closed" class="w-5 h-5 !p-0 !bg-transparent rounded border-white/20 text-koya focus:ring-koya" x-model="closed">
                                <label for="exc_closed" class="!mb-0 !pl-0 text-[10px] font-black uppercase tracking-widest">Mark as closed (Holiday)</label>
                            </div>

                            <div x-show="!closed" x-cloak class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px]">Open 1</label>
                                        <input type="time" name="open_time_1" class="compact-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px]">Close 1</label>
                                        <input type="time" name="close_time_1" class="compact-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px]">Open 2</label>
                                        <input type="time" name="open_time_2" class="compact-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px]">Close 2</label>
                                        <input type="time" name="close_time_2" class="compact-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[9px]">Open 3</label>
                                        <input type="time" name="open_time_3" class="compact-input">
                                    </div>
                                    <div>
                                        <label class="text-[9px]">Close 3</label>
                                        <input type="time" name="close_time_3" class="compact-input">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label>Capacity (Optional)</label>
                                <input type="number" name="max_party_size" placeholder="Defaults to 25" class="compact-input">
                            </div>
                            <div>
                                <label>Description</label>
                                <input type="text" name="description" placeholder="e.g. Christmas Eve" class="compact-input">
                            </div>
                            <button type="submit" class="btn-koya w-full !py-3 text-sm mt-2">
                                Create Exception
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Exceptions List -->
                <div class="lg:col-span-8 space-y-3">
                    <h4 class="text-sm font-black uppercase tracking-widest text-white mb-4 flex items-center gap-2">
                        Upcoming Exceptions
                        @if(!$exceptions->isEmpty())
                            <span class="bg-koya px-2 py-0.5 rounded-full text-[10px]">{{ $exceptions->count() }}</span>
                        @endif
                    </h4>

                    @if($exceptions->isEmpty())
                        <div class="text-center py-12 rounded-[24px] border-2 border-dashed border-white/5 text-white/20 italic text-sm">
                            No upcoming exceptions scheduled.
                        </div>
                    @else
                        @foreach($exceptions as $exception)
                            <div class="flex items-center justify-between p-4 rounded-[20px] bg-white/5 border border-white/5 hover:bg-white/[0.07] transition-all">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold uppercase tracking-wider text-sm">{{ \Carbon\Carbon::parse($exception->date)->format('M d, Y') }}</span>
                                        @if($exception->is_closed)
                                            <span class="px-2 py-0.5 bg-red-500/20 text-red-500 text-[10px] font-black uppercase rounded-lg">Closed</span>
                                        @endif
                                        @if($exception->description)
                                            <span class="text-white/40 text-xs font-medium">— {{ $exception->description }}</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-white/30 mt-1 font-bold tracking-tight">
                                        @if(!$exception->is_closed)
                                            <span class="text-koya/60">{{ substr($exception->open_time_1, 0, 5) }} - {{ substr($exception->close_time_1, 0, 5) }}</span>
                                            @if($exception->open_time_2) / <span class="text-koya/60">{{ substr($exception->open_time_2, 0, 5) }} - {{ substr($exception->close_time_2, 0, 5) }}</span> @endif
                                            @if($exception->open_time_3) / <span class="text-koya/60">{{ substr($exception->open_time_3, 0, 5) }} - {{ substr($exception->close_time_3, 0, 5) }}</span> @endif
                                            @if($exception->max_party_size) <span class="ml-2 text-white/20">|</span> <span class="ml-2">Cap: {{ $exception->max_party_size }}</span> @endif
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('restaurant.exceptions.destroy', $exception) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-red-500/10 text-white/10 hover:text-red-500 transition-all border border-transparent hover:border-red-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endif
        </div>
    </div>
    </div>
</div>
</x-app-layout>
