<?php

namespace Module\BanquetHall\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Hotel\Services\BookingService;
use Module\BanquetHall\Models\BanquetRoom;
use Module\BanquetHall\Models\BanquetCategory;

class BanquetRoomController extends Controller
{


    /**
     * ------------------------------------------------------------------
     * INDEX METHOD
     * ------------------------------------------------------------------
     */
    public function index()
    {
        $this->hasAccess("rooms.index");
        $room_category = BanquetCategory::roomName();
        $rooms         = BanquetRoom::get();
        return view('hall.rooms.index', compact('room_category', 'rooms'));
    }








    /**
     * ------------------------------------------------------------------
     * CREATE METHOD
     * ------------------------------------------------------------------
     */
    public function create()
    {
        $this->hasAccess("rooms.index");

        $room_category = BanquetCategory::roomName();
        $rooms         = BanquetRoom::get();
        return view('hall.rooms.create', compact('room_category', 'rooms'));
    }







    /**
     * ------------------------------------------------------------------
     * EDIT METHOD
     * ------------------------------------------------------------------
     */
    public function edit($id)
    {
        $this->hasAccess("rooms.edit");

        $room_category = BanquetCategory::roomName();
        $rooms         = BanquetRoom::get();
        $room          = BanquetRoom::find($id);
        return view('hall.rooms.edit', compact('rooms', 'room_category', 'room'));
    }










    /**
     * ------------------------------------------------------------------
     * STORE METHOD
     * ------------------------------------------------------------------
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'          => 'required',
            'hall_number'   => 'required',
        ]);

        try {


            $room = BanquetRoom::create([
                'name'          => $request->name,
                'hall_category' => $request->hall_category,
                'room_number'   => $request->hall_number,
                'hall_sqft'     => $request->hall_sqft,
                'status'        => $request->status,
                'price'         => $request->price,
                'hall_aminities'=> $request->hall_aminities ?? null,
                'max_guests'    => $request->max_guests ?? null,
                'to_date'       => $request->to_date ?? null,
                'from_date'     => $request->from_date ?? null,
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('banquet.hall-rooms.index')->with('message', 'Room have been created success.');
    }












    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD
     * ------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name'          => 'required',
            'hall_number'   => 'required',
        ]);

        try {
            DB::transaction(function() use ($request, $id){
                $data = BanquetRoom::find($id);
                $data->update([
                    'name'          => $request->name,
                    'hall_category' => $request->hall_category,
                    'room_number'   => $request->hall_number,
                    'hall_sqft'     => $request->hall_sqft,
                    'status'        => $request->status,
                    'price'         => $request->price,
                    'hall_aminities'=> $request->hall_aminities ?? null,
                    'max_guests'    => $request->max_guests ?? null,
                    'to_date'       => $request->to_date ?? null,
                    'from_date'     => $request->from_date ?? null,
                ]);
                // $status = $request->status == 0 ? 'Dirty' : ($request->status == 2 ? 'Maintenence' : 'Ready');
                // $note = '';
                // (new BookingService())->roomLog($data->id, $status, 'Room status changed to '. $status .' by @'.auth()->user()->name, $note);

            });
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('banquet.hall-rooms.index')->with('message', 'Hall have been updated.');
    }







    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD BY AJAX
     * ------------------------------------------------------------------
     */
    public function updateStatus(Request $request, $id)
    {

        try {

            $data = BanquetRoom::find($id);

            $data->update([
                'status'        => $request->status,
                'from_date'     => $request->from_date,
                'to_date'       => $request->to_date,
            ]);

            $status = $request->status == 0 ? 'Dirty' : ($request->status == 2 ? 'Maintenence' : 'Ready');

            $remark = $request->remarks ?? 'Room status changed to '. $status . 'by @ '. auth()->user()->name;

            (new BookingService())->roomLog($id, $status, $remark);

        } catch (\Throwable $e) {

            return response()->json([
                'status'    => 0,
                'data'      => $e->getMessage(),
                'message'   => 'Server Error',
            ]);
        }

        return response()->json([
            'status'    => 1,
            'data'      => 'Room Status update success.',
            'message'   => 'Success',
        ]);;
    }


        /**
     * ------------------------------------------------------------------
     * UPDATE METHOD BY AJAX
     * ------------------------------------------------------------------
     */
    public function updateKeepingStatus(Request $request, $id)
    {

        try {

            $data = BanquetRoom::find($id);

            $data->update([
                'status'        => $request->status,
                'from_date'     => $request->from_date,
                'to_date'       => $request->to_date,
            ]);

            $status = $request->status == 0 ? 'Dirty' : ($request->status == 2 ? 'Maintenence' : 'Ready');

            $remark = $request->remarks ?? 'Room status changed to '. $status . 'by @ '. auth()->user()->name;

            (new BookingService())->roomLog($id, $status, $remark);

        } catch (\Throwable $e) {

            return response()->json([
                'status'    => 0,
                'data'      => $e->getMessage(),
                'message'   => 'Server Error',
            ]);
        }

        return response()->json([
            'status'    => 1,
            'data'      => 'Room Status update success.',
            'message'   => 'Success',
        ]);;
    }




    /**
     * ------------------------------------------------------------------
     * DESTROY METHOD
     * ------------------------------------------------------------------
     */
    public function destroy($id)
    {
        $this->hasAccess("rooms.delete");

        try {
            $rooms = BanquetRoom::find($id);
            $rooms->delete();

            return redirect()->back()->with('message', 'Room have been deleted Success.');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }



    //------------------------------------------------------------------//
    //                    CHECK ROOM NUMBER METHOD                      //
    //------------------------------------------------------------------//
    public function checkRoomNumber(Request $request)
    {
        if ($request->room_id != null) {
            $room = BanquetRoom::where('id', '!=' ,$request->room_id)
                         ->where('room_category', $request->room_category)
                         ->where('room_number', $request->room_number)
                         ->first();
        } else {
            $room = BanquetRoom::where('room_category', $request->room_category)
                         ->where('room_number', $request->room_number)
                         ->first();
        }



        if ($room == null) {
            return 0;
        } else {
            return 1;
        }

        // return response()->json([
        //     'message'   => "Error",
        //     'status'    => 0,
        // ]);
    }
}
