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




}
