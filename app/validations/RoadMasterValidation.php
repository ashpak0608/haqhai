<?php

namespace App\validations;

class RoadMasterValidation {
    public function validate($data) {
        $errors = [];

        // Validate road name
        if (empty($data['road_name'])) {
            $errors['road_name'] = 'Road name is required.';
        } elseif (strlen($data['road_name']) > 500) {
            $errors['road_name'] = 'Road name must not exceed 500 characters.';
        }

        // Validate city
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'City is required.';
        }

        // Validate ward
        if (empty($data['ward_id'])) {
            $errors['ward_id'] = 'Ward is required.';
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        return null;
    }
}