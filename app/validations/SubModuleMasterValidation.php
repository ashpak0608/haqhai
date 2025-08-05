<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class SubModuleMasterValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'module_id' => 'required',
            'sub_module_short_name' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}