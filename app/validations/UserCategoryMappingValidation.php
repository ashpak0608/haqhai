<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class UserCategoryMappingValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'user_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}