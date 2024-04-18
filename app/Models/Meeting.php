<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'datetime',
        'attendee_email',
        'creator_id',
        'event_id'
    ];

    protected $dates = ['datetime'];

    public function setDatetimeAttribute($value) {
        $this->attributes['datetime'] = new \DateTime($value);
    }

    public function getEndDateTime() {
        if (!$this->datetime instanceof \DateTime) {
            $this->datetime = new \DateTime($this->datetime);
        }
        $endDateTime = clone $this->datetime;
        $endDateTime->modify('+1 hour');
        return $endDateTime;
    }
}
