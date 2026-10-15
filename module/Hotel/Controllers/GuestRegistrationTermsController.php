<?php

namespace Module\Hotel\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\GuestRegistrationTerm;

class GuestRegistrationTermsController extends Controller
{



    public function index()
    {
        $bookingNotes = GuestRegistrationTerm::queryLike('title')->get();

        return view('guest-registration-terms.index', compact('bookingNotes'));
    }





    public function edit($id)
    {
        $bookingNote     = GuestRegistrationTerm::findOrFail($id);

        return view('guest-registration-terms.edit', compact('bookingNote'));
    }






    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
        ]);

        try {
            $bookingNote = GuestRegistrationTerm::findOrFail($id);

            $bookingNote->update([
                'title'                  => $request->title,
            ]);


        } catch (\Throwable $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('guest-registration-terms.index')->with('message', 'Registration Terms have been updated success !');
    }




}
