<?php

namespace Database\Seeders;

use App\Difficulty;
use App\Models\Agenda;
use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // User 1 (matches README)
        $user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // User 2 (for isolation testing)
        $user2 = User::factory()->create([
            'name' => 'Demo User 2',
            'email' => 'demo2@example.com',
            'password' => bcrypt('password'),
        ]);

        // Courses for user 1
        $courses1 = collect([
            Course::factory()->create(['user_id' => $user1->id, 'name' => 'Pemrograman Web', 'code' => 'PW001', 'color' => '#4F46E5']),
            Course::factory()->create(['user_id' => $user1->id, 'name' => 'Basis Data', 'code' => 'BD002', 'color' => '#059669']),
            Course::factory()->create(['user_id' => $user1->id, 'name' => 'Algoritma', 'code' => 'ALG003', 'color' => '#D97706']),
        ]);

        // Courses for user 2
        $courses2 = collect([
            Course::factory()->create(['user_id' => $user2->id, 'name' => 'Jaringan Komputer', 'code' => 'JK001', 'color' => '#DC2626']),
            Course::factory()->create(['user_id' => $user2->id, 'name' => 'Sistem Operasi', 'code' => 'SO002', 'color' => '#7C3AED']),
        ]);

        // Tasks for user 1 - various deadlines
        $now = now();
        $taskData = [
            ['title' => 'Laporan Proyek Akhir', 'difficulty' => Difficulty::Sulit, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()->subDays(2)],
            ['title' => 'Quiz Basis Data', 'difficulty' => Difficulty::Sedang, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()],
            ['title' => 'Tugas Algoritma Minggu 5', 'difficulty' => Difficulty::Mudah, 'status' => TaskStatus::InProgress, 'deadline' => $now->copy()->addDays(2)],
            ['title' => 'UTS Pemrograman Web', 'difficulty' => Difficulty::Sulit, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()->addDays(5)],
            ['title' => 'Resume Jurnal', 'difficulty' => Difficulty::Mudah, 'status' => TaskStatus::Done, 'deadline' => $now->copy()->addDays(3)],
            ['title' => 'Presentasi Kelompok', 'difficulty' => Difficulty::Sedang, 'status' => TaskStatus::InProgress, 'deadline' => $now->copy()->addDays(7)],
            ['title' => 'Coding Challenge', 'difficulty' => Difficulty::Sulit, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()->addDays(10)],
            ['title' => 'Review Code Teman', 'difficulty' => Difficulty::Mudah, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()->addDays(1)],
            ['title' => 'Dokumentasi API', 'difficulty' => Difficulty::Sedang, 'status' => TaskStatus::Done, 'deadline' => $now->copy()->subDays(1)],
            ['title' => 'Testing Aplikasi', 'difficulty' => Difficulty::Sulit, 'status' => TaskStatus::Todo, 'deadline' => $now->copy()->addDays(4)],
        ];

        foreach ($taskData as $i => $data) {
            $task = Task::factory()->create([
                'user_id' => $user1->id,
                'course_id' => $courses1->random()->id,
                'title' => $data['title'],
                'difficulty' => $data['difficulty'],
                'status' => $data['status'],
                'deadline' => $data['deadline'],
                'completed_at' => $data['status'] === TaskStatus::Done ? now() : null,
            ]);
        }

        // Tasks for user 2
        for ($i = 0; $i < 3; $i++) {
            Task::factory()->create([
                'user_id' => $user2->id,
                'course_id' => $courses2->random()->id,
            ]);
        }

        // Agendas for user 1
        for ($i = 0; $i < 4; $i++) {
            Agenda::factory()->create([
                'user_id' => $user1->id,
                'date' => $now->copy()->addDays($i + 1)->format('Y-m-d'),
            ]);
        }
    }
}
