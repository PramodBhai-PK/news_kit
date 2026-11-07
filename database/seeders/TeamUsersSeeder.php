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
                'name' => 'John Anderson (Editor-in-Chief)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+1 (555) 019-2830',
                'is_active' => true,
                'bio' => 'Expertise in editorial policy and national investigative affairs. 15+ years of verified journalism experience.',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 2. Editor
        $editor = User::updateOrCreate(
            ['email' => 'editor@newskit.com'],
            [
                'name' => 'Sarah Mitchell (Senior Editor)',
                'password' => Hash::make('password123'),
                'role' => 'editor',
                'phone' => '+1 (555) 019-2831',
                'is_active' => true,
                'bio' => 'Deep expertise in politics, diplomacy, and global affairs.',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 3. Publisher
        $publisher = User::updateOrCreate(
            ['email' => 'publisher@newskit.com'],
            [
                'name' => 'Marcus Vance (Digital Publisher)',
                'password' => Hash::make('password123'),
                'role' => 'publisher',
                'phone' => '+1 (555) 019-2833',
                'is_active' => true,
                'bio' => 'Head of digital media and multi-platform distribution strategy.',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 4. Reporter 1
        $reporter1 = User::updateOrCreate(
            ['email' => 'reporter@newskit.com'],
            [
                'name' => 'David Miller (Senior Field Reporter)',
                'password' => Hash::make('password123'),
                'role' => 'reporter',
                'phone' => '+1 (555) 019-2832',
                'is_active' => true,
                'bio' => 'Ground investigative correspondent focusing on sports and entertainment features.',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // 5. Reporter 2
        $reporter2 = User::updateOrCreate(
            ['email' => 'elena.reporter@newskit.com'],
            [
                'name' => 'Elena Rostova (Tech & Science Correspondent)',
                'password' => Hash::make('password123'),
                'role' => 'reporter',
                'phone' => '+1 (555) 019-2834',
                'is_active' => true,
                'bio' => 'Specializing in artificial intelligence, space missions, and emerging consumer tech.',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&auto=format&fit=crop&q=80',
            ]
        );

        // Assign some existing stories to Reporter 1 (David) and Reporter 2 (Elena)
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
