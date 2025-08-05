<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyDetailValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'property_id' => 'required',
            'bedrooms' => 'required',
            'property_area' => 'required',
            'total_floors' => 'required',
            'property_floor_no' => 'required',
            'lifts' => 'required',
            'property_price' => 'required',
            'registration_cost' => 'required',
            'booking_amount' => 'required',
            'existing_bank_loan' => 'required',
            'loan_possible' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}