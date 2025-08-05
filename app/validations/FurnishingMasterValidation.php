<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class FurnishingMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'furnishing_type' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}