<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;
use App\Models\Property;
use App\Models\Department;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
                ->components([
                    Section::make([
                    TextInput::make('first_name'),
                    TextInput::make('middle_name'),
                    TextInput::make('last_name'),
                    TextInput::make('initials'),
                    TextInput::make('title'),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email(),
                    TextInput::make('username'),
                    ]),
                    Section::make('User Access')
                    ->description('Select the property, roles, and department for the user.')
                        ->schema([
                        Section::make([
                        Select::make('property_id')
                            ->label('Property')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->options(Property::all()->pluck('name', 'id'))
                            ->relationship('property', 'name')->preload(),
                        Select::make('department_id')
                            ->label('Department')
                            ->prefixIcon('heroicon-o-building-office')
                            ->options(Department::all()->pluck('name', 'id'))
                            ->relationship('department', 'name')->preload(),
                        Select::make('roles')
                            ->label('Roles')
                            ->prefixIcon('heroicon-o-tag')
                            ->multiple()
                            ->options(Role::all()->pluck('name', 'id'))
                            ->relationship('roles', 'name')->preload()->grow(),
                        ]),
                        Section::make('Retire')
                        ->description('Check if the user is retired. This will remove the user from logging in.')
                        ->schema([
                            Toggle::make('is_retired')
                            ->label('Retired'),
                        ])
                    ])
            ]);
    }
}
