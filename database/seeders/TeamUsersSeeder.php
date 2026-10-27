<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeamUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@newskit.com'],
            [
                'name' => 'सौरभ त्रिपाठी (प्रधान संपादक)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+91 98765 43210',
                'is_active' => true,
                'bio' => 'संपादकीय नीति और राष्ट्रीय मुद्दों के विशेषज्ञ। 15+ वर्षों का निष्पक्ष पत्रकारिता अनुभव।',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 2. Editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@newskit.com'],
            [
                'name' => 'नेहा कश्यप (वरिष्ठ संपादक)',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'phone' => '+91 98765 43211',
                'is_active' => true,
                'bio' => 'राजनीति और कूटनीति की गहरी समझ। देश-विदेश के राजनीतिक विश्लेषण में माहिर।',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 3. Publisher
        $publisher = User::updateOrCreate(
            ['email' => 'publisher@newskit.com'],
            [
                'name' => 'विकास वर्मा (डिजिटल प्रकाशक)',
                'password' => Hash::make('password123'),
                'role' => 'publisher',
                'phone' => '+91 98765 43213',
                'is_active' => true,
                'bio' => 'डिजिटल मीडिया हेड एवं कंटेंट डिस्ट्रीब्यूशन लीड। ऑडियंस रीच व पब्लिशिंग स्ट्रैटेजिस्ट।',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 4. Reporter 1
        $reporter1 = User::updateOrCreate(
            ['email' => 'reporter@newskit.com'],
            [
                'name' => 'अमित शर्मा (विशेष संवाददाता)',
                'password' => Hash::make('password123'),
                'role' => 'reporter',
                'phone' => '+91 98765 43212',
                'is_active' => true,
                'bio' => 'ज़मीनी रिपोर्टर, खेल व सिनेमा जगत की अंदरूनी ख़बरों पर पैनी नज़र।',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 5. Reporter 2
        $reporter2 = User::updateOrCreate(
            ['email' => 'priya.reporter@newskit.com'],
            [
                'name' => 'प्रिया राजदान (टेक व साइंस रिपोर्टर)',
                'password' => Hash::make('password123'),
                'role' => 'reporter',
                'phone' => '+91 98765 43214',
                'is_active' => true,
                'bio' => 'AI, स्पेस मिशन (ISRO/NASA) और गैजेट्स पर विशेष कवरेज करने वाली टेक जर्नलिस्ट।',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // Assign some existing stories to Reporter 1 (Amit) and Reporter 2 (Priya)
        Article::where('slug', 'like', '%t20%')
            ->orWhere('slug', 'like', '%rohit%')
            ->orWhere('slug', 'like', '%bollywood%')
            ->update(['user_id' => $reporter1->id]);

        Article::where('slug', 'like', '%chatgpt%')
            ->orWhere('slug', 'like', '%isro%')
            ->orWhere('slug', 'like', '%vande%')
            ->update(['user_id' => $reporter2->id]);
    }
}
