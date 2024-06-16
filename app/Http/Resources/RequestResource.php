<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'passenger_fio_short' => $this->passenger->fio_short,
            'station_departure' => $this->stationDeparture->title,
            'station_arrival' => $this->stationArrival->title,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'category' => $this->category->short_title,
            'status' => new StatusResource($this->status)
        ];
    }
}
