<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionItem;

class TransactionItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionItem::factory(5)->create();
    }
}
