<?php
// app/Traits/HandlesPhilippineAddressIds.php

namespace App\Traits;

trait HandlesPhilippineAddressIds
{
    /**
     * Append zeros to address IDs to match yajra/laravel-address format
     */
    protected function formatAddressId($id, $type = 'city')
    {
        if (!$id) {
            return null;
        }

        // If ID already has the correct length, return as is
        $idString = (string) $id;

        switch ($type) {
            case 'province':
                // Provinces typically have 4 digits + 00000 = 9 digits total
                return strlen($idString) < 9 ? $idString . '00000' : $idString;
            case 'city':
                // Cities have 6 digits + 000 = 9 digits total
                return strlen($idString) < 9 ? $idString . '000' : $idString;
            case 'barangay':
                // Barangays have 9 digits
                return $idString;
            default:
                return $idString;
        }
    }

    /**
     * Remove zeros from address IDs for display
     */
    protected function parseAddressId($id, $type = 'city')
    {
        if (!$id) {
            return null;
        }

        $idString = (string) $id;

        switch ($type) {
            case 'province':
                // Remove last 5 zeros
                return strlen($idString) > 5 ? substr($idString, 0, -5) : $idString;
            case 'city':
                // Remove last 3 zeros
                return strlen($idString) > 6 ? substr($idString, 0, -3) : $idString;
            case 'barangay':
                // Keep as is
                return $idString;
            default:
                return $idString;
        }
    }

    /**
     * Mutators for Business model
     */
    public function setProvinceIdAttribute($value)
    {
        $this->attributes['province_id'] = $this->formatAddressId($value, 'province');
    }

    public function setCityIdAttribute($value)
    {
        $this->attributes['city_id'] = $this->formatAddressId($value, 'city');
    }

    public function setBarangayIdAttribute($value)
    {
        $this->attributes['barangay_id'] = $this->formatAddressId($value, 'barangay');
    }

    public function getProvinceIdForFormAttribute()
    {
        return $this->parseAddressId($this->province_id, 'province');
    }

    public function getCityIdForFormAttribute()
    {
        return $this->parseAddressId($this->city_id, 'city');
    }

    public function getBarangayIdForFormAttribute()
    {
        return $this->parseAddressId($this->barangay_id, 'barangay');
    }
}
