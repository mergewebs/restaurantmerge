<?php

namespace App\Http\Controllers;

use App\Models\DemoAccessSession;
use App\Models\DemoIpAddress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DemoAccessController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);
        $activeSince = now()->subMinutes(15);
        $query = DemoIpAddress::query()->withExists([
            'sessions as active_sessions_exists' => fn (Builder $query) => $query
                ->whereNull('ended_at')->where('last_activity_at', '>=', $activeSince),
        ]);

        $query->when($request->string('search')->isNotEmpty(), function (Builder $query) use ($request) {
            $search = '%'.$request->string('search')->toString().'%';
            $query->where(fn (Builder $query) => $query->where('ip_address', 'like', $search)
                ->orWhere('country', 'like', $search)->orWhere('city', 'like', $search)
                ->orWhere('last_page', 'like', $search));
        });
        $query->when($request->filled('date_from'), fn (Builder $q) => $q->whereDate('last_access_at', '>=', $request->date('date_from')));
        $query->when($request->filled('date_to'), fn (Builder $q) => $q->whereDate('last_access_at', '<=', $request->date('date_to')));
        foreach (['device_type' => 'device', 'browser' => 'browser', 'country' => 'country'] as $column => $input) {
            $query->when($request->filled($input), fn (Builder $q) => $q->where($column, $request->input($input)));
        }
        $query->when($request->input('status') === 'active', fn (Builder $q) => $q->where('is_blocked', false)->whereHas('sessions', fn (Builder $q) => $q->whereNull('ended_at')->where('last_activity_at', '>=', $activeSince)));
        $query->when($request->input('status') === 'inactive', fn (Builder $q) => $q->where(fn (Builder $q) => $q->where('is_blocked', true)->orWhereDoesntHave('sessions', fn (Builder $q) => $q->whereNull('ended_at')->where('last_activity_at', '>=', $activeSince))));

        $sorts = ['ip_address', 'first_access_at', 'last_access_at', 'visit_count', 'browser', 'device_type', 'country', 'last_page'];
        $sort = in_array($request->input('sort'), $sorts, true) ? $request->input('sort') : 'last_access_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        return view('demo-access.index', [
            'ipAddresses' => $query->orderBy($sort, $direction)->paginate(20)->withQueryString(),
            'summary' => [
                'unique' => DemoIpAddress::count(),
                'today' => DemoAccessSession::whereDate('started_at', today())->count(),
                'returning' => DemoIpAddress::where('visit_count', '>', 1)->count(),
                'active' => DemoAccessSession::whereNull('ended_at')->where('last_activity_at', '>=', $activeSince)
                    ->whereHas('ipAddress', fn (Builder $q) => $q->where('is_blocked', false))->count(),
            ],
            'filters' => [
                'devices' => DemoIpAddress::whereNotNull('device_type')->distinct()->orderBy('device_type')->pluck('device_type'),
                'browsers' => DemoIpAddress::whereNotNull('browser')->distinct()->orderBy('browser')->pluck('browser'),
                'countries' => DemoIpAddress::whereNotNull('country')->distinct()->orderBy('country')->pluck('country'),
            ],
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function show(Request $request, DemoIpAddress $demoIpAddress): View
    {
        $this->authorizeAdmin($request);

        return view('demo-access.show', [
            'ipAddress' => $demoIpAddress,
            'sessions' => $demoIpAddress->sessions()->withCount('history')->latest('started_at')->paginate(15, ['*'], 'sessions')->withQueryString(),
            'history' => $demoIpAddress->history()->with('session')->latest('accessed_at')->paginate(30, ['*'], 'history')->withQueryString(),
        ]);
    }

    public function toggleBlock(Request $request, DemoIpAddress $demoIpAddress): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $blocked = ! $demoIpAddress->is_blocked;
        $demoIpAddress->update(['is_blocked' => $blocked, 'blocked_at' => $blocked ? now() : null]);

        return back()->with('success', $blocked ? 'IP address blocked from demo access.' : 'IP address unblocked.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isUserAdmin(), 403);
    }
}
