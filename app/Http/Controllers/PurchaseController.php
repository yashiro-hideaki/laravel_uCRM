<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Customer;
use App\Models\Item;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function index()
    {
        // dd(Order::paginate(50));
        $orders = Order::groupBy('id')
        ->selectRaw('id,sum(subtotal) as total,customer_name,status,created_at')
        ->paginate(50);
        // dd($orders);
        return Inertia::render('Purchases/Index',[
            'orders' => $orders
        ]);
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
        //小計
        $items = Order::where('id',$purchase->id)->get();
        //合計
        $order = Order::groupBy('id')
        //1件だけ取得
        ->where('id' ,$purchase->id)
        ->selectRaw('id,sum(subtotal) as total,customer_name,status,created_at')
        ->get();

        // dd($items,$order);
        return Inertia::render('Purchases/Show',[
            'items' => $items,
            'order' => $order
        ]);
    }

    public function edit(Purchase $purchase)
    {
        $purchase = Purchase::find($purchase->id);
        $allItems = Item::select('id','name','price')->get();
        $items = [];
        foreach($allItems as $allItem){
            $quantity = 0;
            foreach($purchase->items as $item){
                if($allItem->id === $item->id ){
                    $quantity = $item->pivot->quantity;
                }
            }
            array_push($items,[
                'id' => $allItem->id,
                'name' => $allItem->name,
                'price' => $allItem->price,
                'quantity' => $quantity
            ]);
        }
        // dd($items);
        $order = Order::groupBy('id')
        //1件だけ取得
        ->where('id' ,$purchase->id)
        ->selectRaw('id,customer_id,customer_name,status,created_at')
        ->get();

        return Inertia::render('Purchases/Edit',[
            'items' => $items,
            'order' => $order
        ]);
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();

        try{
        // dd($request,$purchase);
        //中間テーブルの情報を更新するにはsync()が便利 //引数に配列が必要なので事前に作成しておく
        $purchase->status = $request->status;
        $purchase->save();
        $items = [];
        foreach($request->items as $item)
        {
        $items = $items + [
        // item_id => [ 中間テーブルの列名 => 値 ]
        $item['id'] => [ 'quantity' => $item['quantity']]
        ];
        }
        $purchase->items()->sync($items);
        DB::commit();
        return to_route('dashboard');
        }catch(\Exception $e){
            DB::rollback();
        }
    }

    public function destroy(Purchase $purchase)
    {
        //
    }
}
