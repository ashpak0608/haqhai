<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class LandmarkMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'landmark_name' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}