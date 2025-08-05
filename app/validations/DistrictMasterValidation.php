<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class DistrictMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'district_name' => 'required',
            'state_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}