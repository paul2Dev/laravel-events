<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Carbon\Carbon;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if($this->record->is_GoogleCalendarEvent) {
            $event = new GoogleCalendarEvent;

            $event->name = $this->record->name;
            $event->description = $this->record->description;

            if($this->record->is_allDayEvent) {
                $event->startDateTime = Carbon::parse($this->record->start_DateTime)->startOfDay();
                $event->endDateTime = Carbon::parse($this->record->end_DateTime)->endOfDay();
            } else {
                $event->startDateTime = Carbon::parse($this->record->start_DateTime);
                $event->endDateTime = Carbon::parse($this->record->end_DateTime);
            }

            $newEvent = $event->save();

            if($newEvent->id) {
                $this->record->google_calendar_event_id = $newEvent->id;
            }
        }

        $this->record->created_by = auth()->user()->id;
        $this->record->save();
    }
}
