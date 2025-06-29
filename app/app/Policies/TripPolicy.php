<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Trip;
use Illuminate\Auth\Access\Response;

class TripPolicy
{

    /**
     * Determine whether the user can view the model.
     */
    public function view(Employee $employee, Trip $trip): bool
    {
        return $employee->id === $trip->employee_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Employee $employee): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Employee $employee, Trip $trip): bool
    {
        return $employee->id === $trip->employee_id && $trip->status === 'planned';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Employee $employee, Trip $trip): bool
    {
        return $this->update($employee, $trip);
    }

}
