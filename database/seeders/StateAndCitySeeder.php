<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateAndCitySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Uttar Pradesh',
                'name_en' => 'Uttar Pradesh',
                'slug' => 'uttar-pradesh',
                'code' => 'UP',
                'order' => 1,
                'cities' => [
                    ['name' => 'Lucknow', 'name_en' => 'Lucknow', 'slug' => 'lucknow'],
                    ['name' => 'Kanpur', 'name_en' => 'Kanpur', 'slug' => 'kanpur'],
                    ['name' => 'Varanasi', 'name_en' => 'Varanasi', 'slug' => 'varanasi'],
                    ['name' => 'Prayagraj', 'name_en' => 'Prayagraj', 'slug' => 'prayagraj'],
                    ['name' => 'Gorakhpur', 'name_en' => 'Gorakhpur', 'slug' => 'gorakhpur'],
                    ['name' => 'Noida', 'name_en' => 'Noida', 'slug' => 'noida'],
                    ['name' => 'Ayodhya', 'name_en' => 'Ayodhya', 'slug' => 'ayodhya'],
                    ['name' => 'Agra', 'name_en' => 'Agra', 'slug' => 'agra'],
                    ['name' => 'Meerut', 'name_en' => 'Meerut', 'slug' => 'meerut'],
                    ['name' => 'Bareilly', 'name_en' => 'Bareilly', 'slug' => 'bareilly'],
                ],
            ],
            [
                'name' => 'Bihar',
                'name_en' => 'Bihar',
                'slug' => 'bihar',
                'code' => 'BR',
                'order' => 2,
                'cities' => [
                    ['name' => 'Patna', 'name_en' => 'Patna', 'slug' => 'patna'],
                    ['name' => 'Gaya', 'name_en' => 'Gaya', 'slug' => 'gaya'],
                    ['name' => 'Muzaffarpur', 'name_en' => 'Muzaffarpur', 'slug' => 'muzaffarpur'],
                    ['name' => 'Bhagalpur', 'name_en' => 'Bhagalpur', 'slug' => 'bhagalpur'],
                    ['name' => 'Darbhanga', 'name_en' => 'Darbhanga', 'slug' => 'darbhanga'],
                    ['name' => 'Purnia', 'name_en' => 'Purnia', 'slug' => 'purnia'],
                ],
            ],
            [
                'name' => 'Delhi-NCR',
                'name_en' => 'Delhi-NCR',
                'slug' => 'delhi-ncr',
                'code' => 'DL',
                'order' => 3,
                'cities' => [
                    ['name' => 'New Delhi', 'name_en' => 'New Delhi', 'slug' => 'new-delhi'],
                    ['name' => 'South Delhi', 'name_en' => 'South Delhi', 'slug' => 'south-delhi'],
                    ['name' => 'Gurugram', 'name_en' => 'Gurugram', 'slug' => 'gurugram'],
                    ['name' => 'Ghaziabad', 'name_en' => 'Ghaziabad', 'slug' => 'ghaziabad'],
                    ['name' => 'Faridabad', 'name_en' => 'Faridabad', 'slug' => 'faridabad'],
                ],
            ],
            [
                'name' => 'Madhya Pradesh',
                'name_en' => 'Madhya Pradesh',
                'slug' => 'madhya-pradesh',
                'code' => 'MP',
                'order' => 4,
                'cities' => [
                    ['name' => 'Bhopal', 'name_en' => 'Bhopal', 'slug' => 'bhopal'],
                    ['name' => 'Indore', 'name_en' => 'Indore', 'slug' => 'indore'],
                    ['name' => 'Gwalior', 'name_en' => 'Gwalior', 'slug' => 'gwalior'],
                    ['name' => 'Jabalpur', 'name_en' => 'Jabalpur', 'slug' => 'jabalpur'],
                    ['name' => 'Ujjain', 'name_en' => 'Ujjain', 'slug' => 'ujjain'],
                ],
            ],
            [
                'name' => 'Rajasthan',
                'name_en' => 'Rajasthan',
                'slug' => 'rajasthan',
                'code' => 'RJ',
                'order' => 5,
                'cities' => [
                    ['name' => 'Jaipur', 'name_en' => 'Jaipur', 'slug' => 'jaipur'],
                    ['name' => 'Jodhpur', 'name_en' => 'Jodhpur', 'slug' => 'jodhpur'],
                    ['name' => 'Udaipur', 'name_en' => 'Udaipur', 'slug' => 'udaipur'],
                    ['name' => 'Kota', 'name_en' => 'Kota', 'slug' => 'kota'],
                    ['name' => 'Bikaner', 'name_en' => 'Bikaner', 'slug' => 'bikaner'],
                ],
            ],
            [
                'name' => 'Maharashtra',
                'name_en' => 'Maharashtra',
                'slug' => 'maharashtra',
                'code' => 'MH',
                'order' => 6,
                'cities' => [
                    ['name' => 'Mumbai', 'name_en' => 'Mumbai', 'slug' => 'mumbai'],
                    ['name' => 'Pune', 'name_en' => 'Pune', 'slug' => 'pune'],
                    ['name' => 'Nagpur', 'name_en' => 'Nagpur', 'slug' => 'nagpur'],
                    ['name' => 'Thane', 'name_en' => 'Thane', 'slug' => 'thane'],
                    ['name' => 'Nashik', 'name_en' => 'Nashik', 'slug' => 'nashik'],
                ],
            ],
            [
                'name' => 'Uttarakhand',
                'name_en' => 'Uttarakhand',
                'slug' => 'uttarakhand',
                'code' => 'UK',
                'order' => 7,
                'cities' => [
                    ['name' => 'Dehradun', 'name_en' => 'Dehradun', 'slug' => 'dehradun'],
                    ['name' => 'Haridwar', 'name_en' => 'Haridwar', 'slug' => 'haridwar'],
                    ['name' => 'Nainital', 'name_en' => 'Nainital', 'slug' => 'nainital'],
                    ['name' => 'Rishikesh', 'name_en' => 'Rishikesh', 'slug' => 'rishikesh'],
                ],
            ],
            [
                'name' => 'Jharkhand',
                'name_en' => 'Jharkhand',
                'slug' => 'jharkhand',
                'code' => 'JH',
                'order' => 8,
                'cities' => [
                    ['name' => 'Ranchi', 'name_en' => 'Ranchi', 'slug' => 'ranchi'],
                    ['name' => 'Jamshedpur', 'name_en' => 'Jamshedpur', 'slug' => 'jamshedpur'],
                    ['name' => 'Dhanbad', 'name_en' => 'Dhanbad', 'slug' => 'dhanbad'],
                ],
            ],
        ];

        foreach ($data as $sItem) {
            $cities = $sItem['cities'];
            unset($sItem['cities']);

            $state = State::firstOrCreate(['slug' => $sItem['slug']], $sItem);

            foreach ($cities as $cIndex => $cItem) {
                $cItem['order'] = $cIndex + 1;
                $cItem['state_id'] = $state->id;
                City::firstOrCreate(['slug' => $cItem['slug']], $cItem);
            }
        }

        // Attach some existing articles to states/cities for instant live demo
        $up = State::where('slug', 'uttar-pradesh')->first();
        $lucknow = City::where('slug', 'lucknow')->first();
        $varanasi = City::where('slug', 'varanasi')->first();
        $bihar = State::where('slug', 'bihar')->first();
        $patna = City::where('slug', 'patna')->first();
        $delhi = State::where('slug', 'delhi-ncr')->first();
        $newDelhi = City::where('slug', 'new-delhi')->first();

        $articles = Article::all();
        if ($articles->count() > 0 && $up && $lucknow) {
            if (isset($articles[0])) {
                $articles[0]->update(['state_id' => $delhi->id, 'city_id' => $newDelhi->id, 'location_name' => 'Parliament Street, New Delhi']);
            }
            if (isset($articles[1])) {
                $articles[1]->update(['state_id' => $up->id, 'city_id' => $lucknow->id, 'location_name' => 'Hazratganj, Lucknow']);
            }
            if (isset($articles[2])) {
                $articles[2]->update(['state_id' => $up->id, 'city_id' => $varanasi->id, 'location_name' => 'Dashashwamedh Ghat, Varanasi']);
            }
            if (isset($articles[3])) {
                $articles[3]->update(['state_id' => $bihar->id, 'city_id' => $patna->id, 'location_name' => 'Dak Bungalow, Patna']);
            }
            if (isset($articles[4])) {
                $articles[4]->update(['state_id' => $up->id, 'city_id' => $lucknow->id, 'location_name' => 'Gomti Nagar, Lucknow']);
            }
        }
    }
}
