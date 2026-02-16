<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointmentService,
    ) {}

    public function store(Request $request, User $user, Service $service): RedirectResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        try {
            $this->appointmentService->book(
                provider: $user,
                client: $request->user(),
                service: $service,
                date: Carbon::parse($request->date),
                startTime: $request->start_time,
            );

            return redirect()->route('client.appointments.index')
                ->with('success', 'Agendamento realizado com sucesso!');
        } catch (\DomainException $e) {
            return back()->withErrors(['start_time' => $e->getMessage()]);
        }
    }
}
