<?php

namespace Module\BanquetHall\Controllers;

use Exception;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Module\BanquetHall\Models\BanquetAmenitie;

class BanquetAmenitieController extends Controller
{
    use FileSaver;

    /*
     |--------------------------------------------------------------------------
     | DELETE METHOD FOR SHOW AMINITIES
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("aminities.types.index");
        $data = BanquetAmenitie::get();
        return view('hall.aminities.index', compact('data'));
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->hasAccess("aminities.types.index");
        return view('hall.aminities.create');
    }










    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD FOR SHOW EDIT PAGE
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("aminities.types.index");
        $aminities = BanquetAmenitie::find($id);
        return view('hall.aminities.edit',compact('aminities'));
    }










    /*
     |--------------------------------------------------------------------------
     | STORE METHOD FOR SAVE AMINITIES
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // dd('ddd');

        $request->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();

        try{

            $aminities = BanquetAmenitie::create([
                'name'            => $request->name,
                // 'created_by' => Auth::user()->id
            ]);

            $this->UploadWebp($request->aminiti_icon,$aminities,'aminities_icon', 'uploads/banquethall/website', 38, 40);

        }catch (\Throwable $e) {

            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('banquet.aminities.index')->with('message', 'Aminities Create Successfull');
    }










    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD FOR UPDATE AMINITIES
     |--------------------------------------------------------------------------
    */
    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
        ]);


        DB::beginTransaction();

        try{
            $aminities = BanquetAmenitie::find($id);

            $aminities->update([
                'name'       => $request->name,
                'status'     => $request->status,
                'updated_by' => Auth::user()->id
            ]);

            $this->UploadWebp($request->aminiti_icon,$aminities,'aminities_icon', 'uploads/banquethall/website', 38, 40);



        }catch (\Throwable $e) {

            DB::rollback();
            return $e->getMessage();
            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('banquet.aminities.index')->with('message', 'Aminities Create Successfull');
    }










    /*
     |--------------------------------------------------------------------------
     | DELETE METHOD FOR UPDATE AMINITIES
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $this->hasAccess("aminities.types.index");
        try{
            $aminities = BanquetAmenitie::find($id);
            $aminities->delete();

            return redirect()->back()->with('message', 'Aminities deleted Successfull');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }
}
