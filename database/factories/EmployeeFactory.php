<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Employee::class;
    public function definition()
    {
        return [
                    "full_name" => $this->faker->firstName. " " . $this->faker->lastName,
                    "position_id" =>rand(1,5),
                    "entry_date"=>$this->faker->date,
                    "telephone_number"=>"+380"." (".rand(10,99).")"." ".rand(0,9).rand(0,9).rand(0,9)." ".rand(10,99)." ".rand(10,99),
                    "email"=>$this->faker->email,
                    "salary"=>rand(0,500000)*(1/rand(1,10)),
                    "photo"=>"img/default_photo.png",
                    "admin_created_id"=>1,
                    "admin_updated_id"=>1
        ];
    }
}
