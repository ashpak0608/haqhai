<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyListingValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'property_description' => 'required',
            'user_id' => 'required',
            'property_type_id' => 'required',
            'property_cat_id' => 'required',
            'property_address' => 'required',
            'developer_name' => 'required',
            'project_name' => 'required',
            'pincode' => 'required',
            'property_status' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}