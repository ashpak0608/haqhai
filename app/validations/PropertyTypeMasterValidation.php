<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyTypeMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'type_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}