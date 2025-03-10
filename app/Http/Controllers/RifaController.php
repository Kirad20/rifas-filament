<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;
use RifaEstadoEnum;

class RifaController extends Controller
{
    public function index()
    {
        $rifas = Rifa::where('estado', RifaEstadoEnum::ACTIVA)
            ->orderBy('fecha_sorteo', 'asc')
            ->paginate(9);

        return view('rifas.index', compact('rifas'));
    }

    public function show(Rifa $rifa)
    {

        return view('rifas.show', compact('rifa'));
    }
}
