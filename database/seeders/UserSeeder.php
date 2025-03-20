<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        self::createUser('Shawn', 1, 'serviceman', 1);
        self::createUser('Jeremiah', 0, 'serviceman', 1);
        self::createUser('Reid', 0, 'serviceman', 1);
        self::createUser('Zach', 0, 'serviceman', 1);
        self::createUser('Phillip', 0, 'serviceman', 1);
        self::createUser('Elias', 0, 'serviceman', 1);

        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {

            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $fullName = $firstName.' '.$lastName;
            self::createUser(
                $fullName,
                0,
                'customer',
                1
            );

            $currentUser = self::getUser($fullName);

            self::createCustomer(
                $currentUser->id,
                $firstName,
                $firstName,
                $lastName
            );

        }

    }

    private function getUser($fullName)
    {
        return User::where('name', $fullName)->first();
    }

    private function getCustomer($firstName, $lastName)
    {
        return Customer::where('first_name', $firstName)->
        where('last_name', $lastName)->
        first();

    }

    private function createCustomer(
        $customerId,
        $firstName,
        $lastName
    ) {

        $serviceDay = self::serviceDay();
        $order = self::setOrder();
        $terms = self::terms();
        $servicemanId = random_int(6, 8);

        $faker = Faker::create();

        DB::table('customers')->insert([
            'serviceman_id' => $servicemanId,
            'user_id' => $customerId,
            'first_name' => $firstName,
            'middle_name' => $firstName,
            'last_name' => $lastName,
            'active' => $faker->boolean(),
            'type' => null,
            'plan' => null,
            'service_day' => $serviceDay,
            'order' => $order,
            'plan_duration' => 'Monthly',
            'plan_price' => 120,
            'chemicals_included' => $faker->boolean(),
            'assigned_serviceman' => null,
            'phone_number' => '14807034902',
            'terms' => $terms,
            'jemmson_id' => $order,
        ]);

        $customer = self::getCustomer($firstName, $lastName);

        self::createAddress(
            $customer->id,
            $serviceDay,
            $order,
            $terms,
            $servicemanId
        );
    }

    private function createAddress($customerId,
        $serviceDay,
        $order,
        $terms,
        $servicemanId
    ) {
        $faker = Faker::create();

        DB::table('addresses')->insert([
            'address_line_1' => $faker->streetAddress(),
            'customer_id' => $customerId,
            'address_line_2' => '',
            'city' => $faker->city(),
            'state' => 'AZ',
            'zip' => $faker->postcode(),
            'community_gate_code' => '#'.random_int(1000, 9999),
            'house_gate_has_lock' => $faker->boolean(),
            'active' => $faker->boolean(),
            'type' => null,
            'plan' => null,
            'service_day' => $serviceDay,
            'order' => $order,
            'plan_duration' => 'Monthly',
            'plan_price' => 120,
            'chemicals_included' => $faker->boolean(),
            'assigned_serviceman' => null,
            'serviceman_id' => $servicemanId,
            'terms' => $terms,
        ]);
    }

    public function assignedServiceman(): Factory
    {
        $p = ['Shawn', 'Jeremiah', 'Reid', 'Zach', 'Phillip', 'Elias'];
        $serviceMan = $p[random_int(0, 5)];
        $user = User::where('name', $serviceMan)->first();

        return $this->state(fn (array $attributes) => [
            'serviceman_id' => $user->id,
            'assigned_serviceman' => $serviceMan,
        ]);
    }

    private function setOrder(): int
    {
        $customers = Customer::where('active', true)->get();

        return count($customers) + 1;
    }

    public function terms(): string
    {
        $p = ['begin', 'end'];

        return $p[random_int(0, 1)];
    }

    public function serviceDay(): string
    {
        $p = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return $p[random_int(0, 4)];
    }

    private function createUser(
        $name,
        $is_admin,
        $type,
        $active
    ) {
        DB::table('users')->insert([
            'name' => $name,
            'email' => Str::random(10).'@gmail.com',
            'is_admin' => $is_admin,
            'phone_number' => '14807034902',
            'password' => Hash::make('password'),
            'type' => $type,
            'active' => $active,
        ]);

    }
}
