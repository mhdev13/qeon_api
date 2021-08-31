<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'user_id' => $this->faker->randomDigitNotNull(),
            'device_timestamp' => Carbon::now()->format('Y-m-d H:i:s'),
            'total_amount' => $this->faker->randomNumber(5, true),
            'paid_amount' => $this->faker->randomNumber(5, true),
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
        ];
    }
}
