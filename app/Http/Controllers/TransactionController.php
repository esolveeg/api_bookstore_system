<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Transaction;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    
    public function getTransaction($id){
        $products = DB::select('CALL getTransactionProducts(?)' , [$id]);
        $transaction = Transaction::find($id);
        
        return response()->json(['transaction' => $transaction , 'products' => $products]);
    }
    public function editTransaction(Request $request , $id){
        $transaction = Transaction::find($id);
        $products = DB::select('CALL getTransactionProducts(?)' , [$id]);
        $from_branch = isset($request->from_branch) ? $request->from_branch : '';
        $branch = $request->branch;
        $old_from_branch = isset($transaction->from_branch) ? $transaction->from_branch : '';
        $old_branch = $transaction->branch_id;
        foreach ($products as $product) {
            $product_id = $product->id;
            $realQty = Stock::where('product_id' , $product_id)->where('branch_id' , $old_from_branch)->orderBy('created_at' , 'DESC')->first();
            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty + $product->qty ;
            
            $toRealQty = Stock::where('product_id' , $product_id)->where('branch_id' , $old_branch)->orderBy('created_at' , 'DESC')->first();
            $toRealQty = isset($toRealQty->qty) ? $toRealQty->qty : 0;
            $toQty = $toRealQty - $product->qty ;
            $stock = [
                'product_id' => $product_id,
                'qty' => $qty,
            ];
            $toStock = [
                'product_id' => $product_id,
                'qty' => $toQty,
            ];
            if($branch !== ''){
                $toStock['branch_id'] = $old_branch;
            }
            if($from_branch !== ''){
                $stock['branch_id'] = $old_from_branch;
            }
           
            Stock::create($stock);
            Stock::create($toStock);
            
            
        }
        sleep(2);
             DB::delete('DELETE FROM transaction_product WHERE transaction_id = ? ' , [$id]);
        
        
            foreach ($request->products as $product) {
                $product_id = $product['id'];
                $realQty = Stock::where('product_id' , $product_id)->where('branch_id' , $from_branch)->orderBy('created_at' , 'DESC')->first();
                $realQty = isset($realQty->qty) ? $realQty->qty : 0;
                $qty = $realQty - $product['qty'] ;
                $toRealQty = Stock::where('product_id' , $product_id)->where('branch_id' , $branch)->orderBy('created_at' , 'DESC')->first();
                $toRealQty = isset($toRealQty->qty) ? $toRealQty->qty : 0;
                $toQty = $toRealQty + $product['qty'] ;
                $stock = [
                    'product_id' => $product_id,
                    'qty' => $qty,
                ];
                $toStock = [
                    'product_id' => $product_id,
                    'qty' => $toQty,
                ];
                if($branch !== ''){
                    $toStock['branch_id'] = $branch;
                }
                if($from_branch !== ''){
                    $stock['branch_id'] = $from_branch;
                }
                
               
                Stock::create($stock);
                Stock::create($toStock);
                $transactionProduct = [
                        'product_id' => $product_id,
                        'qty' => $product['qty'],
                        'transaction_id' => $transaction->id
                    ];
                \App\TransactionProduct::create($transactionProduct);
            }
        
       $transaction->branch_id = $branch;
       $transaction->from_branch = $from_branch;
       $transaction->approved = $request->approved;
       $transaction->save();
       return $transaction;
        
    }
    public function createTransaction(Request $request){
        $from_branch = isset($request->from_branch) ? $request->from_branch : '';
        
        $branch = $request->branch;
     
        $transaction = \App\Transaction::create([
                'branch_id' => $branch,
                'from_branch' => $from_branch
            ]);
        foreach ($request->products as $product) {
            $product_id = $product['id'];
            $realQty = Stock::where('product_id' , $product_id)->where('branch_id' , $from_branch)->orderBy('created_at' , 'DESC')->first();
            $realQty = isset($realQty->qty) ? $realQty->qty : 0;
            $qty = $realQty - $product['qty'] ;
            $toRealQty = Stock::where('product_id' , $product_id)->where('branch_id' , $branch)->orderBy('created_at' , 'DESC')->first();
            $toRealQty = isset($toRealQty->qty) ? $toRealQty->qty : 0;
            $toQty = $toRealQty + $product['qty'] ;
            $stock = [
                'product_id' => $product_id,
                'qty' => $qty,
            ];
            $toStock = [
                'product_id' => $product_id,
                'qty' => $toQty,
            ];
            if($branch !== ''){
                $toStock['branch_id'] = $branch;
            }
            if($from_branch !== ''){
                $stock['branch_id'] = $from_branch;
            }
           
            Stock::create($stock);
            Stock::create($toStock);
            $transactionProduct = [
                    'product_id' => $product_id,
                    'qty' => $product['qty'],
                    'transaction_id' => $transaction->id
                ];
            \App\TransactionProduct::create($transactionProduct);
        }
        return $transaction;
    }
}
