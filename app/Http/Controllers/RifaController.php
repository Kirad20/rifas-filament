<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use App\Models\Boleto;
use Illuminate\Http\Request;

class RifaController extends Controller
{
    public function index()
    {
        // Obtener todas las rifas activas paginadas
        $rifas = Rifa::where('estado', 'activa')
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('rifas.index', compact('rifas'));
    }

    public function show($id)
    {
        // Obtener la rifa específica con sus relaciones
        $rifa = Rifa::findOrFail($id);

        // Obtener los boletos disponibles para esta rifa
        $boletosDisponibles = Boleto::where('rifa_id', $id)
            ->where('estado', 'disponible')
            ->count();

        return view('rifas.show', compact('rifa', 'boletosDisponibles'));
    }
}
