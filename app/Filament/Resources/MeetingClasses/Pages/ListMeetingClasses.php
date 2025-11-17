<?php

namespace App\Filament\Resources\MeetingClasses\Pages;

use App\Filament\Resources\MeetingClasses\MeetingClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMeetingClasses extends ListRecords
{
    protected static string $resource = MeetingClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
