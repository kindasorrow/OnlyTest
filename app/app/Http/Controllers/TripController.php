<?php
namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\Response;

class TripController extends Controller
{

    /**
     * @param  \Illuminate\Http\Request  $r
     *
     * @return mixed
     */
    public function index(Request $r)
    {
        return $r->user()->trips()->with('car.model')->get();
    }

    /**
     * @param  \Illuminate\Http\Request  $r
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $r): JsonResponse
    {
        $data = $r->validate([
            'car_id'    => 'required|exists:cars,id',
            'starts_at' => 'required|date_format:Y-m-d\TH:i',
            'ends_at'   => 'required|date_format:Y-m-d\TH:i|after:starts_at',
        ]);

        // проверка доступности выбранной машины
        $car  = Car::findOrFail($data['car_id']);
        $from = Carbon::parse($data['starts_at']);
        $to   = Carbon::parse($data['ends_at']);

        if (! $car->availableBetween($from, $to)->exists()) {
            return response()->json(['message'=>'Car unavailable'], 422);
        }

        $trip = $r->user()->trips()->create($data + ['status' => 'planned']);

        return response()->json($trip, 201);
    }

    /**
     * @param  \Illuminate\Http\Request  $r
     * @param  \App\Models\Trip  $trip
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $r, Trip $trip): JsonResponse
    {
        $this->authorize('update', $trip);

        $data = $r->validate([
            'status' => 'in:planned,approved,cancelled'
        ]);

        $trip->update($data);
        return response()->json($trip);
    }

    /**
     * @param  \App\Models\Trip  $trip
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip): Response
    {
        $this->authorize('delete', $trip);
        $trip->delete();
        return response()->noContent();
    }
}
