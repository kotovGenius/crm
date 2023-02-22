<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Position::class;
    public function definition()
    {
        $positions = ['Leading specialist of the Control Department', "Contextual advertising specialist", "Load designer", "Frontend developer","Backend developer"];

        return [
            "name" => $positions[rand(0,count($positions)-1)]
        ];
    }
}
