<?php

namespace Database\Factories;

use App\Models\AdvertisingPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RevenueRecord>
 */
class RevenueRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoice = 'INV-' . fake()->unique()->numberBetween(1000, 9999);

        return [
            'advertising_package_id' => AdvertisingPackage::factory(),
            'sponsor_name' => fake()->company(),
            'contact_email' => fake()->companyEmail(),
            'amount' => fake()->randomFloat(2, 200000, 4500000),
            'currency' => 'NGN',
            'status' => fake()->randomElement(['pending', 'paid', 'overdue']),
            'invoice_number' => $invoice,
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'paid_at' => fake()->boolean(50) ? now() : null,
            'notes' => fake()->sentence(),
        ];
    }
}
