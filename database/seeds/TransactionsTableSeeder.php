<?php

use App\Product;
use App\Stock;
use App\Transaction;
use App\TransactionProduct;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $transactions = [
        	[
        		"approved" => true,
				"branch_id" => 1,
        	],
        	[
        		"approved" => false,
				"branch_id" => 1,
        	],
        	[
        		"approved" => false,
				"branch_id" => 1,
        	],
        	[
        		"approved" => true,
				"branch_id" => 2,
        	],
        	[
        		"approved" => false,
				"branch_id" => 2,
        	],
        	[
        		"approved" => false,
				"branch_id" => 2,
        	],

        ];

        $products = [
            [
                "transaction_id" => 1,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 1,
                "product_id" => 9,
                "qty" => 10,
            ],
            //
            [
                "transaction_id" => 2,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 2,
                "product_id" => 9,
                "qty" => 10,
            ],
            ////
            /// 
            [
                "transaction_id" => 3,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 3,
                "product_id" => 9,
                "qty" => 10,
            ],
            //
            [
                "transaction_id" => 4,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 4,
                "product_id" => 9,
                "qty" => 10,
            ], 
            ////
            /// 
            [
                "transaction_id" => 5,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 5,
                "product_id" => 9,
                "qty" => 10,
            ],
            //
            [
                "transaction_id" => 6,
                "product_id" => 1,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 2,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 3,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 5,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 7,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 4,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 8,
                "qty" => 10,
            ],
            [
                "transaction_id" => 6,
                "product_id" => 9,
                "qty" => 10,
            ], 
        ];

        foreach($transactions as $transaction){
            Transaction::create([
                "approved" => $transaction['approved'],
                "branch_id" => $transaction['branch_id'],
            ]);
        }
        $qtys =[];
        foreach ($products as $product) {
            $qty = Stock::where('product_id' , $product['product_id'])->orderBy('created_at' , 'DESC')->first();
            array_push($qtys , $qty->qty);
        }
        for ($i=0; $i < count($products); $i++) { 
            Stock::create([
                "product_id" => $products[$i]['product_id'],
                "qty" =>  $qtys[$i] - 10,
            ]);
        }

        for ($i=0; $i < count($products); $i++) { 
            $product = $products[$i];
            $transaction = Transaction::find($product['transaction_id']);
           
            Stock::create([
                "product_id" => $product['product_id'],
                "branch_id" => $transaction->branch_id,
                "qty" =>  10,
            ]);
            TransactionProduct::create([
                "product_id" => $product['product_id'],
                "transaction_id" => $product['transaction_id'],
                "qty" => 10,
            ]);
        }
    }
}
