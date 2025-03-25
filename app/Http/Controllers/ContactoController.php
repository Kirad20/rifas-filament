<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactoController extends Controller
{
    public function index()
    {
        return view('contacto');
    }

    public function enviar(Request $request)
    {
        // Validar el formulario
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'mensaje' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        // Aquí iría el código para enviar el email
        // Por ejemplo, usando el sistema de correo de Laravel

        // Retornar con mensaje de éxito
        return back()->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}
