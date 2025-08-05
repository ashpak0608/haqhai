<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyAgeValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'age' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}