<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ServiceStop;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Address;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        self::createAdminUsers();
        self::createRegularUsers();

        for ($i = 1; $i < 31; $i++) {
            self::createAddresses($i);
        }

        self::createServiceStops();

    }


    public function createServiceStops()
    {
        $users = User::all();
        foreach ($users as $user){
            $customers = $user->customers()->get();
            foreach ($customers as $customer){
                $addresses = $customer->addresses()->get();
                foreach ($addresses as $address){
                    self::createServiceStop($customer->id, $user->id, $address->id);
                }
            }
        }
    }

    public function createServiceStop($customer_id, $user_id, $address_id)
    {
        ServiceStop::factory()
            ->count(2)
            ->state([
                'customer_id' => $customer_id,
                'user_id' => $user_id,
                'address_id' => $address_id,
            ])->create();
    }

    public function createAddresses($id)
    {
        Address::factory()
            ->count(2)
            ->state([
                'customer_id' => $id
            ])
            ->create();
    }

    private function createCustomers()
    {
        Customer::factory()
            ->assignedServiceman()
            ->count(5)
            ->state(
                new Sequence(fn(Sequence $sequence) => ['order' => $sequence->index]))->create();
    }

    public function createAdminUsers()
    {
        User::factory()
            ->state([
                    'name' => 'Shawn',
                    'email' => 'pike.shawn@gmail.com',
                    'phone_number' => '14807034902',
                    'is_admin' => 1,
                    'password' => Hash::make('asdasd')
                ]
            )
            ->hasCustomers(5)
            ->create();
    }

    private function createRegularUsers()
    {

        User::factory()
            ->state([
                    'name' => 'Jeremiah',
                    'email' => Str::random(10) . '@gmail.com',
                    'is_admin' => 0,
                    'phone_number' => '14807034902',
                    'password' => Hash::make('password')
                ]
            )
            ->hasCustomers(5)->create();

        User::factory()
            ->state([
                'name' => 'Reid',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'password' => Hash::make('password')
            ])
            ->hasCustomers(5)->create();

        User::factory()
            ->state([
                'name' => 'Zach',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'password' => Hash::make('password')
            ])
            ->hasCustomers(5)->create();

        User::factory()
            ->state([
                'name' => 'Phillip',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'password' => Hash::make('password')
            ])
            ->hasCustomers(5)->create();

        User::factory()
            ->state([
                'name' => 'Elias',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'password' => Hash::make('password')
            ])
            ->hasCustomers(5)->create();

    }
}
