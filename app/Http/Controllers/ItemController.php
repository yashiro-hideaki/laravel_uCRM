<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreitemRequest;
use App\Http\Requests\UpdateitemRequest;
use App\Models\item;
use Inertia\Inertia;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $items = Item::select('id','name','is_selling','price')->get();
        return Inertia::render('Items/Index',[
            // 'items' => $items
            'items' => Item::select('id','name','is_selling','price')->get()
        ]);
    }
    public function create()
    {
        return Inertia::render('Items/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreitemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreitemRequest $request)
    {
        Item::create([
            'name' => $request->name,
            'memo' => $request->memo,
            'price' => $request->price
        ]);
        return to_route('items.index')
        ->with([
            'message' => '登録しました',
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    //引数がitemであるため、すべての情報が入ってくる
    public function show(item $item)
    {
        return Inertia::render('Items/Show',[
            'item' => $item
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(item $item)
    {
        return Inertia::render('Items/Edit',[
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateitemRequest  $request
     * @param  \App\Models\item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateitemRequest $request, item $item)
    {
        // dd($item->name,$request->name);
        $item->name = $request->name;
        $item->memo = $request->memo;
        $item->price = $request->price;
        $item->is_selling = $request->is_selling;
        $item->save();
        return to_route('items.index')
        ->with([
            'message' => '更新しました',
            'status' => 'success'
        ]);
    }
    public function destroy(item $item)
    {
        $item->delete();

        return to_route('items.index')
        ->with([
            'message' => '削除しました',
            'status' => 'danger'
        ]);
    }
}
