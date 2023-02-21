<?php

namespace Database\Factories;

class PartnerFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => uuid(),
            'name' => fake()->name(),
            'handle' => fake()->domainWord(),
            'status' => fake()->boolean()
        ];
    }
}
