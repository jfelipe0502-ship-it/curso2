<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;


class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['titulo', 'contenido', 'categoria_id', 'publicado', 'user_id'];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    protected function resumen(): Attribute
    {
        return Attribute::get(fn () => Str::limit($this->contenido, 90));
    }

    protected function esNuevo(): Attribute
    {
        return Attribute::get(fn () =>
            $this->publicado
            && $this->created_at->gt(now()->subDays(7))
        );
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublicados(Builder $query)
    {
        return $query->where('publicado', true);
    }

    public function scopeDeCategoria(Builder $query, int $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class);
    }
}
