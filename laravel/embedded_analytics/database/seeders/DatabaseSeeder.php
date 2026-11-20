<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the demo customers and users, so you can sign in right away.
     *
     * Each customer's ID matches an Account ID in Metabase's Sample Database.
     */
    public function run(): void
    {
        $demoCustomers = [
            ['id' => 5, 'name' => 'Acme Corp', 'users' => [
                ['name' => 'Alice', 'email' => 'alice@example.com'],
                ['name' => 'Bob', 'email' => 'bob@example.com'],
            ]],
            ['id' => 12, 'name' => 'Bluebird Labs', 'users' => [
                ['name' => 'Carol', 'email' => 'carol@example.com'],
            ]],
        ];

        foreach ($demoCustomers as $demoCustomer) {
            // The ID isn't mass-assignable, so set it with forceCreate.
            $customer = Customer::find($demoCustomer['id']) ?? Customer::forceCreate([
                'id' => $demoCustomer['id'],
                'name' => $demoCustomer['name'],
            ]);

            foreach ($demoCustomer['users'] as $demoUser) {
                $customer->users()->firstOrCreate(
                    ['email' => $demoUser['email']],
                    ['name' => $demoUser['name'], 'password' => 'password'],
                );
            }
        }
    }
}
