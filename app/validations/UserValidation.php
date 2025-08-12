<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class UserValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'full_name' => 'required',
            'email_id' => 'required|email',
            'phone1' => 'required',
            'password' => 'required',
            'level_id' => 'required',
            'gender' => 'required',
            'marital_status' => 'required',
            'dob' => 'required',
            'doa' => 'required',
            'last_passChanged_dt' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}