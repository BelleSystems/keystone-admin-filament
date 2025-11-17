<?php

namespace App\Filament\Resources\MeetingClasses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Requirement;
class MeetingClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('abbreviation')
                    ->required(),
                TextInput::make('property_id')
                    ->numeric(),
                Select::make('requirements')
                    ->relationship('requirementsBelongsToMany', 'name')
                    ->label('Requirements')
                    ->multiple()
                    ->preload()
            ]);
    }
}
