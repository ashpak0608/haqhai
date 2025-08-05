<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class AroundPropertyAmenityMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'around_amenity_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}