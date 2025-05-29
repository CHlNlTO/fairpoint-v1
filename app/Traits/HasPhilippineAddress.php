<?php
// app/Traits/HasPhilippineAddress.php

namespace App\Traits;

use Yajra\Address\Entities\Barangay;
use Yajra\Address\Entities\City;
use Yajra\Address\Entities\Province;
use Yajra\Address\Entities\Region;

trait HasPhilippineAddress
{
    public function getRegionAttribute()
    {
        if ($this->barangay_id) {
            $barangay = Barangay::find($this->barangay_id);
            return Region::where('region_id', $barangay->region_id)->first();
        }

        if ($this->city_id) {
            $city = City::find($this->city_id);
            return Region::where('region_id', $city->region_id)->first();
        }

        if ($this->province_id) {
            $province = Province::find($this->province_id);
            return Region::where('region_id', $province->region_id)->first();
        }

        return null;
    }

    public function getFormattedAddressAttribute()
    {
        $parts = [];

        if ($this->address_sub_street) {
            $parts[] = $this->address_sub_street;
        }

        if ($this->address_street) {
            $parts[] = $this->address_street;
        }

        if ($this->barangay_id) {
            $barangay = Barangay::find($this->barangay_id);
            if ($barangay) {
                $parts[] = "Brgy. {$barangay->name}";
            }
        }

        if ($this->city_id) {
            $city = City::find($this->city_id);
            if ($city) {
                $parts[] = $city->name;
            }
        }

        if ($this->province_id) {
            $province = Province::find($this->province_id);
            if ($province) {
                $parts[] = $province->name;
            }
        }

        if ($this->zip_code) {
            $parts[] = $this->zip_code;
        }

        return implode(', ', $parts);
    }

    public function scopeInRegion($query, $regionId)
    {
        return $query->whereHas('barangay', function ($q) use ($regionId) {
            $q->where('region_id', $regionId);
        });
    }

    public function scopeInProvince($query, $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeInBarangay($query, $barangayId)
    {
        return $query->where('barangay_id', $barangayId);
    }
}
