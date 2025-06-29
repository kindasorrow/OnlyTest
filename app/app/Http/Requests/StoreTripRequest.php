<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class StoreTripRequest extends FormRequest
{

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'car_id'    => ['required', 'integer', $this->carRule()],
            'starts_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'ends_at'   => ['required', 'date_format:Y-m-d\TH:i', 'after:starts_at'],
        ];
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    private function carRule(): Exists
    {
        $emp        = $this->user();
        $categories = $emp->position
            ->categories()
            ->pluck('id')       // массив разрешённых категорий
            ->all();

        return Rule::exists('cars', 'id')
            ->where(function ($q) use ($categories) {
                $q->whereIn('car_model_id', function ($sub) use ($categories) {
                    $sub
                        ->select('id')
                        ->from('car_models')
                        ->whereIn('comfort_category_id', $categories);
                });
            });
    }

}
