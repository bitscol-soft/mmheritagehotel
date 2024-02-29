<?php

namespace Module\Hotel\Controllers;

use Exception;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use App\Traits\SendNotification;
use Module\CRM\Models\CRMCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Module\Hotel\Models\ImageStoreGuest;
use Skycoder\LaravelFilesaver\Filesaver;
use Module\Hotel\Models\GuestRegistrationTerm;


class GuestController extends Controller
{

    use SendNotification;


    public function index()
    {
        $this->hasAccess("guests.index");

        $guests = Guest::searchByField('nid_no')->searchByField('phone_no')->queryLike('name')->latest()->paginate(25);

        return view('guests.index', compact('guests'));
    }




    public function create()
    {

        $this->hasAccess("guests.create");
        $countries = Country::pluck('name', 'id');

        $companies = CRMCustomer::where('org_name','!=', null)
                                // ->where('is_customer', 1)
                                ->select('id','org_name','org_phone','org_email','address')
                                ->get();
        // $companies = Company::select('id','name','group_id')->get();

        return view('guests.create', compact('countries','companies'));
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
            if($request->company_name){

                $company = $request->company_name;

                $guest = Guest::create([
                    'name'                  => $request->guest_name,
                    'email'                 => $request->email,
                    'phone_no'              => $request->phone_no,
                    'nid_no'                => $request->nid_no,
                    'country_id'            => $request->country_id,
                    'city_id'               => $request->city_id,
                    'company_id'            => $this->company($company),
                    'gender'                => $request->gender,
                    'profession'            => $request->profession,
                    'age'                   => $request->age,
                    'father_name'           => $request->father_name,
                    'address'               => $request->address,
                    'reference'             => $request->reference,
                    'spouse_name'           => $request->spouse_name,
                    'is_stuff'              => $request->is_stuff ?? 0,
                    'created_by'            => Auth::user()->id,
                    'passport_expiry_date'  => $request->passport_expiry_date,
                ]);
            }else{

                $guest = Guest::create([
                    'name'                  => $request->guest_name,
                    'email'                 => $request->email,
                    'phone_no'              => $request->phone_no,
                    'nid_no'                => $request->nid_no,
                    'country_id'            => $request->country_id,
                    'city_id'               => $request->city_id,
                    'company_id'            => $request->company_id,
                    'gender'                => $request->gender,
                    'profession'            => $request->profession,
                    'age'                   => $request->age,
                    'father_name'           => $request->father_name,
                    'address'               => $request->address,
                    'reference'             => $request->reference,
                    'spouse_name'           => $request->spouse_name,
                    'is_stuff'              => $request->is_stuff ?? 0,
                    'created_by'            => Auth::user()->id,
                    'passport_expiry_date'  => $request->passport_expiry_date,
                ]);
            }
            $uploader->upload_file($request->nid_front, $guest, 'nid_front', 'hotel/guests/');
            $uploader->upload_file($request->nid_back, $guest, 'nid_back', 'hotel/guests/');
            $uploader->upload_file($request->spouse_nid_front, $guest, 'spouse_nid_front', 'hotel/guests');
            $uploader->upload_file($request->spouse_nid_back, $guest, 'spouse_nid_back', 'hotel/guests');

            if($request->web_cam == 1){
                $image = $request->image;
                $file = $guest->id . '-' . time() . '-' . rand(11111, 999999) . '.jpg';
                $directory   = './assets/uploads/hotel/guests' . '/' . date('Y') . '/';
                $new_file = $directory . $file;


                $image_parts = explode(";base64,", $image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);

                Storage::disk('base64')->put($new_file, $image_base64);

                $guest->update(['image' => $new_file]);

            }else{
                $uploader->upload_file($request->image, $guest, 'image', 'hotel/guests');
            }


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
            // return redirect()->back()->with('error', $e->getMessage());
        }

        // return redirect()->route('guests.index')->with('message', 'Guest have been created successfully !');
        return redirect()->route('guests.invoice',$guest->id);

    }








    public function edit($id)
    {
        $this->hasAccess("guests.edit");

        $countries  = Country::pluck('name', 'id');
        $guests     = Guest::find($id);

        $companies = CRMCustomer::where('org_name','!=', null)
                                // ->where('is_customer', 1)
                                ->select('id','org_name','org_phone','org_email','address')
                                ->get();

        return view('guests.edit', compact('countries', 'guests', 'companies'));
    }














    public function update(Request $request, $id)
    {
        // return $request->all();
        $uploader = new Filesaver;

        $request->validate([
            'guest_name' => 'required',
            // 'nid_no'     => 'required',
            'phone_no'   => 'required|unique:hotel_guest,phone_no,' . $id,
        ]);

        try {
            $guest = Guest::find($id);

            $guest->update([
                'name'                  => $request->guest_name,
                'email'                 => $request->email,
                'phone_no'              => $request->phone_no,
                'nid_no'                => $request->nid_no,
                'country_id'            => $request->country_id,
                'company_id'            => $request->company_id,
                'city_id'               => $request->city_id,
                'gender'                => $request->gender,
                'profession'            => $request->profession,
                'age'                   => $request->age,
                'father_name'           => $request->father_name,
                'address'               => $request->address,
                'reference'             => $request->reference,
                'spouse_name'           => $request->spouse_name,
                'updated_by'            => Auth::user()->id,
                'passport_expiry_date'  => $request->passport_expiry_date,
            ]);



            /**
             * ------------------------------------------------------------------
             * UPLOAD NID,SPOUSE,PROFILE INFORMATION
             * ------------------------------------------------------------------
             */

            $uploader->upload_file($request->nid_front, $guest, 'nid_front', 'hotel/guests/');

            $uploader->upload_file($request->nid_back, $guest, 'nid_back', 'hotel/guests/');

            $uploader->upload_file($request->spouse_nid_front, $guest, 'spouse_nid_front', 'hotel/guests');

            $uploader->upload_file($request->spouse_nid_back, $guest, 'spouse_nid_back', 'hotel/guests');

            if($request->web_cam == 1){
                $image = $request->image;
                $file = $guest->id . '-' . time() . '-' . rand(11111, 999999) . '.jpg';
                $directory   = './assets/uploads/hotel/guests' . '/' . date('Y') . '/';
                $new_file = $directory . $file;

                $image_parts = explode(";base64,", $image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);

                Storage::disk('base64')->put($new_file, $image_base64);

                $guest->update(['image' => $new_file]);

            }else{
                $uploader->upload_file($request->image, $guest, 'image', 'hotel/guests');
            }
            if($request->is_ajax){
                return response()->json([
                    'data'  => $guest,
                    'status'    => 200
                ]);
            }


        } catch (\Throwable $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('guests.index')->with('message', 'Guest profule have been updated success !');
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

    //------------------------------------------------------------------//
    //                          GET SMS BALANCE                         //
    //------------------------------------------------------------------//
    public function getSMSBalance(){
        try {
            $apiUrl = env('SMS_BALANCE_GET');

            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                return $data;
            }
        } catch (\Throwable $th) {
            $errorMessage = 'Failed to fetch data.';
            return $errorMessage;
        }
    }

    //------------------------------------------------------------------//
    //                          SEND SMS METHOD                         //
    //------------------------------------------------------------------//
    public function sendSms(Request $request)
    {

        $data['smsbal'] = $this->getSMSBalance()['Balance'] ?? 0;

        $data['phones'] = '';

        if (isset($request->isFromGuestList)) {

            if (!isset($request->guest_id)) {
                return redirect()->back()->with('error', 'Select Guest to send SMS!');
            }

            if ($request->isAllSelected == 1) {
                $phones = Guest::where('status', 1)->pluck('phone_no');
            }
            else{

                $phones = Guest::whereIn('id', $request->guest_id)->pluck('phone_no');
            }

            $val    = '';
            $i      = 0;
            $len    = count($phones);

            foreach ($phones as $key => $phone) {
                if ($i == $len - 1) {
                    $val    .= $phone;
                }
                else{
                    $val    .= $phone .', ';
                }
                $i++;
            }

            $data['phones'] = $val;

        }

        return view('guests.sms.index', $data);

    }





    //------------------------------------------------------------------//
    //                          SUBMIT SMS METHOD                       //
    //------------------------------------------------------------------//
    public function submitSms(Request $request)
    {

        if ($request->phone_no == null || $request->message == null) {
            return redirect()->back()->with('error', 'Type Phone Number & Messages to send SMS!');
        }

        $phone_no = str_replace(", ",",", $request->phone_no);

        $this->sendMultipleSmsToGuest($request->message , $phone_no);


        if (isset($request->isFromGuestList)) {
            return redirect()->route('guests.index')->with('message', 'SMS Send Successfully :)');
        }
        return redirect()->back()->with('message', 'SMS Send Successfully :)');

    }





    public function getCustomerInfo(Request $request){

        try {
            $guest = Guest::where('id', $request->customer_id)->select('id','company_id','reference')->first();

            $data['customer'] = $guest;

            return response()->json([
                'data'      => $data,
                'status'    => 1,
                'message'   => 'Data Found',
            ]);


        } catch (\Throwable $th) {
            return response()->json([
                'data'      => '',
                'status'    => 0,
                'message'   => $th,
            ]);
        }
    }


    public function getGuestInfo(Request $request){

        try {
            $data['customer'] = Guest::find($request->customer_id);

            return response()->json([
                'data'      => $data,
                'status'    => 1,
                'message'   => 'Data Found',
            ]);


        } catch (\Throwable $th) {
            return response()->json([
                'data'      => '',
                'status'    => 0,
                'message'   => $th,
            ]);
        }
    }



    public function guestInfoInvoice($customer_id){

        try {

            $guest          = Guest::where('id', $customer_id)->first();
            $guestRegTerms  = GuestRegistrationTerm::get();
            $company        = Company::first();

            return view('guests.invoice', compact('guest', 'company', 'guestRegTerms'));

        } catch (\Throwable $th) {
            return back()->with('message', $th);
        }
    }





    public function guestImageUpdate(Request $request){

        $uploader = new Filesaver;


        $guest = Guest::find($request->customer_id);


        try{
            if($request->web_cam == 1){
                $image = $request->image;
                $file = $guest->id . '-' . time() . '-' . rand(11111, 999999) . '.jpg';
                $directory   = './assets/uploads/hotel/guests' . '/' . date('Y') . '/';
                $new_file = $directory . $file;

                $image_parts = explode(";base64,", $image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];

                $image_base64 = base64_decode($image_parts[1]);

                Storage::disk('base64')->put($new_file, $image_base64);

                $guest->update(['image' => $new_file]);
                $guestImage = ImageStoreGuest::create([
                    "guest_id"           =>$request->customer_id,
                    "booking_id"         =>$request->booking_id,
                    "booking_number"     =>$request->booking_number,
                    "image"              =>$new_file,
                ]);

            }else{


                $image = $request->image;
                $file = $guest->id . '-' . time() . '-' . rand(11111, 999999) . '.jpg';
                $directory   = './assets/uploads/hotel/guests' . '/' . date('Y') . '/';
                $new_file = $directory . $file;
                $guest->update(['image' => $new_file]);
                $guestImage = ImageStoreGuest::create([
                            "guest_id"           =>$request->customer_id,
                            "booking_id"         =>$request->booking_id,
                            "booking_number"     =>$request->booking_number,
                ]);
                $uploader->upload_file($image, $guestImage, 'image', 'hotel/guests');

            }

            return redirect()->back()->with('success', 'Guest Image Update Success');

        }
        catch(\Throwable $th){
            return redirect()->back()->with('error', $th->getMessage());
        }


    }




    /*
     |--------------------------------------------------------------------------
     | COMPANY
     |--------------------------------------------------------------------------
    */
    private function company($name)
    {
        return CRMCustomer::firstOrCreate([
            'org_name'       => $name,
        ])->id;
    }




}
// $image = $request->image;
// $file = $guest->id . '-' . time() . '-' . rand(11111, 999999) . '.jpg';
// $directory   = './assets/uploads/hotel/guests' . '/' . date('Y') . '/';
// $new_file = $directory . $file;
