<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class UserDetailValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'full_name' => 'required',
            'email_id' => 'required',
            'phone_1' => 'required',
            //'level_id' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}