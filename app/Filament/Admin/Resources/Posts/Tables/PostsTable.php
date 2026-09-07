<?php

namespace App\Filament\Admin\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('resumen')
                    ->limit(40),
                TextColumn::make('categoria.nombre')
                    ->label('Categoría'),
                IconColumn::make('publicado')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre')
                    ->preload(),
                TernaryFilter::make('publicado')
                    ->label('¿Publicados?')
                    ->trueLabel('Solo publicados')
                    ->falseLabel('Solo borradores'),
            ])
            ->recordActions([
                Action::make('publicar')
                    ->label('Publicar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Post $record) => ! $record->publicado)
                    ->action(fn (Post $record) => $record->update(['publicado' => true])),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
