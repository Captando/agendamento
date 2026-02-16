<?php

namespace App\Http\Controllers\PublicPage;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderPageController extends Controller
{
    public function show(Request $request, User $user): View
    {
        if (! $user->isProvider()) {
            abort(404);
        }

        $services = $user->services()->where('is_active', true)->orderBy('sort_order')->get();

        $selectedService = null;
        if ($request->has('service')) {
            $selectedService = $services->firstWhere('id', $request->service);
        }

        return view('public.provider-page', compact('user', 'services', 'selectedService'));
    }
}
