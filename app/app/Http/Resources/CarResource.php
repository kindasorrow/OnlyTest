<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{

    /**
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'reg_number' => $this->reg_number,
            'model' => [
                'id'      => $this->model->id,
                'name'    => $this->model->name,
                'category'=> $this->model->category->title,
            ],
            'driver' => [
                'name'  => $this->driver->name,
                'phone' => $this->driver->phone,
            ],
        ];
    }
}
