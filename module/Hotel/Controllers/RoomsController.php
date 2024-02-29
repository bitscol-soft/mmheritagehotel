<?php

namespace Module\Hotel\Controllers;

use Exception;
use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Services\BookingService;
use Illuminate\Validation\Rule;

class RoomsController extends Controller
{






    /**
     * ------------------------------------------------------------------
     * INDEX METHOD
     * ------------------------------------------------------------------
     */
    public function index()
    {
        $this->hasAccess("rooms.index");
        $room_category = RoomCategory::roomName();
        $rooms         = Rooms::get();
        return view('rooms.index', compact('room_category', 'rooms'));
    }








    /**
     * ------------------------------------------------------------------
     * CREATE METHOD
     * ------------------------------------------------------------------
     */
    public function create()
    {
        $this->hasAccess("rooms.index");

        $room_category = RoomCategory::roomName();
        $rooms         = Rooms::get();
        return view('rooms.create', compact('room_category', 'rooms'));
    }







    /**
     * ------------------------------------------------------------------
     * EDIT METHOD
     * ------------------------------------------------------------------
     */
    public function edit($id)
    {
        $this->hasAccess("rooms.edit");

        $room_category = RoomCategory::roomName();
        $rooms         = Rooms::get();
        $room          = Rooms::find($id);
        return view('rooms.edit', compact('rooms', 'room_category', 'room'));
    }










    /**
     * ------------------------------------------------------------------
     * STORE METHOD
     * ------------------------------------------------------------------
     */
    public function store(Request $request)
    {
        // return $request->all();

        $request->validate([
            'name'          => 'required',
            'room_number'   =>  [
                            'required',
                            Rule::unique('rooms')
                                ->where('room_category', $request->name)
                                ->where('room_number', $request->room_number)
            ]
        ]);

        try {

            $room_name = RoomCategory::Select('name')->where('id', $request->name)->first();
            $room = Rooms::create([
                'name'          => $room_name->name,
                'room_category' => $request->name,
                'room_number'   => $request->room_number,
                'f_r_id_card'   => $request->f_r_id_card,
                'status'        => $request->status,
                'smoking_status'=> $request->smoking_status,
                'from_date'     => $request->from_date,
                'to_date'       => $request->to_date,
                'rent'          => $request->rent ?? null,
                'beds'          => $request->beds ?? null,
                'max_guests'    => $request->max_guests ?? null,
            ]);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('rooms.index')->with('message', 'Room have been created success.');
    }












    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD
     * ------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {
        // return $request->all();

        $request->validate([
            'name'          => 'required',
            'room_number'   =>  [
                            'required',
                            // Rule::unique('rooms')->ignore($id)
                            //     ->where('room_category', $request->name)
                            //     ->where('room_number', $request->room_number)
            ]
        ]);

        try {
            DB::transaction(function() use ($request, $id){
                $room_name = RoomCategory::Select('name')->where('id', $request->name)->first();
                $data = Rooms::find($id);
                $data->update([
                    'name'          => $room_name->name,
                    'room_category' => $request->name,
                    'room_number'   => $request->room_number,
                    'f_r_id_card'   => $request->f_r_id_card,
                    'status'        => $request->status,
                    'smoking_status'=> $request->smoking_status,
                    'from_date'     => $request->from_date,
                    'to_date'       => $request->to_date,
                    'rent'          => $request->rent ?? null,
                    'beds'          => $request->beds ?? null,
                    'max_guests'    => $request->max_guests ?? null,
                ]);
                $status = $request->status == 0 ? 'Dirty' : ($request->status == 2 ? 'Maintenence' : 'Ready');
                $note = '';
                (new BookingService())->roomLog($data->id, $status, 'Room status changed to '. $status .' by @'.auth()->user()->name, $note);

            });
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('rooms.index')->with('message', 'Room have been updated.');
    }







    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD BY AJAX
     * ------------------------------------------------------------------
     */
    public function updateStatus(Request $request, $id)
    {

        try {

            $data = Rooms::find($id);

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

            $data = Rooms::find($id);

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
            $rooms = Rooms::find($id);
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
            $room = Rooms::where('id', '!=' ,$request->room_id)
                         ->where('room_category', $request->room_category)
                         ->where('room_number', $request->room_number)
                         ->first();
        } else {
            $room = Rooms::where('room_category', $request->room_category)
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
