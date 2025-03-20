<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceStopFactory extends Factory
{
    protected ?Carbon $startTime = null;

    protected ?Carbon $endTime = null;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            //
            'ph_level' => random_int(6, 8),
            'chlorine_level' => random_int(0, 10),
            'tabs_whole_mine' => random_int(0, 20),
            'liquid_chlorine' => random_int(0, 20),
            'liquid_acid' => random_int(0, 1),
            'time_in' => self::startTime(),
            'time_out' => self::add15minutes(),
            'service_time' => self::serviceTime(),
            'vacuum' => $this->faker->boolean(),
            'brush' => $this->faker->boolean(),
            'empty_baskets' => $this->faker->boolean(),
            'backwash' => $this->faker->boolean(),
            'powder_chlorine' => random_int(6, 8),
            'notes' => $this->faker->text(),
            'service_type' => self::serviceType(),
            'salt_level' => random_int(400, 8000),
        ];
    }

    public function highChlorine(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'chlorine_level' => random_int(5, 10),
            'liquid_chlorine' => 0,
            'tabs_whole_mine' => 1,
        ]);
    }

    public function lowChlorine(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'chlorine_level' => random_int(0, 2),
            'liquid_chlorine' => random_int(1, 4),
            'tabs_whole_mine' => random_int(3, 8),
        ]);
    }

    public function highPh(): Factory
    {
        $a = [0.0, 0.25, 0.5, 0.75, 1.0];

        return $this->state(fn (array $attributes) => [
            'ph_level' => random_int(8, 16),
            'liquid_acid' => $a[random_int(0, 3)],
        ]);
    }

    public function lowPh(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'ph_level' => random_int(8, 16),
            'liquid_acid' => 0,
        ]);
    }

    public function noSalt(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'salt_level' => null,
        ]);
    }

    private function startTime()
    {
        if (is_null($this->startTime)) {
            $this->startTime = now();
        } elseif (is_null($this->endTime)) {
            $this->startTime = $this->startTime->addMinutes(5);
        } elseif (! is_null($this->endTime)) {
            $this->startTime = $this->endTime->addMinutes(5);
        }

        return $this->startTime;
    }

    private function add15minutes()
    {
        $this->endTime = $this->startTime->addMinutes(15);

        return $this->endTime;
    }

    private function serviceTime()
    {
        return $this->endTime->sub($this->startTime);
    }

    private function serviceType()
    {
        $serviceTypes = [
            'Service Stop',
            'Repair',
            'Clear Green Pool',
            'Chemical Stop',
            'Intro',
        ];

        $rand = random_int(0, 4);

        return $serviceTypes[$rand];
    }
}
