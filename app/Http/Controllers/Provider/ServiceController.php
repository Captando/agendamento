<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\StoreServiceRequest;
use App\Http\Requests\Provider\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = auth()->user()->services()->withTrashed()->orderBy('sort_order')->get();

        return view('provider.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('provider.services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        auth()->user()->services()->create([
            'name' => $request->name,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price_cents' => (int) round($request->price * 100),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('provider.services.index')
            ->with('success', 'Servico criado com sucesso!');
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        return view('provider.services.edit', compact('service'));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price_cents' => (int) round($request->price * 100),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('provider.services.index')
            ->with('success', 'Servico atualizado com sucesso!');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        $service->delete();

        return redirect()->route('provider.services.index')
            ->with('success', 'Servico removido com sucesso!');
    }
}
