<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PageAccessNameValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'page_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}