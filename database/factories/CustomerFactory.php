<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            //
            'serviceman_id' => random_int(6, 8),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'active' => $this->faker->boolean,
            'type' => null,
            'plan' => null,
            'service_day' => self::serviceDay(),
            'order' => self::setOrder(),
            'plan_duration' => 'Monthly',
            'plan_price' => 120,
            'chemicals_included' => $this->faker->boolean,
            'assigned_serviceman' => null,
            'phone_number' => '14807034902',
            'terms' => self::terms(),
            'jemmson_id' => self::setOrder(),
        ];
    }

    public function serviceDay(): String
    {
        $p = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        return $p[random_int(0, 4)];
    }

    public function assignedServiceman(): Factory
    {
        $p = ['Shawn', 'Jeremiah', 'Reid', 'Zach', 'Phillip', 'Elias'];
        $serviceMan = $p[random_int(0, 5)];
        $user = User::where('name', $serviceMan)->first();

        return $this->state(fn(array $attributes) => [
            'serviceman_id' => $user->id,
            'assigned_serviceman' => $serviceMan
        ]);
    }

    public function terms(): String
    {
        $p = ['begin', 'end'];
        return $p[random_int(0, 1)];
    }

    public function active(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'active' => 1,
            'order' => self::setOrder()
        ]);
    }

    public function inActive(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'active' => 0
        ]);
    }

    private function setOrder(): int
    {
        $customers = Customer::where('active', true)->get();
        return count($customers) + 1;
    }


}
