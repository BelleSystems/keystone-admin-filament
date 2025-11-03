<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\Property;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Select::make('monthly_target_type')
                    ->label('Monthly Target Type')
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage',
                    ])->required(),
                Toggle::make('is_staffActual')
                    ->label('Is Staff Actual')
                    ->required(),
                TextInput::make('updated_by')
                    ->label('Updated By')
                    ->required(),
                Select::make('property_id')
                    ->label('Property')
                    ->options(Property::all()->pluck('name', 'id'))
                    ->required(),
            ]);
    }
}
