<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = User::create([
            'name' => 'Admin 2',
            'username' => 'admin2',
            'phone' => '081234567',
            'password' => Hash::make('admin123'),
        ]);


        $administrator = Role::where('name','admin')->first();
        $user->addRole($administrator);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
