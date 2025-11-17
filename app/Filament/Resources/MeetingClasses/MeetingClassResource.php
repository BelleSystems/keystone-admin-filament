<?php

namespace App\Filament\Resources\MeetingClasses;

use App\Filament\Resources\MeetingClasses\Pages\CreateMeetingClass;
use App\Filament\Resources\MeetingClasses\Pages\EditMeetingClass;
use App\Filament\Resources\MeetingClasses\Pages\ListMeetingClasses;
use App\Filament\Resources\MeetingClasses\Schemas\MeetingClassForm;
use App\Filament\Resources\MeetingClasses\Tables\MeetingClassesTable;
use App\Models\MeetingClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MeetingClassResource extends Resource
{
    protected static ?string $model = MeetingClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MeetingClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MeetingClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMeetingClasses::route('/'),
            'create' => CreateMeetingClass::route('/create'),
            'edit' => EditMeetingClass::route('/{record}/edit'),
        ];
    }
}
