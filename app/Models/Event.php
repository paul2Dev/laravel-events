<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_DateTime',
        'end_DateTime',
        'created_by',
        'updated_by',
        'is_GoogleCalendarEvent',
        'google_calendar_event_id',
        'is_allDayEvent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
