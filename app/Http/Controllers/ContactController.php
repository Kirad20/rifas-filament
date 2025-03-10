<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|max:255',
            'mensaje' => 'required|max:1000',
        ]);

        // Aquí puedes implementar el envío del correo o guardar en base de datos
        // Por ejemplo con Mail::to('contacto@rifas.com')->send(new ContactoMail($validatedData));

        return redirect()->route('contacto')->with('success', 'Tu mensaje ha sido enviado correctamente. Pronto nos pondremos en contacto contigo.');
    }
}
