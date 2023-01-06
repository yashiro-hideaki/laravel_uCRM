<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Item;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        // $customers = Customer::select('name','kana','id')->get();
        $items = Item::select('id','name','price')
        ->where('is_selling',true)
        ->get();

        return Inertia::render('Purchases/Create',[
            // 'customers' => $customers,
            'items' => $items
        ]);
    }

    public function store(StorePurchaseRequest $request)
    {
        DB::beginTransaction();

        try{
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status,
            ]);
            foreach($request->items as $item){
                $purchase->items()->attach( $purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }
            DB::commit();
            return to_route('dashboard');
        } catch(\Exception $e){
            DB::rollback();
        }
    }

    public function show(Purchase $purchase)
    {
        //
    }

    public function edit(Purchase $purchase)
    {
        //
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        //
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
