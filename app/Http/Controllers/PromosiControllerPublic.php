<?php

namespace App\Http\Controllers;

use App\Models\Promosi;
use Illuminate\Http\Request;

class PromosiControllerPublic extends Controller
{
    /**
     * Halaman detail satu promosi (public, untuk user).
     * URL: /promosi/{id}
     * Name: promosi.show
     */
    public function show(Promosi $promosi)
    {
        // Jika promosi tidak aktif, redirect ke home
        if (!$promosi->is_active) {
            return redirect()->route('home')->with('error', 'Promosi tidak tersedia.');
        }

        return view('promosi.show', compact('promosi'));
    }
}