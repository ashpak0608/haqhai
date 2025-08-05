<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyFacingValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'facing' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}