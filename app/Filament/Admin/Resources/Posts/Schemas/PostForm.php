<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('titulo')
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(fn (Set $set, ?string $state) =>
                                                        $set('slug', str($state)->slug()->toString())
                                                )
                        ->required()
                        ->maxLength(255),
                                TextInput::make('slug'),
                TextInput::make('resumen')
                        ->maxLength(160)
                        ->columnSpanFull(),
                Select::make('categoria_id')
                        ->label('Categoría')
                        ->relationship('categoria', 'nombre')
                        ->required(),
                Textarea::make('contenido')
                        ->required()
                        ->columnSpanFull(),
                Toggle::make('publicado')
                        ->live()
                        ->default(true),
                DateTimePicker::make('publicado_en')
                        ->visible(fn (Get $get): bool => (bool) $get('publicado')),
            ]);
    }
}
