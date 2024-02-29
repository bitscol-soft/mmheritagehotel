<?php

namespace App\Http\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use CheckPermission;





    //--------------------------------------------------------------------------
    //            DELETE DATA METHOD
    //--------------------------------------------------------------------------
    public function deleteData($model, $id, $routeName)
    {
        try {

            $item = $model::find($id);

            if ($item) {
                if (file_exists($item->image)) {
                    unlink($item->image);
                }
                $item->delete();
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->route($routeName)->with('message', 'Delete Successfull !');
    }



    /*
     |--------------------------------------------------------------------------
     | UPDATE STATUS METHOD
     |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $table)
    {
        if ($request->ajax()) {

            $request->status == 'Active' ? $status = 0 : $status = 1;
            DB::table($table)->whereId($request->item_id)->update(['status' => $status]);

            return response()->json(['status' => $status, 'item_id' => $request->item_id]);
        }
    }



}
