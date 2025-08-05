<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class StateMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'state_name' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}