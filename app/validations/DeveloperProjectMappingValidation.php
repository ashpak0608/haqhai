<?php
namespace App\Validations;
use Illuminate\Support\Facades\Validator;

class DeveloperProjectMappingValidation
{
    public function validate(array $data)
    {
        $validator = Validator::make($data, [
            'developer_id' => 'required',
            'project_name' => 'required',
            'location_id' => 'required',
            //'pincode' => 'required',
        ]);

        if ($validator->fails()) {
            return ['status' => 'error', 'message' => 'Validation Error', 'errors' => $validator->errors()];
        }

        return null; // Validation passed
    }
}