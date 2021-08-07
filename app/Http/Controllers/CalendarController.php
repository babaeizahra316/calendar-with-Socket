<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Http\Resources\CalendarResource;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function schedule() {
        return view('userCalendar');
    }

    public function index() {
        $calendars = auth()->user()->calendars()->whereIn('status', [0, 1])->get();
        return CalendarResource::collection($calendars);
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        $new_calendar = auth()->user()->calendars()->create(['user_name' => auth()->user()->name,
            'event_name' => $request->event_name,
            'start_date' => $request->start_date,
            'end_date' => $request-> end_date,
            'status' => 0,
            'background_color' => '#3490dc']);
        return response()->json([
            'data' => new CalendarResource($new_calendar),
            'message' => 'Successfully added new event!',
            'status' => Response::HTTP_CREATED
        ]);
    }

    public function show(Calendar $calendar) {
        return response($calendar, Response::HTTP_OK);
    }

    public function edit(Calendar $calendar) {
        //
    }

    public function update(Request $request, Calendar $calendar) {
        $calendar->event_name = $request->event_name;
        $calendar->start_date = $request->start_date;
        $calendar->end_date = $request->end_date;
        $calendar->save();

        return response()->json([
            'data' => new CalendarResource($calendar),
            'message' => 'Successfully updated event!',
            'status' => Response::HTTP_ACCEPTED
        ]);
    }

    public function destroy(Calendar $calendar) {
        $calendar->delete();
        return response('Event removed successfully!', Response::HTTP_NO_CONTENT);
    }
}
