<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Carbon\Carbon;


class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        if($this->record->is_GoogleCalendarEvent && $this->record->google_calendar_event_id != null) {
            $event = GoogleCalendarEvent::find($this->record->google_calendar_event_id);

            $event->name = $this->record->name;
            $event->description = $this->record->description;

            if($this->record->is_allDayEvent) {
                $event->startDateTime = Carbon::parse($this->record->start_DateTime)->startOfDay();
                $event->endDateTime = Carbon::parse($this->record->end_DateTime)->endOfDay();
            } else {
                $event->startDateTime = Carbon::parse($this->record->start_DateTime);
                $event->endDateTime = Carbon::parse($this->record->end_DateTime);
            }

            $event->save();
        }

        $this->record->last_updated_by = auth()->user()->id;
        $this->record->save();
    }
}
