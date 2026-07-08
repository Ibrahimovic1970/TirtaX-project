<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user lama jika ada (untuk menghindari duplikat)
        User::where('email', 'admin@tirtax.com')->delete();
        User::where('email', 'kurir@tirtax.com')->delete();

        // 1. Buat Admin
        User::create([
            'name' => 'Admin TirtaX',
            'email' => 'admin@tirtax.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 2. Buat Courier
        User::create([
            'name' => 'Kurir TirtaX',
            'email' => 'kurir@tirtax.com',
            'password' => Hash::make('password123'),
            'role' => 'courier',
        ]);


        // Tampilkan info di terminal
        $this->command->info('✅ User berhasil dibuat!');
        $this->command->info('================================');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@tirtax.com');
        $this->command->info('  Password: admin123');
        $this->command->info('  Role: admin');
        $this->command->info('');
        $this->command->info('Kurir:');
        $this->command->info('  Email: kurir@tirtax.com');
        $this->command->info('  Password: password123');
        $this->command->info('  Role: courier');
        $this->command->info('');
    }
}