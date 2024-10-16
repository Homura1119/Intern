<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testData = [
            ['name' => 'HPかいふく薬', 'price' => 10,'type' => 1, 'value' => 100],
            ['name' => 'MPかいふく薬', 'price' => 50,'type' => 2, 'value' => 20]
        ];

        foreach ($testData as $datum) {
            $item = new Item;
            $item->name = $datum['name'];
            $item->price = $datum['price'];
            $item->type = $datum['type'];
            $item->value = $datum['value'];
            $item->save();
        }
    }
}
