<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessHourController extends Controller
{
    public function index(): View
    {
        $businessHours = auth()->user()->businessHours()
            ->orderBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        return view('provider.business-hours.index', compact('businessHours'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'hours' => ['required', 'array'],
            'hours.*.is_active' => ['boolean'],
            'hours.*.start_time' => ['required_if:hours.*.is_active,1', 'nullable', 'date_format:H:i'],
            'hours.*.end_time' => ['required_if:hours.*.is_active,1', 'nullable', 'date_format:H:i', 'after:hours.*.start_time'],
            'hours.*.break_start' => ['nullable', 'date_format:H:i'],
            'hours.*.break_end' => ['nullable', 'date_format:H:i', 'after:hours.*.break_start'],
        ]);

        $provider = auth()->user();

        foreach ($request->hours as $day => $data) {
            $provider->businessHours()->updateOrCreate(
                ['day_of_week' => $day],
                [
                    'start_time' => $data['start_time'] ?? '09:00',
                    'end_time' => $data['end_time'] ?? '18:00',
                    'break_start' => $data['break_start'] ?? null,
                    'break_end' => $data['break_end'] ?? null,
                    'is_active' => isset($data['is_active']),
                ]
            );
        }

        return redirect()->route('provider.business-hours.index')
            ->with('success', 'Horarios atualizados com sucesso!');
    }

    public function destroy(BusinessHour $businessHour): RedirectResponse
    {
        if ($businessHour->provider_id !== auth()->id()) {
            abort(403);
        }

        $businessHour->delete();

        return redirect()->route('provider.business-hours.index')
            ->with('success', 'Horario removido!');
    }
}
