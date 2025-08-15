<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PinLocationMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'location_name' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}