<?php

namespace Database\Factories;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'foto_profile' => 'laravel.256x256.png',
            'jabatan' => Jabatan::all()->random(rand(1, 3))->pluck('nama')->toArray(),
            'tanggal_dipekerjakan' => $this->faker->date(),
            'tanggal_berhenti' => $this->faker->date(),
            'gaji' => $this->faker->numberBetween(1, 50) * 100000,
        ];
    }
}
