<?php

namespace Module\Restaurant\Controllers\Kitchen;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Restaurant\Models\Kitchen;
use Module\Restaurant\Models\KitchenOrder;

class KitchenOrderController extends Controller
{


    public function status(Request $request, $id){

        try {

            if($request->type == 'Cooking'){


                $order = KitchenOrder::find($id);

                foreach ($order->sale->items as $key => $saleItem) {
                    foreach ($saleItem->productMaterials as $key => $material) {

                        $needToMinus = $saleItem->quantity * $material->quantity;

                        $material->stock()->decrement('purchased_quantity', $needToMinus);

                    }
                }

                KitchenOrder::find($id)->update([
                    'order_status' => 'Cooking',
                ]);


            }

            if($request->type == 'Ready'){
                KitchenOrder::find($id)->update([
                    'order_status' => 'Ready',
                ]);
            }

            if($request->type == 'Complete'){
                KitchenOrder::find($id)->update([
                    'order_status' => 'Complete',
                ]);
            }
    } catch (\Throwable $e) {

        return redirect()->back()->with('error', $e->getMessage());
    }
    return redirect()->route('kit.kitchen.create')->with('message', 'Order Update Successfully.');
 }



}
