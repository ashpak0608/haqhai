<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyTypePlanMappingValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'property_type_id' => 'required',
            'plan_id' => 'required',
            'no_of_properties' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}