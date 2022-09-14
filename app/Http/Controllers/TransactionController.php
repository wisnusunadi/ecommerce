<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all (Request $request){
        $id= $request->input('id');
        $limit = $request->input('limit');
         $status = $request->input('status');

        if($id){
            $transaction = Transaction::with(['items.product'])->find($id);
            if($transaction)
            {
                return ResponseFormatter::success(
                    $transaction,'Data Ok'
                );
            }else{
                return ResponseFormatter::error(
                    null, 'Data Kosong',404
                );
            }
        }

        $transaction = Transaction::with(['items.product']);

        if($status){
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->get(),
            'Data Berhasil Diambil'
        );
    }

    public function checkout (Request $request){
        $request->validate([
            'items'=> 'required|array',
            'items.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPED',
        ]);
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status,
        ]);

        foreach($request->items as $product){
            TransactionItem::create([
                'user_id' => Auth::user()->id,
                'products_id' => $product['id'],
                'transactions_id' => $transaction->id,
                'quantity' => $product['quantity'],
            ]);
        }
        return ResponseFormatter::success($transaction->load('items.product'),'Transaksi Ok');
    }
}
