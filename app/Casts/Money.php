<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Money implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return float
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return (float) $value;
    }

    /**
     * Prepare the value for storage.
     *
     * @param  Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array<string, mixed>  $attributes
     * @return float
     */
    public function set($model, string $key, $value, array $attributes)
    {
        // Allow only numbers and a single decimal point
        $sanitizedValue = preg_replace('/[^\d.]/', '', $value);
        
        // Validate if there is more than one decimal point
        if (substr_count($sanitizedValue, '.') > 1) {
            // Handle error case, return null or throw an exception as needed
            return null; // or throw new InvalidArgumentException('Invalid decimal format.');
        }

        return floatval($sanitizedValue); // Convert to float
    }
}
