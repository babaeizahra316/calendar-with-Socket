<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
use Symfony\Component\HttpFoundation\Response;

class AdminCalendarController extends Controller
{
    public function acceptReject() {
        return view('adminCalendar');
    }

    public function index() {
        return CalendarResource::collection(Calendar::all());
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        //
    }

    public function show(Calendar $calendar) {
        return response($calendar, Response::HTTP_OK);
    }

    public function edit(Calendar $calendar) {
        //
    }

    public function update(Request $request, Calendar $calendar) {
        $calendar->status = 1;
        $calendar->background_color = '#2fa360';
        $calendar->save();

        return response()->json([
            'data' => new CalendarResource($calendar),
            'message' => 'Successfully updated event!',
            'status' => Response::HTTP_ACCEPTED
        ]);
    }

    public function destroy(Calendar $calendar) {
        $calendar->status = 2;
        $calendar->background_color = '#d0211c';
        $calendar->save();

        return response()->json([
            'data' => new CalendarResource($calendar),
            'message' => 'Event removed successfully!',
            'status' => Response::HTTP_ACCEPTED
        ]);
    }
}
