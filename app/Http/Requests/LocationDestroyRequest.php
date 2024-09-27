<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // You can add authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
