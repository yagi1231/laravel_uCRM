<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::groupBy('id')
            ->selectRaw('id, customer_name,sum(subtotal) as total, status, created_at')
            ->paginate(50);

        return Inertia::render('Purchases/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = Item::select('id', 'name', 'price')->where('is_selling', true)->get();

        return Inertia::render('Purchases/Create', [
            'items' =>  $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {

        DB::beginTransaction();

        try {
            $purchase = Purchase::create([
                'customer_id' => $request->customer_id,
                'status' => $request->status,
            ]);

            foreach ($request->items as $item) {
                $purchase->items()->attach($purchase->id, [
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }

            DB::commit();

            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $item = Order::where('id', $purchase->id)->get();
        // 合計
        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_name, sum(subtotal) as total, status, created_at')->get();

        return Inertia::render('Purchases/Show', [
            'items' => $item, 'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $purchase = Purchase::find($purchase->id);

        $allItems = Item::select('id', 'name', 'price')->get();

        $items = [];

        foreach ($allItems as $allItems) {
            $quantity = 0;
            foreach ($purchase->items as $item) {
                if ($allItems->id === $item->id) {
                    $quantity = $item->pivot->quantity;
                }
            }
            array_push($items, [
                'id' => $allItems->id,
                'name' => $allItems->name,
                'price' => $allItems->price,
                'quantity' => $quantity,
            ]);
        }

        $order = Order::groupBy('id')
            ->where('id', $purchase->id)
            ->selectRaw('id, customer_name, customer_id, status, created_at')->get();

        return Inertia::render('Purchases/Edit', [
            'items' =>  $items,
            'order' =>  $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();

        try {
            $purchase->status = $request->status;
            $purchase->save();

            $items = [];
            foreach ($request->items as $item) {
                $items = $items + [
                    // item_id => [ 中間テーブルの列名 => 値 ]
                    $item['id'] => ['quantity' => $item['quantity']]
                ];
            }
            $purchase->items()->sync($items);

            DB::commit();

            return to_route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}