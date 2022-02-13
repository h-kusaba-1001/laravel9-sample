<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'production') {
            AdminUser::factory([
                'login_id' => 'valid',
                'is_active' => true,
            ])->create();
            AdminUser::factory([
                'login_id' => 'deleted',
                'is_active' => true,
                'deleted_at' => Carbon::now(),
            ])->create();
            AdminUser::factory([
                'login_id' => 'inactive',
                'is_active' => false,
            ])->create();
        }
    }
}
