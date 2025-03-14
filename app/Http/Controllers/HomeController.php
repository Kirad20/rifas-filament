<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;
use RifaEstadoEnum;
use TomatoPHP\FilamentSettingsHub\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {

        $rifasDestacadas = Rifa::where('estado', '=', 'activa')
            ->orderBy('fecha_sorteo', 'asc')
            ->take(3)
            ->get();



        return view('home', compact('rifasDestacadas'));
    }

    public function listarRifas()
    {
        $rifasActivas = Rifa::where('estado', '=', 'activa')
            ->orderBy('fecha_sorteo', 'asc')
            ->paginate(9);

        return view('rifas.index', compact('rifasActivas'));
    }

    public function mostrarRifa(Rifa $rifa)
    {
        return view('rifas.show', compact('rifa'));
    }
}
