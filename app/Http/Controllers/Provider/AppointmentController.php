<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->get('date', now()->toDateString());

        $appointments = auth()->user()->providerAppointments()
            ->with(['client', 'service'])
            ->where('date', $date)
            ->orderBy('start_time')
            ->get();

        return view('provider.appointments.index', compact('appointments', 'date'));
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        if ($appointment->provider_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'string', 'in:confirmed,completed,cancelled'],
        ]);

        $newStatus = AppointmentStatus::from($request->status);

        $appointment->update([
            'status' => $newStatus,
            'cancelled_at' => $newStatus === AppointmentStatus::Cancelled ? now() : $appointment->cancelled_at,
        ]);

        return back()->with('success', 'Status atualizado!');
    }
}
