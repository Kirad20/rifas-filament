@extends('layouts.app')

@section('title', 'Rifas Disponibles')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold mb-8 text-center">Rifas Disponibles</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($rifasActivas as $rifa)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if ($rifa->imagen)
                        <img src="{{ asset('storage/' . $rifa->imagen) }}" alt="{{ $rifa->nombre }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sin imagen</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $rifa->nombre }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($rifa->descripcion), 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-amber-500 font-bold">${{ number_format($rifa->precio, 2) }}</span>
                            <a href="{{ route('rifas.show', $rifa) }}"
                                class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition">Ver
                                más</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <p class="text-gray-500 text-xl">No hay rifas disponibles en este momento.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $rifasActivas->links() }}
        </div>
    </div>
@endsection
