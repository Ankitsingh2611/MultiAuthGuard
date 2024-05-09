<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Country, State, City};

class CountriesStatesCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

     /*
     * php artisan db:seed --class=CountriesStatesCitiesTableSeeder
     */
    public function run(): void
    {
        $countries = [
            [
                'country_name' => 'India'
            ],
            [
                'country_name' => 'Sri Lanka'
            ]
        ];
        foreach($countries as $key => $country)
        {
            Country::create($country);
        }

        $states = [
            [
                'state_name' => 'Jharkhand',
                'country_id' => 1,
            ],
            [
                'state_name' => 'Colombo',
                'country_id' => 2,
            ]
        ];
        foreach($states as $key => $state)
        {
            State::create($state);
        }

        $cities = [
            [
                'city_name' => 'Ranchi',
                'state_id' => 1,
            ],
            [
                'city_name' => 'dehiwala',
                'state_id' => 2,
            ]
        ];
        foreach($cities as $key => $city)
        {
            City::create($city);
        }
    }
}
