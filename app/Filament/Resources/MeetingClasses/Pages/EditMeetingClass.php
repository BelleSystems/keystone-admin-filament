<?php

namespace App\Filament\Resources\MeetingClasses\Pages;

use App\Filament\Resources\MeetingClasses\MeetingClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMeetingClass extends EditRecord
{
    protected static string $resource = MeetingClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
