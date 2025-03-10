<?php

namespace App\Http\Controllers;

use App\Models\Rifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RifaController extends Controller
{
    public function index()
    {
        $rifas = Rifa::where('estado', '=', 'activa')
            ->orderBy('fecha_sorteo', 'asc')
            ->paginate(12);

        return view('rifas.index', compact('rifas'));
    }

    public function filter(Request $request)
    {
        $query = Rifa::where('activa', true);

        // Filtrar por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Filtrar por precio máximo
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        // Filtrar solo próximas a sortearse (en los próximos 7 días)
        if ($request->has('proximas')) {
            $query->whereDate('fecha_sorteo', '<=', now()->addDays(7));
        }

        // Ordenar resultados
        switch ($request->ordenar) {
            case 'fecha_cercana':
                $query->orderBy('fecha_sorteo', 'asc');
                break;
            case 'fecha_lejana':
                $query->orderBy('fecha_sorteo', 'desc');
                break;
            case 'precio_bajo':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_alto':
                $query->orderBy('precio', 'desc');
                break;
            case 'popularidad':
                // Asumiendo que hay un campo para medir popularidad o usar la cantidad de boletos vendidos
                $query->orderByRaw('boletos_vendidos / boletos_totales DESC');
                break;
            default:
                $query->orderBy('fecha_sorteo', 'asc');
        }

        $rifas = $query->paginate(12)->appends($request->all());

        return view('rifas.index', compact('rifas'));
    }

    public function show(Rifa $rifa)
    {
        // Asegurarse que la rifa esté activa
        if (!$rifa->activa) {
            abort(404);
        }

        return view('rifas.show', compact('rifa'));
    }
}
