<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Select::make('permissions')
                    ->label('Permissions')
                    ->relationship('permissions', 'name')->preload()
                    ->multiple()
                    ->options(Permission::all()->pluck('name', 'id'))->preload()
            ]);
    }
}
