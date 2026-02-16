<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentStatus;
use App\Events\AppointmentCancelled;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $appointments = auth()->user()->clientAppointments()
            ->with(['provider', 'service'])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->get();

        return view('client.appointments.index', compact('appointments'));
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        if ($appointment->client_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($appointment->status, AppointmentStatus::occupying())) {
            return back()->withErrors(['status' => 'Este agendamento nao pode ser cancelado.']);
        }

        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
            'cancelled_at' => now(),
        ]);

        event(new AppointmentCancelled($appointment));

        return back()->with('success', 'Agendamento cancelado.');
    }
}
