<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => '',
            'city' => $this->faker->city(),
            'state' => 'AZ',
            'zip' => $this->faker->postcode(),
            'community_gate_code' => '#'.random_int(1000, 9999),
            'house_gate_has_lock' => $this->faker->boolean(),
        ];
    }
}
