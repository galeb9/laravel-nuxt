<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;

class CalculateStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate FIFO stock from transactions in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::orderBy('product')->orderBy('date')->get();
        $stock = [];

        foreach ($transactions as $transaction) {
            $quantity = $transaction->quantity;
            $price = $transaction->price;
            $name = $transaction->product;
            $type= $transaction->direction;


            if ($type == 'buy') {

                if (!isset($stock[$name])) {
                    $stock[$name] = [];
                }

                $stock[$name][] = ['quantity' => $quantity, 'price' => $price];

            } elseif ($type == 'sell') {

                if (isset($stock[$name])) {

                    while ($quantity > 0 && count($stock[$name]) > 0) {
                        $item = array_shift($stock[$name]);
                        $itemQuantity = $item['quantity'];

                        if ($quantity >= $itemQuantity) {
                            $quantity -= $itemQuantity;

                        } else {
                            $item['quantity'] -= $quantity;
                            array_unshift($stock[$name], $item);
                            $quantity = 0;
                        }
                    }
                } else {
                    $this->error('Napaka: zaloga ne mora biti negativna ' . $name);
                    return;
                }
            }
        }

        $total = 0;
        foreach ($stock as $product => $productStock) {
            if (empty($productStock)) {
                $this->error("Napaka: Zaloga produkta $product je prazna.");
                continue; // Skip to the next iteration if the stock is empty
            }

            $productQuantity = array_sum(array_column($productStock, 'quantity'));
            $productValue = array_sum(array_map(function ($item) {
                return $item['quantity'] * $item['price'];
            }, $productStock));

            $this->info("Zaloga produkta $product je $productQuantity kosov po ceni " . $productStock[0]['price'] . ", vrednost zaloge produkta $product je $productValue.");
            $total += $productValue;
        }


        $this->info("Vrednost zaloge vseh produktov je $total.");
    }
}
