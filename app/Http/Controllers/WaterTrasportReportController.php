<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterCompany;
use App\Models\WaterTicket;
use DB;
use MongoDB\BSON\UTCDateTime;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
class WaterTrasportReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return WaterCompany::all();
    }
    public function companies(Request $request)
    {
        $all_companies = WaterCompany::select('id','name','digitalTicketingCommissionBase')->get();
        $all_companies_to_array = $all_companies->toArray();
        // $searchedValue = '6242ac3aca6de73359946921';

        // $all_companies_columns =  array_column($all_companies_to_array, 'name', '_id')['6242ac3aca6de73359946921'];
        // $all_companies_columns =  array_column($all_companies_to_array, 'digitalTicketingCommissionBase', '_id')['6242ac3aca6de73359946921'];
        // return $all_companies_columns;
        // return $all_companies_columns['6242ac3aca6de73359946921'];
        $custom_range = "01/02/2022 - 01/06/2022";
        $date_range = $from_date = $to_date = '';
        request()->merge([ 'date_range' => $custom_range ]);
        /*** Create Date ***/
        if ($request->has('date_range')){
            if (!empty($request->date_range)) {
                $date_range = $request->date_range;
                $date_ranges = explode(' - ', $date_range);
                $from_date  =  date_format(date_create_from_format('d/m/Y', $date_ranges[0]), 'Y-m-d');
                $from_date =  Carbon::parse($from_date)->startOfDay();
                $to_date    =  date_format(date_create_from_format('d/m/Y', $date_ranges[1]), 'Y-m-d');
                $to_date =  Carbon::parse($to_date)->startOfDay();
                if((int) round((strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24)) > 600){
                    return redirect()->back()->with('error', 'Wrong Booking Date! Maximum 7 days.');
                }
            }
        }
        $tickets  = WaterTicket::select('companyId','companyName','totalSeat','totalAmount','ticketType','ticketDateTime','tripDateTime')->whereBetween(
            'tripDateTime', array(
                $from_date,
                $to_date
            )
        )->take(500)->get()->groupBy('companyId');
        // return $tickets;
        $tickets_report_groupings = $tickets->mapWithKeys(function ($group, $key) use ($all_companies_to_array){
            
        return [
                $key => [
                    'company' => $key, // $key is what we grouped by
                    'SEAT' =>  $group->where('ticketType', 'SEAT')->count(),
                    'DECK' =>  $group->where('ticketType', 'DECK')->count(),
                    'STAFF' => $group->where('ticketType', 'STAFF')->count(),
                    'GOODS' => $group->where('ticketType', 'GOODS')->count(),

                    'SEAT_GMV' => $group->where('ticketType', 'SEAT')->sum('totalAmount'),  //totalAmount
                    'DECK_GMV' => $group->where('ticketType', 'DECK')->sum('totalAmount'),  //totalAmount
                    'STAFF_GMV' => $group->where('ticketType', 'STAFF')->sum('totalAmount'),  //totalAmount
                    'GOODS_GMV' => $group->where('ticketType', 'GOODS')->sum('totalAmount'),  //totalAmount

                    'deckCommissionBase' => array_column($all_companies_to_array, 'name', '_id')[$key] //totalAmount
                ]
            ];
        });
        // 61f0f1a2b58d0a20fd39fe7a
        // $all_companies
        return $tickets_report_groupings;
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
        //
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
