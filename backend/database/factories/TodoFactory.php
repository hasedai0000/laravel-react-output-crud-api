<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
 /**
  * The name of the factory's corresponding model.
  *
  * @var string
  */
 protected $model = Todo::class;

 /**
  * Define the model's default state.
  *
  * @return array<string, mixed>
  */
 public function definition(): array
 {
  return [
   'user_id' => User::factory(),
   'title' => $this->faker->sentence(),
   'content' => $this->faker->paragraph(),
   'status' => $this->faker->randomElement(['incomplete', 'complete']),
  ];
 }

 /**
  * Indicate that the todo is incomplete.
  */
 public function incomplete(): static
 {
  return $this->state(fn(array $attributes) => [
   'status' => 'incomplete',
  ]);
 }

 /**
  * Indicate that the todo is complete.
  */
 public function complete(): static
 {
  return $this->state(fn(array $attributes) => [
   'status' => 'complete',
  ]);
 }
}
