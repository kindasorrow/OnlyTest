<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
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
    public function index(Request $r): mixed
    {
        return $r->user()->trips()->with('car.model')->get();
    }

    /**
     * @param  \App\Http\Requests\StoreTripRequest  $r
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTripRequest $r): JsonResponse
    {
        $emp  = $r->user();
        $from = Carbon::parse($r->starts_at);
        $to   = Carbon::parse($r->ends_at);

        $car = Car::whereKey($r->car_id)
            ->availableBetween($from, $to)
            ->first();

        if (!$car) {
            return response()->json(['message' => 'Car unavailable'], 422);
        }

        $trip = $emp->trips()->create([
            'car_id'    => $car->id,
            'starts_at' => $from,
            'ends_at'   => $to,
            'status'    => 'planned',
        ]);

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
