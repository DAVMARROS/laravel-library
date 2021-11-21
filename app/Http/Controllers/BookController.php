<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Status;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('books.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $book = Book::find($id);
            $book->delete();
            return response()->json(['status' => 'OK', 'message' => "Book deleted"]);
        } catch (Exception $e) {
            return response()->error("Whoops! Something went wrong.");
        }
    }

    /**
     * Request a book borrowing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function request(Request $request)
    {
        try{
            $book = Book::find($request->id);
            $user = request()->user();
            if(!$book){
                return response()->error("Book not found.");
            }
            $borrows = $user->books()->whereIn('status', [Status::REQUESTED, Status::BORROWED, Status::EXPIRED])->count();
            if($borrows >= 3){
                return response()->json(['status' => 'error', 'message' => "You can't borrow a book. You exced the limit."]);
            }

            $user->books()->attach($book->id, ['expired_at' => Carbon::now()->addDays(3), 'status'=> Status::REQUESTED]);
            $book->available = 0;
            $book->save();
            return response()->json(['status' => 'OK', 'message' => "Book borrowed"]);
        } catch (Exception $e) {
            return response()->error("Whoops! Something went wrong.");
        }
    }
}
