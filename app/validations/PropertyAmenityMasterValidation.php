<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyAmenityMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'amenity_name' => 'required',
            'property_amenity_type' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}