<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyListingForValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'listing_for' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}