<?php

namespace Module\Hotel\Controllers;

use Exception;
use App\Models\Country;
use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\BookingNote;
use Skycoder\LaravelFilesaver\Filesaver;

class BookingNoteController extends Controller
{




    public function index()
    {
        $bookingNotes = BookingNote::queryLike('name')->get();

        $table       = BookingNote::getTableName();

        return view('booking-note.index', compact('bookingNotes','table'));
    }




    public function create()
    {

        $this->hasAccess("guests.create");
        $countries = Country::pluck('name', 'id');
        return view('booking-note.create', compact('countries'));
    }










    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'required',
            // 'nid_no'     => 'nullable',
            'phone_no'   => 'required|unique:hotel_guest,phone_no',
        ]);


        $uploader = new Filesaver;

        try {

            $guest = Guest::create([
                'name'                  => $request->guest_name,
                'email'                 => $request->email,
                'phone_no'              => $request->phone_no,
                'nid_no'                => $request->nid_no,
                'country_id'            => $request->country_id,
                'gender'                => $request->gender,
                'address'               => $request->address,
                'spouse_name'           => $request->spouse_name,
                'created_by'            => Auth::user()->id,
                'passport_expiry_date'  => $request->passport_expiry_date,
            ]);

            $uploader->upload_file($request->nid_front, $guest, 'nid_front', 'hotel/guests/');
            $uploader->upload_file($request->nid_back, $guest, 'nid_back', 'hotel/guests/');
            $uploader->upload_file($request->spouse_nid_front, $guest, 'spouse_nid_front', 'hotel/guests');
            $uploader->upload_file($request->spouse_nid_back, $guest, 'spouse_nid_back', 'hotel/guests');

            if ($request->ajax()) {
                return response()->json([
                    'status'    => 1,
                    'data'      => $guest,
                    'message'   => 'Success'
                ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'    => 1,
                'data'      => $e->getMessage(),
                'message'   => 'Error'
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('booking-note.index')->with('message', 'Guest have been created successfully !');
    }








    public function edit($id)
    {
        // $this->hasAccess("guests.edit");

        $bookingNote     = BookingNote::find($id);

        return view('booking-note.edit', compact('bookingNote'));
    }














    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
        ]);

        try {
            $bookingNote = BookingNote::find($id);

            $bookingNote->update([
                'title'                  => $request->title,
            ]);


        } catch (\Throwable $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('booking-note.index')->with('message', 'Data have been updated success !');
    }











    public function destroy($id)
    {

        $this->hasAccess("guests.delete");

        try {

            $guest = Guest::find($id);

            if (file_exists($guest->nid_front)) {
                unlink($guest->nid_front);
            }


            if (file_exists($guest->nid_back)) {
                unlink($guest->nid_back);
            }

            if (file_exists($guest->spouse_nid_front)) {
                unlink($guest->spouse_nid_front);
            }

            if (file_exists($guest->spouse_nid_back)) {
                unlink($guest->spouse_nid_back);
            }

            $guest->delete();

            return redirect()->back()->with('message', 'Guest deleted Successfull');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'This Guest Information can not be deleted.');
        }
    }



    //------------------------------------------------------------------//
    //                    GET HOTEL GUEST INFO METHOD                   //
    //------------------------------------------------------------------//
    public function getHotelGuestInfo(Request $request)
    {

        return Guest::where('id', $request->id)->first();

    }




    //------------------------------------------------------------------//
    //                  UPDATE HOTEL GUEST INFO METHOD                  //
    //------------------------------------------------------------------//
    public function updateHotelGuestInfo(Request $request)
    {
        // return $request->all();
        $uploader = new Filesaver;

        $request->validate([
            'name'       => 'required',
            'phone_no'   => 'required|unique:hotel_guest,phone_no,' . $request->id,
        ]);

        try {

            $guest = Guest::find($request->id);

            $guest->update([
                'name'                  => $request->name,
                'email'                 => $request->email,
                'phone_no'              => $request->phone_no,
                'nid_no'                => $request->nid_no,
                'country_id'            => $request->country_id,
                'gender'                => $request->gender,
                'address'               => $request->address,
                'spouse_name'           => $request->spouse_name,
                'updated_by'            => Auth::user()->id,
                'passport_expiry_date'  => $request->passport_expiry_date,
            ]);

            // ------------------------------------------------------------------//
            //                UPLOAD NID,SPOUSE,PROFILE INFORMATION              //
            // ------------------------------------------------------------------//

            $uploader->upload_file($request->nid_front, $guest, 'nid_front', 'hotel/guests/');

            $uploader->upload_file($request->nid_back, $guest, 'nid_back', 'hotel/guests/');

            $uploader->upload_file($request->spouse_nid_front, $guest, 'spouse_nid_front', 'hotel/guests');

            $uploader->upload_file($request->spouse_nid_back, $guest, 'spouse_nid_back', 'hotel/guests');


            return response()->json([
                'data'      => $guest = Guest::where('id',$request->id)->select('id','name')->first(),
                'status'    => 1,
                'message'   => 'Guest Updated Successfully : )',
            ]);

        }
        catch (\Throwable $e) {
            return response()->json([
                'data'      => '',
                'status'    => 0,
                'message'   => $e->getMessage(),
            ]);
        }

    }


}
