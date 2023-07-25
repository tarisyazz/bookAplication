<?php

namespace Database\Factories;

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Eloquent\Factories\Factory;

class novelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => $this->faker->name,
            "description" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus cupiditate reiciendis, nobis quae nulla iusto ad dolorem expedita animi quos rerum quam, voluptatem officia qui. Maiores ad cupiditate quis expedita!.",
            "price" => $this->faker->numberBetween($min = 15000, $max = 60000),
            "status" => $this->faker->numberBetween($int1=0, $int2=1)
        ];
    }
}
