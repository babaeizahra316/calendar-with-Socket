<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use HasFactory, BroadcastsEvents, SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::Class);
    }

    public function broadcastOn($event) {
        return [new Channel('model')];
    }
}
