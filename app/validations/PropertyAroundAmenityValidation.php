<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyAroundAmenityValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'property_id' => 'required',
            'property_around_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}