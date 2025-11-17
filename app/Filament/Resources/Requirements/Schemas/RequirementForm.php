<?php

namespace App\Filament\Resources\Requirements\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use App\Models\RequirementField;
use Filament\Schemas\Components\Section;
use App\Enums\RequirementFieldType;
use Filament\Forms\Components\Toggle;
class RequirementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                Repeater::make('fields')
                    ->label('Fields')
                    ->relationship('fields')
                    ->schema([
                        TextInput::make('label')->required(),
                        Select::make('field_type')
                            ->label('Field Type')
                            ->options(
                                array_combine(
                                    RequirementFieldType::listValues(),
                                    RequirementFieldType::listValues()
                                )
                            )
                            ->required(),
                        Toggle::make('is_required')
                            ->label('Is Required')
                            ->required()
                ])
                    ->columns(2)
                    ->grow(),
                ]);
    }
}
