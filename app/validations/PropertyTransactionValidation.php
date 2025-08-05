<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class PropertyTransactionValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'property_id' => 'required',
            'user_id' => 'required',
            'transaction_no' => 'required',
            'payment_mode' => 'required',
            'trx_amount' => 'required',
            'txn_dt' => 'required',
            'coupon_code' => 'required',
            'plan_id' => 'required',
            'payment_status' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}