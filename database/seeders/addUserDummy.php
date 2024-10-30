<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class addUserDummy extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil role konsumen
        $konsumenRole = Role::where('name', 'konsumen')->first();

        // Buat 200 pengguna dengan role konsumen
        for ($i = 1; $i <= 200; $i++) {
            $user = User::create([
                'name' => 'Konsumen ' . $i,
                'username' => 'konsumen' . $i, // Username unik
                'phone' => '081234' . str_pad($i, 6, '0', STR_PAD_LEFT), // Nomor telepon unik
                'password' => Hash::make('sipeta123'), 
            ]);

            // Tambahkan role konsumen ke user
            $user->addRole($konsumenRole);
        }
    }
}
