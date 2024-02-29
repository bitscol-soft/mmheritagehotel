<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Hotel\Models\Guest;

class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Guest::class;

    public function definition()
    {
        return [
                'name'                => $this->faker->name(),
                'email'               => $this->faker->unique()->safeEmail(),
                'phone_no'            => $this->faker->phoneNumber(),
                'country_id'          => 18,
                'gender'              => $this->faker->randomElement(['male', 'female']),
                'address'             => $this->faker->address(),
                'created_by'          => 1,

        ];
    }
}
