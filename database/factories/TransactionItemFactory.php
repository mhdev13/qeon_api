<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'transaction_id' => $this->faker->randomDigitNotNull(),
            'title' => $this->faker->sentence(mt_rand(2,2)),
            'qty' => $this->faker->randomDigitNotNull(),
            'price' => $this->faker->randomNumber(5, true),
        ];
    }
}
