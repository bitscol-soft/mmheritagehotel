<?php

namespace Module\Hotel\Controllers;

use Exception;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Module\Hotel\Models\Aminities;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AminitiesController extends Controller
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
        $data = Aminities::get();
        return view('aminities.index', compact('data'));
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->hasAccess("aminities.types.index");
        return view('aminities.create');
    }










    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD FOR SHOW EDIT PAGE
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("aminities.types.index");
        $aminities = Aminities::find($id);
        return view('aminities.edit',compact('aminities'));
    }










    /*
     |--------------------------------------------------------------------------
     | STORE METHOD FOR SAVE AMINITIES
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        DB::beginTransaction();

        try{

            $aminities = Aminities::create([
                'name'            => $request->name,
                'created_by' => Auth::user()->id
            ]);

            $this->UploadWebp($request->aminiti_icon,$aminities,'aminities_icon', 'uploads/hotel/website', 38, 40);

        }catch (\Throwable $e) {

            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('aminities.index')->with('message', 'Aminities Create Successfull');
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
            $aminities = Aminities::find($id);

            $aminities->update([
                'name'       => $request->name,
                'status'     => $request->status,
                'updated_by' => Auth::user()->id
            ]);

            $this->UploadWebp($request->aminiti_icon,$aminities,'aminities_icon', 'uploads/hotel/website', 38, 40);



        }catch (\Throwable $e) {

            DB::rollback();
            return $e->getMessage();
            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('aminities.index')->with('message', 'Aminities Create Successfull');
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
            $aminities = Aminities::find($id);
            $aminities->delete();

            return redirect()->back()->with('message', 'Aminities deleted Successfull');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }
}
