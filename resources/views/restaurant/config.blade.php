<x-app-layout>
    <div class="config-page" data-async-region="restaurant-config" data-async-mutations="true">
        @if(session('success'))
            <div class="hidden" data-async-feedback data-feedback-level="success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ session('error') }}</div>
        @elseif($errors->any())
            <div class="hidden" data-async-feedback data-feedback-level="error">{{ $errors->first() }}</div>
        @endif

        <style>
            .config-page {
                --config-ink: #392c24;
                --config-rust: #b95738;
                --config-muted: #806d60;
                --config-line: #e6d8cb;
                --config-cream: #fbf6ef;
                max-width: 88rem;
                margin-inline: auto;
            }

            .config-panel {
                overflow: visible !important;
                border: 1px solid var(--config-line) !important;
                background: #ffffff !important;
                box-shadow: 0 8px 24px rgba(68, 44, 29, 0.06) !important;
                backdrop-filter: none !important;
            }

            .config-hero {
                position: relative;
                overflow: hidden;
                margin-bottom: 0.75rem;
                padding: 1rem;
                border-radius: 1.35rem;
                color: #fff8ee;
                background: linear-gradient(135deg, #2d2119 0%, #533525 58%, #895038 100%);
                box-shadow: 0 18px 38px rgba(53, 38, 29, 0.16);
            }

            .config-hero::after {
                content: "";
                position: absolute;
                top: -4.5rem;
                right: -3rem;
                width: 12rem;
                height: 12rem;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 999px;
                background: rgba(237, 183, 93, 0.12);
            }

            .config-summary {
                position: relative;
                z-index: 1;
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                margin-top: 0.8rem;
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.11);
                border-radius: 0.8rem;
                background: rgba(255, 255, 255, 0.07);
            }

            .config-summary div {
                min-width: 0;
                padding: 0.55rem;
                text-align: center;
            }

            .config-summary div + div {
                border-left: 1px solid rgba(255, 255, 255, 0.1);
            }

            .config-summary span {
                display: block;
                color: rgba(255, 255, 255, 0.5);
                font-size: 0.52rem;
                font-weight: 900;
                text-transform: uppercase;
            }

            .config-summary strong {
                display: block;
                overflow: hidden;
                color: #fff8ee;
                font-size: 0.72rem;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .config-tabs {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.25rem;
                margin-bottom: 0.75rem;
                padding: 0.3rem;
                border: 1px solid var(--config-line);
                border-radius: 0.9rem;
                background: #ffffff;
                box-shadow: 0 5px 16px rgba(68, 44, 29, 0.05);
            }

            .config-tabs button {
                min-height: 2.65rem;
                border-radius: 0.7rem;
                color: #725f53;
                font-size: 0.68rem;
                font-weight: 900;
            }

            .config-tabs button.active {
                color: #ffffff;
                background: var(--config-rust);
                box-shadow: 0 5px 12px rgba(185, 87, 56, 0.22);
            }

            .config-section-heading {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .config-section-heading h3,
            .config-panel h4 {
                color: var(--config-ink) !important;
            }

            .config-section-heading p {
                color: var(--config-muted) !important;
            }

            .config-section-heading > div > span {
                background: var(--config-rust) !important;
            }

            .config-input {
                width: 100% !important;
                min-height: 3rem !important;
                padding: 0.65rem 0.8rem !important;
                border-radius: 0.8rem !important;
                font-size: 1rem !important;
                color: var(--config-ink) !important;
                background: #ffffff !important;
                border: 1px solid #ddcfc2 !important;
            }

            .config-label {
                display: block;
                margin-bottom: 0.4rem !important;
                color: #475569 !important;
                font-size: 0.65rem !important;
                font-weight: 900 !important;
                letter-spacing: 0.12em;
                text-transform: uppercase;
            }

            .day-card {
                overflow: hidden;
                border: 1px solid #dbe3ee;
                border-radius: 1rem;
                background: #ffffff;
            }

            .day-card-header {
                width: 100%;
                min-height: 3.75rem;
                padding: 0.75rem 0.9rem;
                text-align: left;
            }

            .day-card-body {
                padding: 0 0.9rem 0.9rem;
            }

            .day-name {
                color: var(--config-ink) !important;
            }

            .day-summary {
                color: var(--config-muted) !important;
            }

            .day-card-header > span {
                width: 2rem !important;
                height: 2rem !important;
                color: #8b7567 !important;
                background: var(--config-cream) !important;
            }

            .session-card {
                padding: 0.9rem;
                border: 1px solid #dbe3ee;
                border-radius: 1rem;
                background: var(--config-cream);
            }

            .config-page .time-pair {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0.65rem !important;
            }

            .config-page label.closed-toggle {
                display: flex !important;
                min-height: 3.25rem;
                margin-bottom: 0.75rem !important;
                color: inherit !important;
                font-size: inherit !important;
                letter-spacing: normal !important;
                text-transform: none !important;
                cursor: pointer;
                user-select: none;
            }

            .config-page .closed-toggle {
                border-color: var(--config-line) !important;
                background: var(--config-cream) !important;
            }

            .config-page .closed-toggle .text-white {
                color: var(--config-ink) !important;
            }

            .config-page .closed-toggle .text-slate-500 {
                color: var(--config-muted) !important;
            }

            .config-page input.sr-only {
                position: absolute !important;
                width: 1px !important;
                height: 1px !important;
                min-height: 1px !important;
                margin: -1px !important;
                padding: 0 !important;
                overflow: hidden !important;
                clip: rect(0, 0, 0, 0) !important;
                white-space: nowrap !important;
                border: 0 !important;
            }

            .toggle-track {
                position: relative;
                width: 3rem;
                height: 1.7rem;
                flex: 0 0 auto;
                border-radius: 999px;
                background: #d7c9bd;
                transition: background-color 160ms ease;
            }

            .toggle-track::after {
                content: '';
                position: absolute;
                top: 0.2rem;
                left: 0.2rem;
                width: 1.3rem;
                height: 1.3rem;
                border-radius: 999px;
                background: white;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.28);
                transition: transform 160ms ease;
            }

            .toggle-track.is-closed {
                background: #ef4444;
            }

            .toggle-track.is-closed::after {
                transform: translateX(1.3rem);
            }

            .schedule-save-bar {
                z-index: 25;
                margin-top: 1.25rem;
                padding: 0.75rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1rem;
                background: rgba(15, 23, 42, 0.88);
                box-shadow: 0 18px 50px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(16px);
            }

            .exception-card {
                border: 1px solid #dbe3ee;
                border-radius: 1rem;
                background: #ffffff;
            }

            .exception-card .text-white {
                color: var(--config-ink) !important;
            }

            .exception-card .text-white\/50,
            .exception-card .text-slate-500 {
                color: var(--config-muted) !important;
            }

            .exception-form {
                border-color: var(--config-line) !important;
                background: var(--config-cream) !important;
            }

            @media (max-width: 767px) {
                .config-page {
                    margin-inline: -0.15rem;
                }

                .config-panel {
                    padding: 0.8rem !important;
                    border-radius: 1rem !important;
                }

                .config-section-heading {
                    margin-bottom: 1rem;
                }

                .config-section-heading h3 {
                    font-size: 0.95rem !important;
                }

                .day-card-header {
                    min-height: 3.6rem;
                    padding: 0.7rem 0.8rem;
                }

                .day-card-body {
                    padding: 0 0.8rem 0.8rem;
                }

                .session-card {
                    padding: 0.75rem;
                }

                .config-page .time-pair {
                    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                    gap: 0.5rem !important;
                }

                .config-input {
                    min-height: 2.8rem !important;
                    padding-inline: 0.65rem !important;
                    font-size: 1rem !important;
                }

                .schedule-save-bar {
                    position: sticky;
                    bottom: calc(0.65rem + env(safe-area-inset-bottom));
                }

                .schedule-save-bar button {
                    width: 100%;
                    min-height: 3.25rem;
                }
            }

            @media (min-width: 768px) {
                .config-hero {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 1.2rem 1.35rem;
                }

                .config-summary {
                    width: 24rem;
                    margin-top: 0;
                }

                .config-tabs {
                    display: none;
                }

                .schedule-save-bar {
                    display: flex;
                    justify-content: flex-end;
                    border: 0;
                    background: transparent;
                    box-shadow: none;
                    backdrop-filter: none;
                }
            }
        </style>

        <div class="pb-12" x-data="{ view: 'hours', desktop: window.innerWidth >= 768, init() { window.addEventListener('resize', () => this.desktop = window.innerWidth >= 768) } }">
            <header class="config-hero">
                <div class="relative z-[1]">
                    <p class="text-[9px] font-black uppercase tracking-[.24em] text-[#efbb77]">Restaurant operations</p>
                    <h1 class="mt-1 font-display text-3xl font-bold leading-none">Service Settings</h1>
                    <p class="mt-2 text-[10px] text-white/55">Opening hours, booking capacity and special dates.</p>
                </div>
                <div class="config-summary">
                    <div><span>Service days</span><strong>{{ $schedules->where('is_closed', false)->count() }}</strong></div>
                    <div><span>Exceptions</span><strong>{{ $exceptions->count() }}</strong></div>
                    <div><span>Booking mode</span><strong>Schedule</strong></div>
                </div>
            </header>

            <nav class="config-tabs" aria-label="Configuration sections">
                <button type="button" @click="view='hours'" :class="view === 'hours' && 'active'">Weekly hours</button>
                <button type="button" @click="view='exceptions'" :class="view === 'exceptions' && 'active'">Special dates</button>
            </nav>

            <div class="space-y-4">
            @if(auth()->user()->isAdmin())
                <section x-show="desktop || view === 'hours'" class="config-panel glass rounded-[28px] p-4 md:p-6 lg:p-8">
                    <div class="config-section-heading">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 h-8 w-1.5 shrink-0 rounded-full bg-accent"></span>
                            <div>
                                <h3 class="text-xl font-black uppercase tracking-tight text-white">Weekly Schedule</h3>
                                <p class="mt-1 text-xs leading-relaxed text-white/40">Tap a day to edit service hours and booking capacity.</p>
                            </div>
                        </div>
                        <span class="shrink-0 rounded-full bg-white/5 px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-500">
                            {{ $schedules->count() }} days
                        </span>
                    </div>

                    <form action="{{ route('restaurant.schedule.update') }}" method="POST" x-data="{ activeDay: null }">
                        @csrf
                        @method('PUT')

                        @php
                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        @endphp

                        <div class="space-y-3">
                            @forelse($schedules as $schedule)
                                @php
                                    $isClosed = (bool) $schedule->is_closed;
                                    $sessions = collect([
                                        [$schedule->open_time_1, $schedule->close_time_1],
                                        [$schedule->open_time_2, $schedule->close_time_2],
                                        [$schedule->open_time_3, $schedule->close_time_3],
                                    ])->filter(fn ($session) => filled($session[0]) && filled($session[1]));
                                @endphp

                                <article
                                    class="day-card"
                                    x-data="{ closed: {{ $isClosed ? 'true' : 'false' }}, showExtra: {{ ($schedule->open_time_2 || $schedule->open_time_3) ? 'true' : 'false' }} }"
                                >
                                    <button
                                        type="button"
                                        class="day-card-header flex items-center justify-between gap-3"
                                        @click="activeDay = activeDay === {{ $schedule->id }} ? null : {{ $schedule->id }}"
                                        :aria-expanded="(activeDay === {{ $schedule->id }}).toString()"
                                        aria-controls="schedule-day-{{ $schedule->id }}"
                                    >
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="day-name text-base font-black uppercase tracking-wide md:text-lg">{{ $days[$schedule->day_of_week] }}</span>
                                                <span x-show="closed" class="rounded-full bg-red-500/15 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-red-400">Closed</span>
                                                <span x-show="!closed" class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-emerald-400">Open</span>
                                            </div>

                                            <div x-show="activeDay !== {{ $schedule->id }}" class="day-summary mt-0.5 truncate text-[11px] font-semibold">
                                                <template x-if="closed">
                                                    <span>No service</span>
                                                </template>
                                                <template x-if="!closed">
                                                    <span>
                                                        @forelse($sessions as $session)
                                                            {{ substr($session[0], 0, 5) }} - {{ substr($session[1], 0, 5) }}@if(!$loop->last) / @endif
                                                        @empty
                                                            Add service hours
                                                        @endforelse
                                                    </span>
                                                </template>
                                            </div>
                                        </div>

                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 text-white/50">
                                            <svg class="h-4 w-4 transition-transform duration-200" :class="activeDay === {{ $schedule->id }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    <div id="schedule-day-{{ $schedule->id }}" class="day-card-body" x-show="activeDay === {{ $schedule->id }}" x-cloak>
                                        <label for="closed_{{ $schedule->id }}" class="closed-toggle mb-3 flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-2.5">
                                            <span>
                                                <span class="block text-sm font-bold text-white">Closed all day</span>
                                                <span class="block text-[11px] text-slate-500">No reservations will be available.</span>
                                            </span>
                                            <input
                                                type="checkbox"
                                                name="schedules[{{ $schedule->id }}][is_closed]"
                                                id="closed_{{ $schedule->id }}"
                                                value="1"
                                                class="sr-only"
                                                x-model="closed"
                                                {{ $isClosed ? 'checked' : '' }}
                                            >
                                            <span class="toggle-track" :class="closed && 'is-closed'" aria-hidden="true"></span>
                                        </label>

                                        <div x-show="!closed" x-cloak class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                                            @foreach([1, 2, 3] as $sessionNumber)
                                                @php
                                                    $openField = "open_time_{$sessionNumber}";
                                                    $closeField = "close_time_{$sessionNumber}";
                                                    $openValue = $schedule->{$openField} ? substr($schedule->{$openField}, 0, 5) : '';
                                                    $closeValue = $schedule->{$closeField} ? substr($schedule->{$closeField}, 0, 5) : '';
                                                @endphp
                                                <div class="session-card" x-show="{{ $sessionNumber }} === 1 || showExtra" x-cloak>
                                                    <div class="mb-2 text-[10px] font-black uppercase tracking-[0.16em] text-accent">Session {{ $sessionNumber }}</div>
                                                    <div class="time-pair">
                                                        <div>
                                                            <label for="{{ $openField }}_{{ $schedule->id }}" class="config-label">Open</label>
                                                            <input id="{{ $openField }}_{{ $schedule->id }}" type="time" name="schedules[{{ $schedule->id }}][{{ $openField }}]" value="{{ old("schedules.{$schedule->id}.{$openField}", $openValue) }}" class="config-input">
                                                        </div>
                                                        <div>
                                                            <label for="{{ $closeField }}_{{ $schedule->id }}" class="config-label">Close</label>
                                                            <input id="{{ $closeField }}_{{ $schedule->id }}" type="time" name="schedules[{{ $schedule->id }}][{{ $closeField }}]" value="{{ old("schedules.{$schedule->id}.{$closeField}", $closeValue) }}" class="config-input">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <button x-show="!showExtra" type="button" class="flex min-h-11 items-center justify-center gap-2 rounded-xl border border-dashed border-accent/30 px-3 text-xs font-black uppercase tracking-wider text-accent" @click="showExtra = true">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                                Add another session
                                            </button>

                                            <div class="session-card flex flex-col justify-center">
                                                <label for="capacity_{{ $schedule->id }}" class="config-label">Maximum party size</label>
                                                <input id="capacity_{{ $schedule->id }}" type="number" inputmode="numeric" name="schedules[{{ $schedule->id }}][max_party_size]" value="{{ old("schedules.{$schedule->id}.max_party_size", $schedule->max_party_size) }}" min="1" class="config-input">
                                                <span class="mt-1.5 text-[10px] text-slate-500">Maximum guests per reservation</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-2xl border border-amber-400/20 bg-amber-400/10 p-5 text-sm text-amber-200">
                                    No weekly schedule is saved yet. Run the restaurant schedule database seeder first.
                                </div>
                            @endforelse
                        </div>

                        @if($schedules->isNotEmpty())
                            <div class="schedule-save-bar">
                                <button type="submit" class="btn-accent !px-8 !py-3 text-sm">
                                    Save Schedule Changes
                                </button>
                            </div>
                        @endif
                    </form>
                </section>
            @endif

            <section x-show="desktop || view === 'exceptions'" class="config-panel glass rounded-[28px] p-4 md:p-6 lg:p-8" x-data="{ formOpen: window.innerWidth >= 1024, closed: {{ old('is_closed') ? 'true' : 'false' }} }">
                <div class="config-section-heading">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-8 w-1.5 shrink-0 rounded-full bg-accent"></span>
                        <div>
                            <h3 class="text-xl font-black uppercase tracking-tight text-white">Exceptions & Holidays</h3>
                            <p class="mt-1 text-xs leading-relaxed text-white/40">Override normal hours for one specific date.</p>
                        </div>
                    </div>
                    <button type="button" class="flex min-h-11 shrink-0 items-center gap-2 rounded-xl bg-accent px-3 py-2 text-[10px] font-black uppercase tracking-wider text-white lg:hidden" @click="formOpen = !formOpen">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        <span x-text="formOpen ? 'Close' : 'Add'"></span>
                    </button>
                </div>

                <div class="grid gap-6 lg:grid-cols-12">
                    <div class="lg:col-span-4" x-show="formOpen" x-cloak>
                        <div class="exception-form rounded-2xl border border-white/10 bg-white/[0.035] p-4 md:p-5">
                            <h4 class="mb-5 text-sm font-black uppercase tracking-widest text-white">Add Exception</h4>
                            <form action="{{ route('restaurant.exceptions.store') }}" method="POST" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="exception_date" class="config-label">Date</label>
                                    <input id="exception_date" type="date" name="date" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}" class="config-input">
                                </div>

                                <label for="exc_closed" class="closed-toggle flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/[0.035] px-4 py-2.5">
                                    <span>
                                        <span class="block text-sm font-bold text-white">Closed all day</span>
                                        <span class="block text-[11px] text-slate-500">Holiday or special closure</span>
                                    </span>
                                    <input type="checkbox" name="is_closed" id="exc_closed" value="1" class="sr-only" x-model="closed" {{ old('is_closed') ? 'checked' : '' }}>
                                    <span class="toggle-track" :class="closed && 'is-closed'" aria-hidden="true"></span>
                                </label>

                                <div x-show="!closed" x-cloak class="space-y-3">
                                    @foreach([1, 2, 3] as $sessionNumber)
                                        <div class="session-card">
                                            <div class="mb-2 text-[10px] font-black uppercase tracking-[0.16em] text-accent">Session {{ $sessionNumber }}</div>
                                            <div class="time-pair">
                                                <div>
                                                    <label for="exception_open_{{ $sessionNumber }}" class="config-label">Open</label>
                                                    <input id="exception_open_{{ $sessionNumber }}" type="time" name="open_time_{{ $sessionNumber }}" value="{{ old("open_time_{$sessionNumber}") }}" class="config-input">
                                                </div>
                                                <div>
                                                    <label for="exception_close_{{ $sessionNumber }}" class="config-label">Close</label>
                                                    <input id="exception_close_{{ $sessionNumber }}" type="time" name="close_time_{{ $sessionNumber }}" value="{{ old("close_time_{$sessionNumber}") }}" class="config-input">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div>
                                    <label for="exception_capacity" class="config-label">Maximum party size (optional)</label>
                                    <input id="exception_capacity" type="number" inputmode="numeric" name="max_party_size" value="{{ old('max_party_size') }}" min="1" placeholder="Uses weekly capacity" class="config-input">
                                </div>

                                <div>
                                    <label for="exception_description" class="config-label">Description</label>
                                    <input id="exception_description" type="text" name="description" value="{{ old('description') }}" maxlength="255" placeholder="Example: Christmas Eve" class="config-input">
                                </div>

                                <button type="submit" class="btn-accent w-full !py-3 text-sm">Create Exception</button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-3 lg:col-span-8">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-black uppercase tracking-widest text-white">Upcoming Exceptions</h4>
                            @if($exceptions->isNotEmpty())
                                <span class="rounded-full bg-accent px-2.5 py-1 text-[10px] font-black text-white">{{ $exceptions->count() }}</span>
                            @endif
                        </div>

                        @forelse($exceptions as $exception)
                            <article class="exception-card flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-bold uppercase tracking-wide text-white">{{ \Carbon\Carbon::parse($exception->date)->format('M d, Y') }}</span>
                                        @if($exception->is_closed)
                                            <span class="rounded-full bg-red-500/15 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-red-400">Closed</span>
                                        @else
                                            <span class="rounded-full bg-emerald-500/15 px-2.5 py-1 text-[9px] font-black uppercase tracking-wider text-emerald-400">Special hours</span>
                                        @endif
                                    </div>

                                    @if($exception->description)
                                        <p class="mt-1 text-sm text-white/50">{{ $exception->description }}</p>
                                    @endif

                                    @if(!$exception->is_closed)
                                        <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-xs font-semibold text-slate-500">
                                            @foreach([1, 2, 3] as $sessionNumber)
                                                @php
                                                    $openField = "open_time_{$sessionNumber}";
                                                    $closeField = "close_time_{$sessionNumber}";
                                                @endphp
                                                @if($exception->{$openField} && $exception->{$closeField})
                                                    <span>{{ substr($exception->{$openField}, 0, 5) }} - {{ substr($exception->{$closeField}, 0, 5) }}</span>
                                                @endif
                                            @endforeach
                                            @if($exception->max_party_size)
                                                <span class="text-accent">Max {{ $exception->max_party_size }} guests</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <form action="{{ route('restaurant.exceptions.destroy', $exception) }}" method="POST" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex min-h-11 w-full items-center justify-center gap-2 rounded-xl border border-red-500/15 px-4 text-xs font-bold text-red-400 transition hover:bg-red-500/10 sm:w-auto" onclick="return confirm('Remove this schedule exception?')">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Remove
                                    </button>
                                </form>
                            </article>
                        @empty
                            <div class="rounded-2xl border-2 border-dashed border-white/10 px-5 py-10 text-center">
                                <svg class="mx-auto h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="mt-3 text-sm font-semibold text-slate-500">No upcoming exceptions</p>
                                <p class="mt-1 text-xs text-slate-400">Your weekly schedule applies normally.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
            </div>
        </div>
    </div>
</x-app-layout>
