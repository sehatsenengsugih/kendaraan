<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class ManualController extends Controller
{
    /**
     * Show the user manual page.
     */
    public function index(): View
    {
        return view('manual.index');
    }

    /**
     * Show specific section of the manual.
     */
    public function section(string $section): View
    {
        $validSections = [
            'dashboard',
            'kendaraan',
            'pajak',
            'servis',
            'master-data',
            'pengguna',
            'profil',
            'kalender',
        ];

        if (!in_array($section, $validSections)) {
            abort(404);
        }

        return view('manual.index', ['activeSection' => $section]);
    }
}
