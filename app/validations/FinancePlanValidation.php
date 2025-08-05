<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class FinancePlanValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'plan_name' => 'required',
            'plan_description' => 'required',
            'plan_amount' => 'required',
            'validity_days' => 'required',
            'applicable_user_type' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}