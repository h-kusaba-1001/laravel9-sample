<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'production') {
            $this->customer = Customer::factory([
                'email' => 'customer@example.com',
            ])->create();
            $this->deletedCustomer = Customer::factory([
                'email' => 'deleted@example.com',
                'deleted_at' => Carbon::now(),
            ])->create();
        }
    }
}
