<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public static function nombresPermitidosParaAvisos(): array
    {
        return ['Aviso', 'Capacitación', 'Operativo'];
    }

    public function scopePermitidasParaAvisos($query)
    {
        return $query->whereIn('nombre', self::nombresPermitidosParaAvisos());
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

