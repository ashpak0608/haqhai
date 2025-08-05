<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyCategoryMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}