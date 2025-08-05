<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class LoginHistoryValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'login_date_time' => 'required',
            'ip_address' => 'required',
            'device_details' => 'required',
            'browser_details' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}