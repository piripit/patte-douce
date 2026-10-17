<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'prestataire@pattedouce.fr']);
        $user->name = 'Prestataire';
        $user->password = Hash::make('clemroot');
        $user->is_admin = true;
        $user->save();
    }
}
