<?php

namespace Database\Seeders\Vendor\Yajra;

use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;
use Yajra\Address\Entities\Barangay;
use Yajra\Address\Entities\City;
use Yajra\Address\Entities\Province;
use Yajra\Address\Entities\Region;

class AddressSeeder extends Seeder
{
    /**
     * @throws \OpenSpout\Common\Exception\IOException
     * @throws \OpenSpout\Common\Exception\UnsupportedTypeException
     * @throws \OpenSpout\Reader\Exception\ReaderNotOpenedException
     */
    public function run(): void
    {
        $publication = config('address.publication.path', __DIR__ . '/publication/PSGC-3Q-2024-Publication-Datafile.xlsx');
        $sheet = config('address.publication.sheet', 4);

        $regions = [];
        $provinces = [];
        $cities = [];
        $barangays = [];

        $this->command->info(sprintf('Parsing PSA official PSGC publication (%s).', $publication));

        (new FastExcel)
            ->sheet($sheet)
            ->import($publication, function ($line) use (&$regions, &$provinces, &$cities, &$barangays) {
                $attributes = [];
                $attributes['code'] = $line['10-digit PSGC'];
                $attributes['correspondence_code'] = $line['Correspondence Code'];
                $attributes['name'] = trim($line['Name']);
                $attributes['region_id'] = substr($attributes['code'], 0, 2);
                $geographicLevel = $line['Geographic Level'];
                $cityClass = $line['City Class'];

                if ($this->hasOwnProvince($geographicLevel, $attributes['region_id'], $cityClass)) {
                    $attributes['province_id'] = substr($attributes['code'], 0, 5);
                    $name = trim($line['Name']);

                    $provinces[] = [...$attributes, 'name' => $name];
                }

                switch ($geographicLevel) {
                    case 'Reg':
                        $regions[] = $attributes;
                        break;

                    case 'Dist':
                    case 'Prov':
                    case '':
                        $attributes['province_id'] = substr($attributes['code'], 0, 5);

                        $provinces[] = $attributes;
                        break;

                    case 'Bgy':
                        $attributes['province_id'] = substr($attributes['code'], 0, 5);
                        $attributes['city_id'] = substr($attributes['code'], 0, 7);

                        $barangays[] = $attributes;
                        break;

                    default: // City, SubMun, Mun
                        // Do not insert City of Manila
                        if ($attributes['code'] === '1380600000') {
                            break;
                        }

                        $attributes['province_id'] = substr($attributes['code'], 0, 5);
                        $attributes['city_id'] = substr($attributes['code'], 0, 7);

                        $name = str_replace('City of ', '', $attributes['name']);

                        $cities[] = [...$attributes, 'name' => $name];
                        break;
                }
            });

        $this->command->info(sprintf('Seeding %s regions.', count($regions)));
        $region = config('address.models.region', Region::class);
        $region::query()->insert($regions);

        $this->command->info(sprintf('Seeding %s provinces.', count($provinces)));
        $province = config('address.models.province', Province::class);
        collect($provinces)->chunk(100)->each(function ($chunk) use ($province) {
            $province::query()->insert($chunk->toArray());
        });

        $city = config('address.models.city', City::class);
        $this->command->info(sprintf('Seeding %s cities & municipalities.', count($cities)));
        collect($cities)->chunk(100)->each(function ($chunk) use ($city) {
            $city::query()->insert($chunk->toArray());
        });

        $this->command->info(sprintf('Seeding %s barangays.', count($barangays)));
        $barangay = config('address.models.barangay', Barangay::class);
        collect($barangays)->chunk(100)->each(function ($chunk) use ($barangay) {
            $barangay::query()->insert($chunk->toArray());
        });
    }

    protected function isMunicipalityInNCR(string $geographicLevel, string $regionId): bool
    {
        return in_array($geographicLevel, ['City', 'Mun']) && $regionId === '13';
    }

    protected function isHUC(string $geographicLevel, string $cityClass): bool
    {
        return $geographicLevel === 'City' && $cityClass === 'HUC';
    }

    protected function hasOwnProvince(string $geographicLevel, string $regionId, string $cityClass): bool
    {
        return $this->isMunicipalityInNCR($geographicLevel, $regionId)
            || $this->isHUC($geographicLevel, $cityClass);
    }
}
