<?php

use Illuminate\Validation\Rules\Enum;

class RifaEstadoEnum extends Enum
{
    const ACTIVA = 'activa';
    const CANCELADA = 'cancelada';
    const FINALIZADA = 'finalizada';
}
