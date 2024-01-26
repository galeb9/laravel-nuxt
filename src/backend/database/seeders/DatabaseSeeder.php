<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		// Sample 1
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-01"),
			'product' => "produkt A",
			'quantity' => 100,
			'price' => 10,
			'direction' => "buy",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-02"),
			'product' => "produkt A",
			'quantity' => 20,
			'price' => 12,
			'direction' => "buy",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-03"),
			'product' => "produkt A",
			'quantity' => 60,
			'price' => 14,
			'direction' => "sell",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-04"),
			'product' => "produkt A",
			'quantity' => 50,
			'price' => 12,
			'direction' => "sell",
        ]);

		// Sample 2
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-01"),
			'product' => "produkt B",
			'quantity' => 300,
			'price' => 20,
			'direction' => "buy",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-02"),
			'product' => "produkt B",
			'quantity' => 80,
			'price' => 25,
			'direction' => "buy",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-03"),
			'product' => "produkt B",
			'quantity' => 350,
			'price' => 30,
			'direction' => "sell",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-04"),
			'product' => "produkt B",
			'quantity' => 100,
			'price' => 24,
			'direction' => "buy",
        ]);
		\App\Models\Transaction::create([
			'date' => \Carbon\Carbon::parse("2024-01-05"),
			'product' => "produkt B",
			'quantity' => 80,
			'price' => 10,
			'direction' => "sell",
        ]);

    }
}
