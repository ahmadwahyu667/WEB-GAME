<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:newsletters,email'],
        ]);

        Newsletter::create($validated);

        return back()->with('success', 'Berhasil berlangganan newsletter kami!');
    }
}
