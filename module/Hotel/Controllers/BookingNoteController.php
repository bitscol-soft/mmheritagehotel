<?php

namespace Module\Hotel\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\BookingNote;

class BookingNoteController extends Controller
{



    public function index()
    {
        $bookingNotes = BookingNote::queryLike('title')->get();

        $table       = BookingNote::getTableName();

        return view('booking-note.index', compact('bookingNotes','table'));
    }



    public function create()
    {
        return view('booking-note.create');
    }





    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
        ]);

        try {
            BookingNote::create([
                'title'      => $request->title,
                'status'     => 1,
                'created_by' => auth()->id(),
            ]);

        } catch (\Throwable $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('booking-note.index')->with('message', 'Booking note have been created success !');
    }





    public function edit($id)
    {
        $bookingNote     = BookingNote::findOrFail($id);

        return view('booking-note.edit', compact('bookingNote'));
    }






    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
        ]);

        try {
            $bookingNote = BookingNote::findOrFail($id);

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

        try {
            $bookingNote = BookingNote::findOrFail($id);

            $bookingNote->delete();

        } catch (\Throwable $e) {


            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('booking-note.index')->with('message', 'Booking note have been deleted success !');
    }




}
