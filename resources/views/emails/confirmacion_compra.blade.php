<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #f59e0b;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        h1,
        h2 {
            color: #333;
        }

        .content {
            padding: 20px;
        }

        .footer {
            font-size: 12px;
            color: #777;
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .boleto {
            display: inline-block;
            background-color: #fff8e1;
            border: 1px solid #ffd54f;
            border-radius: 12px;
            padding: 5px 10px;
            font-weight: bold;
            color: #f59e0b;
        }

        .total {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>¡Gracias por tu compra!</h1>
        </div>

        <div class="content">
            <p>Hola {{ $venta->cliente->nombre }},</p>

            <p>Te confirmamos que tu compra ha sido procesada correctamente. A continuación encontrarás los detalles de
                tu compra:</p>

            <h2>Detalles de la compra</h2>
            <p><strong>Referencia de pago:</strong> {{ $venta->referencia_pago }}</p>
            <p><strong>Fecha:</strong> {{ $venta->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Método de pago:</strong> {{ ucfirst($venta->metodo_pago) }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($venta->estado) }}</p>

            <h2>Boletos comprados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Rifa</th>
                        <th>Número</th>
                        <th>Fecha sorteo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venta->boletos as $boleto)
                        <tr>
                            <td>{{ $boleto->rifa->nombre }}</td>
                            <td><span class="boleto">{{ $boleto->numero }}</span></td>
                            <td>{{ $boleto->rifa->fecha_sorteo->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total">Total pagado: ${{ number_format($venta->total, 2) }}</p>

            <p>Te recordamos que el sorteo se realizará en la fecha indicada y los resultados serán publicados en
                nuestra página web. Si tienes alguna pregunta, no dudes en contactarnos.</p>

            <p>¡Mucha suerte!</p>
        </div>

        <div class="footer">
            <p>Este correo ha sido enviado porque realizaste una compra en nuestro sistema de rifas.<br>
                © {{ date('Y') }} Sistema de Rifas. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
