<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'partner_or_brand' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'session_types' => ['required', 'string', 'max:100'],
            'shoot_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'vision' => ['required', 'string'],
        ]);

        Inquiry::query()->create($validated);

        return back()->with('status', 'Inquiry received.');
    }
}
