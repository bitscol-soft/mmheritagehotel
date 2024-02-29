<?php

namespace Module\Restaurant\Controllers\Kitchen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Kitchen;
use Module\Restaurant\Models\KitchenOrder;


class KitchenController extends Controller
{

    public function index()
    {
            $this->hasAccess("kit.kitchen.create");

            $data['orders'] = KitchenOrder::query()->with('order_items')->latest()->get();

        return view('kitchen.index',$data);
    }

    public function create()
    {
            $this->hasAccess("kit.kitchen.create");

            $data['orders'] = KitchenOrder::query()->with('order_items', 'sale.items.productMaterials.stock')->latest()->get();

        return view('kitchen.create',$data);

    }


    public function store(Request $request)
    {

         //
    }


    public function show($id)
    {
            $this->hasAccess("kit.kitchen.create");

            $data['orders'] = KitchenOrder::query()->with('order_items')->find($id);

        return view('kitchen.show',$data);
    }


    public function edit(Kitchen $kitchen)
    {
        //
    }


    public function update(Request $request, Kitchen $kitchen)
    {
        // return $request->all();
    }


    public function destroy(Kitchen $kitchen)
    {
        //
    }

    public function details(Request $request)
    {
            $this->hasAccess("kit.kitchen.create");
            $order = KitchenOrder::find(10);

        return response()->json($order);
    }
}
