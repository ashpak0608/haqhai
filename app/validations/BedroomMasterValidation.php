<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class BedroomMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'bedroom_nos' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}