<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Obtener rifas destacadas (activas y ordenadas por popularidad)
        $rifasDestacadas = Rifa::where('estado', 'activa')

            ->take(3)
            ->get();

        return view('landing', compact('rifasDestacadas'));
    }
}
