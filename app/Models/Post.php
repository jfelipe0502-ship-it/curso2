<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['titulo', 'slug', 'resumen', 'contenido', 'categoria_id', 'publicado', 'publicado_en', 'user_id'];

    protected $casts = [
        'publicado' => 'boolean',
        'publicado_en' => 'datetime',
    ];

    protected function resumen(): Attribute
    {
        return Attribute::get(fn (?string $value): string =>
            $value ?: Str::limit($this->contenido, 160)
        );
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
