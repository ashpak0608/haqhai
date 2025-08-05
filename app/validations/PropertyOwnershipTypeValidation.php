<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyOwnershipTypeValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'ownership_type' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}