<?php

namespace App\validations;

class LocationMasterValidation {
    public function validate($data) {
        $errors = [];

        // Validate location name
        if (empty($data['location_name'])) {
            $errors['location_name'] = 'Location name is required.';
        } elseif (strlen($data['location_name']) > 500) {
            $errors['location_name'] = 'Location name must not exceed 500 characters.';
        }

        // Validate city
        if (empty($data['city_id'])) {
            $errors['city_id'] = 'City is required.';
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        return null;
    }
}