<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyAreaTypeValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'area_type' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}