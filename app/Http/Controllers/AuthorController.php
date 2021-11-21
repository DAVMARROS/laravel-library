<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('authors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::find($id);
        if(!$author){
            return redirect("/dashboard");
        }
        return view('authors.show', compact('author'));
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
            $author = Author::find($id);
            $author->delete();
            return response()->json(['status' => 'OK', 'message' => "Author deleted"]);
        } catch (Exception $e) {
            return response()->error("Whoops! Something went wrong.");
        }
    }
}
