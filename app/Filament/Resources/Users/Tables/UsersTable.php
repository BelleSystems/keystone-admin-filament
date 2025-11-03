<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.name')
                    ->label('Property')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                TextColumn::make('department.name')
                    ->badge()
                    ->color('info')
                    ->label('Department')
                    ->sortable(),
                    TextColumn::make('last_name')
                        ->searchable(),
                TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('initials')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('username')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('is_retired')
                    ->label('Retired')
                    // if 1 show Yes else No
                    ->formatStateUsing(fn (string $state): string => $state ? 'Yes' : 'No')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        '0' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultGroup('property.name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
