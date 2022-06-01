<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaterCompany;
use App\Models\WaterTicket;
use DB;
use MongoDB\BSON\UTCDateTime;
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

        $request->date_range = "01/06/2021 - 01/06/2022";

        $date_range = $from_date = $to_date = '';
        /*** Create Date ***/
        // if ($request->has('date_range')){
              
            if (!empty($request->date_range)) {
                // return 'what';
                $date_range = $request->date_range;
                $date_ranges = explode(' - ', $date_range);
               
                /*** Create Date ***/
                $from_date  =  date_format(date_create_from_format('d/m/Y', $date_ranges[0]), 'Y-m-d');
                $to_date    =  date_format(date_create_from_format('d/m/Y', $date_ranges[1]), 'Y-m-d');
                
                // if((int) round((strtotime($to_date) - strtotime($from_date)) / (60 * 60 * 24)) > 6){
                    
                //     return redirect()->back()->with('error', 'Wrong Booking Date! Maximum 7 days.');
                // }
            }
            // return $from_date;
            // $from_date = "2020-01-13T16:21:00.000+00:00";
            // $to_date = "2022-01-13T16:21:00.000+00:00";
            // return $from_date;
            
        // } 
        // else {
        //     $date_range  = date('d/m/Y').' - '.date('d/m/Y');
        //     $date_ranges = explode(' - ', $date_range);
        //     /*** Create Date ***/
        //     $from_date  =  date_format(date_create_from_format('d/m/Y', $date_ranges[0]), 'Y-m-d');
        //     $to_date    =  date_format(date_create_from_format('d/m/Y', $date_ranges[1]), 'Y-m-d');
        // }
        // $MongoDt= date("Y-m-d\TH:i:s.000P", strtotime($from_date));
        // $MongoDt2= date("Y-m-d\TH:i:s.000P", strtotime($to_date));
        //  return $MongoDt;
         $dateAdded =  new \MongoDB\BSON\UTCDateTime(strtotime('2022-01-13 10:00:00') * 1000);

        $tickets  = WaterTicket::select('companyId','companyName','totalSeat','ticketType','ticketDateTime')->whereDate('ticketDateTime',$dateAdded)->get()->groupBy('companyId');
        return $tickets;
        $tickets_report_groupings = $tickets->mapWithKeys(function ($group, $key) {
           
        return [
                $key => [
                    'company' => $key, // $key is what we grouped by
                    'date_time' => $group->ticketDateTime, // $key is what we grouped by
                    'DECK' =>  $group->where('ticketType', 'DECK')->count(),
                    'STAFF' => $group->where('ticketType', 'STAFF')->count(),
                    'GOODS' => $group->where('ticketType', 'GOODS')->count(),
                ]
            ];
        });
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
