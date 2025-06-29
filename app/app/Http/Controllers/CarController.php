<?php
// app/Http/Controllers/CarController.php

namespace App\Http\Controllers;

use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CarController extends Controller
{
    /**
     *
     * GET /api/available-cars
     *
     * @param  \Illuminate\Http\Request  $r
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $r): \Illuminate\Http\JsonResponse
    {
        $r->validate([
            'starts_at'            => 'required|date_format:Y-m-d\TH:i',
            'ends_at'              => 'required|date_format:Y-m-d\TH:i|after:starts_at',
            'model_id'             => 'sometimes|integer|exists:car_models,id',
            'comfort_category_id'  => 'sometimes|integer|exists:comfort_categories,id',
        ]);

        $emp   = $r->user();
        $from  = Carbon::parse($r->starts_at);
        $to    = Carbon::parse($r->ends_at);

        $allowedCategories = $emp->position
            ->categories()
            ->pluck('id')
            ->all();

        $cars = Car::query()
            ->whereHas('model', fn($q) =>
            $q->whereIn('comfort_category_id', $allowedCategories)
                ->when($r->comfort_category_id, fn($q) => $q->where(
                    'comfort_category_id', $r->comfort_category_id)))
            ->when($r->model_id, fn($q) => $q->where('car_model_id', $r->model_id))
            ->availableBetween($from, $to)
            ->with(['model.category','driver'])
            ->orderBy('reg_number')
            ->get();

        return response()->json(CarResource::collection($cars));
    }
}
