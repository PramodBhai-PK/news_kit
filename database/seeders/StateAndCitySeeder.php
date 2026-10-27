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
                'name' => 'उत्तर प्रदेश',
                'name_en' => 'Uttar Pradesh',
                'slug' => 'uttar-pradesh',
                'code' => 'UP',
                'order' => 1,
                'cities' => [
                    ['name' => 'लखनऊ', 'name_en' => 'Lucknow', 'slug' => 'lucknow'],
                    ['name' => 'कानपुर', 'name_en' => 'Kanpur', 'slug' => 'kanpur'],
                    ['name' => 'वाराणसी', 'name_en' => 'Varanasi', 'slug' => 'varanasi'],
                    ['name' => 'प्रयागराज', 'name_en' => 'Prayagraj', 'slug' => 'prayagraj'],
                    ['name' => 'गोरखपुर', 'name_en' => 'Gorakhpur', 'slug' => 'gorakhpur'],
                    ['name' => 'नोएडा', 'name_en' => 'Noida', 'slug' => 'noida'],
                    ['name' => 'अयोध्या', 'name_en' => 'Ayodhya', 'slug' => 'ayodhya'],
                    ['name' => 'आगरा', 'name_en' => 'Agra', 'slug' => 'agra'],
                    ['name' => 'मेरठ', 'name_en' => 'Meerut', 'slug' => 'meerut'],
                    ['name' => 'बरेली', 'name_en' => 'Bareilly', 'slug' => 'bareilly'],
                ],
            ],
            [
                'name' => 'बिहार',
                'name_en' => 'Bihar',
                'slug' => 'bihar',
                'code' => 'BR',
                'order' => 2,
                'cities' => [
                    ['name' => 'पटना', 'name_en' => 'Patna', 'slug' => 'patna'],
                    ['name' => 'गया', 'name_en' => 'Gaya', 'slug' => 'gaya'],
                    ['name' => 'मुज़फ़्फ़रपुर', 'name_en' => 'Muzaffarpur', 'slug' => 'muzaffarpur'],
                    ['name' => 'भागलपुर', 'name_en' => 'Bhagalpur', 'slug' => 'bhagalpur'],
                    ['name' => 'दरभंगा', 'name_en' => 'Darbhanga', 'slug' => 'darbhanga'],
                    ['name' => 'पूर्णिया', 'name_en' => 'Purnia', 'slug' => 'purnia'],
                ],
            ],
            [
                'name' => 'दिल्ली-NCR',
                'name_en' => 'Delhi-NCR',
                'slug' => 'delhi-ncr',
                'code' => 'DL',
                'order' => 3,
                'cities' => [
                    ['name' => 'नई दिल्ली', 'name_en' => 'New Delhi', 'slug' => 'new-delhi'],
                    ['name' => 'दक्षिणी दिल्ली', 'name_en' => 'South Delhi', 'slug' => 'south-delhi'],
                    ['name' => 'गुरुग्राम', 'name_en' => 'Gurugram', 'slug' => 'gurugram'],
                    ['name' => 'गाज़ियाबाद', 'name_en' => 'Ghaziabad', 'slug' => 'ghaziabad'],
                    ['name' => 'फरीदाबाद', 'name_en' => 'Faridabad', 'slug' => 'faridabad'],
                ],
            ],
            [
                'name' => 'मध्य प्रदेश',
                'name_en' => 'Madhya Pradesh',
                'slug' => 'madhya-pradesh',
                'code' => 'MP',
                'order' => 4,
                'cities' => [
                    ['name' => 'भोपाल', 'name_en' => 'Bhopal', 'slug' => 'bhopal'],
                    ['name' => 'इंदौर', 'name_en' => 'Indore', 'slug' => 'indore'],
                    ['name' => 'ग्वालियर', 'name_en' => 'Gwalior', 'slug' => 'gwalior'],
                    ['name' => 'जबलपुर', 'name_en' => 'Jabalpur', 'slug' => 'jabalpur'],
                    ['name' => 'उज्जैन', 'name_en' => 'Ujjain', 'slug' => 'ujjain'],
                ],
            ],
            [
                'name' => 'राजस्थान',
                'name_en' => 'Rajasthan',
                'slug' => 'rajasthan',
                'code' => 'RJ',
                'order' => 5,
                'cities' => [
                    ['name' => 'जयपुर', 'name_en' => 'Jaipur', 'slug' => 'jaipur'],
                    ['name' => 'जोधपुर', 'name_en' => 'Jodhpur', 'slug' => 'jodhpur'],
                    ['name' => 'उदयपुर', 'name_en' => 'Udaipur', 'slug' => 'udaipur'],
                    ['name' => 'कोटा', 'name_en' => 'Kota', 'slug' => 'kota'],
                    ['name' => 'बीकानेर', 'name_en' => 'Bikaner', 'slug' => 'bikaner'],
                ],
            ],
            [
                'name' => 'महाराष्ट्र',
                'name_en' => 'Maharashtra',
                'slug' => 'maharashtra',
                'code' => 'MH',
                'order' => 6,
                'cities' => [
                    ['name' => 'मुंबई', 'name_en' => 'Mumbai', 'slug' => 'mumbai'],
                    ['name' => 'पुणे', 'name_en' => 'Pune', 'slug' => 'pune'],
                    ['name' => 'नागपुर', 'name_en' => 'Nagpur', 'slug' => 'nagpur'],
                    ['name' => 'ठाणे', 'name_en' => 'Thane', 'slug' => 'thane'],
                    ['name' => 'नासिक', 'name_en' => 'Nashik', 'slug' => 'nashik'],
                ],
            ],
            [
                'name' => 'उत्तराखंड',
                'name_en' => 'Uttarakhand',
                'slug' => 'uttarakhand',
                'code' => 'UK',
                'order' => 7,
                'cities' => [
                    ['name' => 'देहरादून', 'name_en' => 'Dehradun', 'slug' => 'dehradun'],
                    ['name' => 'हरिद्वार', 'name_en' => 'Haridwar', 'slug' => 'haridwar'],
                    ['name' => 'नैनीताल', 'name_en' => 'Nainital', 'slug' => 'nainital'],
                    ['name' => 'ऋषिकेश', 'name_en' => 'Rishikesh', 'slug' => 'rishikesh'],
                ],
            ],
            [
                'name' => 'झारखंड',
                'name_en' => 'Jharkhand',
                'slug' => 'jharkhand',
                'code' => 'JH',
                'order' => 8,
                'cities' => [
                    ['name' => 'रांची', 'name_en' => 'Ranchi', 'slug' => 'ranchi'],
                    ['name' => 'जमशेदपुर', 'name_en' => 'Jamshedpur', 'slug' => 'jamshedpur'],
                    ['name' => 'धनबाद', 'name_en' => 'Dhanbad', 'slug' => 'dhanbad'],
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
                $articles[0]->update(['state_id' => $delhi->id, 'city_id' => $newDelhi->id, 'location_name' => 'संसद मार्ग, नई दिल्ली']);
            }
            if (isset($articles[1])) {
                $articles[1]->update(['state_id' => $up->id, 'city_id' => $lucknow->id, 'location_name' => 'हज़रतगंज, लखनऊ']);
            }
            if (isset($articles[2])) {
                $articles[2]->update(['state_id' => $up->id, 'city_id' => $varanasi->id, 'location_name' => 'दशाश्वमेध घाट, वाराणसी']);
            }
            if (isset($articles[3])) {
                $articles[3]->update(['state_id' => $bihar->id, 'city_id' => $patna->id, 'location_name' => 'डाक बंगला चौराहा, पटना']);
            }
            if (isset($articles[4])) {
                $articles[4]->update(['state_id' => $up->id, 'city_id' => $lucknow->id, 'location_name' => 'गोमती नगर, लखनऊ']);
            }
        }
    }
}
