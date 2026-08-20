<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Aviso', 'Capacitación', 'Operativo'] as $nombre) {
            Categoria::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
