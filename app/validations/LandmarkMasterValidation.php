<?php

namespace App\validations;

class LandmarkMasterValidation
{
    public function validate($data)
    {
        $rules = [
            'area_id' => 'required',
            'city_id' => 'required',
            'landmark_name' => 'required|string|max:255',
        ];

        $messages = [
            'area_id.required' => 'Area is required',
            'city_id.required' => 'City is required',
            'landmark_name.required' => 'Landmark name is required',
        ];

        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return ['status' => 'warning', 'message' => $validator->errors()->first()];
        }

        return null;
    }
}