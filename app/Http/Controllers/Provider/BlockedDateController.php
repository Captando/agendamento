<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\BlockedDate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlockedDateController extends Controller
{
    public function index(): View
    {
        $blockedDates = auth()->user()->blockedDates()
            ->orderBy('date')
            ->where('date', '>=', now()->toDateString())
            ->get();

        return view('provider.blocked-dates.index', compact('blockedDates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()->blockedDates()->create([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
        ]);

        return redirect()->route('provider.blocked-dates.index')
            ->with('success', 'Data bloqueada com sucesso!');
    }

    public function destroy(BlockedDate $blockedDate): RedirectResponse
    {
        if ($blockedDate->provider_id !== auth()->id()) {
            abort(403);
        }

        $blockedDate->delete();

        return redirect()->route('provider.blocked-dates.index')
            ->with('success', 'Bloqueio removido!');
    }
}
