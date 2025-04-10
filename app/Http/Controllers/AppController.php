<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Pub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppController extends Controller {
    public function events(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $events = Event::paginate($perPage, ['*'], 'page', $page);
        $eventItems = $events->items();

        // Iterate through each event item and its attributes to check for INF or NaN
        foreach ($eventItems as $event) {
            foreach ($event->getAttributes() as $key => $value) {
                if (is_numeric($value)) {
                    if (is_infinite((float) $value) || is_nan((float) $value)) {
                        Log::error("INF or NaN found in Event ID: {$event->id}, Attribute: {$key}, Value: {$value}");
                        $event->$key = null;
                    }
                }
            }
        }

        return response()->json([
            'content' => $eventItems,
            'pagination' => [
                'total' => $events->total(),
                'per_page' => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'next_page_url' => $events->nextPageUrl(),
                'prev_page_url' => $events->previousPageUrl(),
            ],
        ]);
    }

    public function pubs(Request $request) {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);

        $pubs = Pub::paginate($perPage, ['*'], 'page', $page);
        $pubItems = $pubs->items();

        // Iterate through each event item and its attributes to check for INF or NaN
        foreach ($pubItems as $pub) {
            foreach ($pub->getAttributes() as $key => $value) {
                if (is_numeric($value)) {
                    if (is_infinite((float) $value) || is_nan((float) $value)) {
                        Log::error("INF or NaN found in Event ID: {$pub->id}, Attribute: {$key}, Value: {$value}");
                        $pub->$key = null;
                    }
                }
            }
        }

        return response()->json([
            'content' => $pubItems,
            'pagination' => [
                'total' => $pubs->total(),
                'per_page' => $pubs->perPage(),
                'current_page' => $pubs->currentPage(),
                'last_page' => $pubs->lastPage(),
                'next_page_url' => $pubs->nextPageUrl(),
                'prev_page_url' => $pubs->previousPageUrl(),
            ],
        ]);
    }

    public function all_events(Request $request) {

        $events = Event::all();
        foreach ($events as $event) {
            foreach ($event->getAttributes() as $key => $value) {
                if (is_numeric($value)) { // Check if the value is numeric
                    if (is_infinite((float) $value) || is_nan((float) $value)) {
                        $event->$key = null;
                    }
                }
            }
        }
    
    


        return response()->json($events);
    }

    public function all_pubs(Request $request) {
        $pubs = Event::all();
        foreach ($pubs as $event) {
            foreach ($event->getAttributes() as $key => $value) {
                if (is_numeric($value)) { // Check if the value is numeric
                    if (is_infinite((float) $value) || is_nan((float) $value)) {
                        $event->$key = null;
                    }
                }
            }
        }



        return response()->json($pubs);}
}
