<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
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
        return response()->json(Book::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $book = new Book();
       $book->name = $request->name;
       $book->author = $request->author;
       $book->check_date = Now();
       $book->save();
       return response()->json($book);

    }
    public function searchByDate(Request $request){
        // $book = Book::whereYear('created_at', '2022')->get();
        // $book = Book::where('created_at', '>','2022-06-01')->get();
        $start_date = '2022-06-02';
        $parsed_start_date = Carbon::parse($start_date)->startOfDay();


        $end_date = '2022-06-02';
        $parsed_end_date = Carbon::parse($end_date)->endOfDay();



        // return $parsed_start_date;
        $dt = Carbon::now()->startOfDay();
        // return $dt;
        // $book = Book::where('created_at', '>', $parsed_end_date)->get();
        $book = Book::whereBetween(
            'check_date', array(
                $parsed_start_date,
                $parsed_end_date
            )
        )->get();
        return response()->json($book);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
