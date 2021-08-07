<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'title' => $this->event_name,
            'start' => $this->start_date,
            'end' => $this->end_date,
            'status' => $this->status,
            'backgroundColor' => $this->background_color
        ];
    }
}
