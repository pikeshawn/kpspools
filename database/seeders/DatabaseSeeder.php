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
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {

//        $faker = Faker::create();
//
//        dd($faker->streetAddress);

        self::createAdminUsers();
//        self::createRegularUsers();
//        self::createCustomers();

//        for ($i = 1; $i < 31; $i++) {
//            $customer = Customer::find($i);
////            dd($customer);
//            self::createAddresses($i, $customer->serviceman_id);
//        }

        self::createServiceStops();

    }


    public function createServiceStops()
    {
        $users = User::where('type', 'serviceman')->get();
        foreach ($users as $user) {
            $customers = Customer::where('serviceman_id', $user->id)->get();
            foreach ($customers as $customer) {
                $addresses = $customer->addresses()->get();
                foreach ($addresses as $address) {
                    self::createServiceStop($customer->id, $user->id, $address->id);
                }
            }
        }
    }

    public function createServiceStop($customer_id, $serviceman_id, $address_id)
    {
        ServiceStop::factory()
            ->count(2)
            ->state([
                'customer_id' => $customer_id,
                'user_id' => $serviceman_id,
                'address_id' => $address_id,
            ])->create();
    }

    public function createAddresses($id, $serviceman_id)
    {

        Address::factory()
            ->count(2)
            ->state([
                'customer_id' => $id,
                'serviceman_id' => $serviceman_id
            ])
            ->create();
    }

    private function createCustomers()
    {

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {

            $rand = rand(1, 6);
            $serviceman = User::find($rand);

            $type = 'customer';
            $active = 1;

            if ($i > 90) {
                $type = 'prospective';
                $rand = 0;
            }

            if ($i > 70) {
                $active = 0;
            }

            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user = User::create([
                'name' => $firstName . " " . $lastName,
                'email' => $faker->email,
                'phone_number' => '14806226441',
                'type' => $type,
                'active' => 1,
                'is_admin' => 0,
                'password' => Hash::make('password')
            ]);

            $serviceDay = $faker->dayOfWeek;

            $customer = Customer::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'active' => $active,
                'type' => 'Service',
                'plan' => null,
                'plan_duration' => 'Monthly',
                'service_day' => $serviceDay,
                'plan_price' => null,
                'chemicals_included' => 1,
                'assigned_serviceman' => $serviceman->name,
                'serviceman_id' => $serviceman->id,
                'phone_number' => '14807034902',
                'terms' => 'begin'
            ]);

            Address::create([
                'customer_id' => $customer->id,
                'address_line_1' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => 'AZ',
                'service_day' => $serviceDay,
                'serviceman_id' => $serviceman->id,
                'zip' => $faker->postcode
            ]);
        }
    }

    public function createAdminUsers()
    {
//        User::factory()
//            ->state([
//                    'name' => 'Shawn',
//                    'email' => 'pike.shawn@gmail.com',
//                    'phone_number' => '14807034902',
//                    'type' => 'serviceman',
//                    'is_admin' => 1,
//                    'password' => Hash::make('asdasd')
//                ]
//            )
//            ->create();

        User::factory()->create();
    }

    private function createRegularUsers()
    {

        User::factory()
            ->state([
                    'name' => 'Jeremiah',
                    'email' => Str::random(10) . '@gmail.com',
                    'is_admin' => 0,
                    'phone_number' => '14807034902',
                    'type' => 'serviceman',
                    'password' => Hash::make('password')
                ]
            )
            ->create();

        User::factory()
            ->state([
                'name' => 'Reid',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'type' => 'serviceman',
                'password' => Hash::make('password')
            ])
            ->create();

        User::factory()
            ->state([
                'name' => 'Zach',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'type' => 'serviceman',
                'password' => Hash::make('password')
            ])
            ->create();

        User::factory()
            ->state([
                'name' => 'Phillip',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'type' => 'serviceman',
                'password' => Hash::make('password')
            ])
            ->create();

        User::factory()
            ->state([
                'name' => 'Elias',
                'email' => Str::random(10) . '@gmail.com',
                'is_admin' => 0,
                'phone_number' => '14807034902',
                'type' => 'serviceman',
                'password' => Hash::make('password')
            ])
            ->create();

    }

}
