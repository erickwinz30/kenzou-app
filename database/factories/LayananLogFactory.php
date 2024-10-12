<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LayananLog>
 */
class LayananLogFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'layanan_id' => Layanan::factory(),
      'nama_layanan' => $this->faker->word,
      'harga' => $this->faker->randomFloat(2, 10000, 100000),
      'point' => $this->faker->numberBetween(1, 10),
      'detail' => $this->faker->sentence,
      'deleted_at' => null,
    ];
  }
}
