<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Province;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{

    protected $model = Customer::class; // Ensure this is set

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $provinceIds = Province::pluck('id')->toArray(); // Fetch all province IDs from the database

        return [
            'customer_name' => $this->faker->name(),
            'npwp' => $this->faker->numerify('################'), // ✅ Ensures 16-digit number
            'nik_ktp' => $this->faker->numerify('################'), // ✅ Ensures 16-digit number
            'registration_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['private', 'corporate', 'breeder']),
            'phone_code' => $this->faker->numberBetween(100, 999), // ✅ Generates a 3-digit code
            'telephone_number' => $this->faker->phoneNumber(),
            'province_id' => $this->faker->randomElement($provinceIds), // ✅ Fix `$this->fake` typo
        ];
    }
}
