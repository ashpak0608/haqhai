<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyAreaUnitMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'unit' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}