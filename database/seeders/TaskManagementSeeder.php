<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaskManagementSeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create teams
        $team1 = Team::create([
            'name' => 'Development Team',
            'description' => 'Main development team for the project',
            'owner_id' => $user1->id,
        ]);

        $team2 = Team::create([
            'name' => 'Design Team',
            'description' => 'UI/UX design team',
            'owner_id' => $user2->id,
        ]);

        // Add members to teams
        $team1->members()->attach([$user1->id, $user2->id, $user3->id]);
        $team2->members()->attach([$user2->id, $user3->id]);

        // Create tasks
        Task::create([
            'title' => 'Implement User Authentication',
            'description' => 'Create login and registration system with Laravel Sanctum',
            'status' => 'completed',
            'assigned_user_id' => $user1->id,
            'due_date' => now()->addDays(7),
            'team_id' => $team1->id,
            'created_by' => $user1->id,
        ]);

        Task::create([
            'title' => 'Design User Interface',
            'description' => 'Create wireframes and mockups for the main application',
            'status' => 'in_progress',
            'assigned_user_id' => $user2->id,
            'due_date' => now()->addDays(14),
            'team_id' => $team2->id,
            'created_by' => $user2->id,
        ]);

        Task::create([
            'title' => 'Database Schema Design',
            'description' => 'Design and implement the database structure',
            'status' => 'pending',
            'assigned_user_id' => $user3->id,
            'due_date' => now()->addDays(5),
            'team_id' => $team1->id,
            'created_by' => $user1->id,
        ]);

        Task::create([
            'title' => 'API Documentation',
            'description' => 'Write comprehensive API documentation',
            'status' => 'pending',
            'assigned_user_id' => $user1->id,
            'due_date' => now()->addDays(10),
            'team_id' => $team1->id,
            'created_by' => $user2->id,
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('Users: john@example.com, jane@example.com, mike@example.com');
        $this->command->info('Password: password123');
    }
}
