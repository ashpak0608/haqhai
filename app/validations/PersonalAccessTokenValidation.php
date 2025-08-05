<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PersonalAccessTokenValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'tokenable_type' => 'required',
            'tokenable_id' => 'required',
            'name' => 'required',
            'token' => 'required',
            'abilities' => 'required',
            'last_used_at' => 'required',
            'expires_at' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}