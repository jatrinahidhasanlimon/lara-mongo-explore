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
        $from_date =  Carbon::parse('2022-05-06')->startOfDay();
        $to_date =  Carbon::parse('2022-06-07')->endOfDay();
        // return $from_date;
        // select('id','name','seatCommissionBase','createdAt')->
        $all_companies = WaterCompany::select('id','name','seatCommissionBase','deckCommissionBase','goodsCommissionBase','seatCommissionRate','deckCommissionRate','goodsCommissionRate','createdAt')->get();
        // return $all_companies;
        $all_companies_to_array = $all_companies->toArray();
        // $searchedValue = '6242ac3aca6de73359946921';

        // $all_companies_columns =  array_column($all_companies_to_array, 'name', '_id')['6242ac3aca6de73359946921'];
        // $all_companies_columns =  array_column($all_companies_to_array, 'seatCommissionBase', '_id')['6242ac3aca6de73359946921'];
        // return $all_companies_columns;
        // return $all_companies_columns['6242ac3aca6de73359946921'];
        $custom_range = "01/05/2022 - 01/06/2022";
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
                $to_date =  Carbon::parse($to_date)->endOfDay();
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
        )->get()->groupBy('companyId');
        $tickets_report_groupings = $tickets->mapWithKeys(function ($group, $key) use ($all_companies_to_array){
            $seat_total_ticket = $group->where('ticketType', 'SEAT')->sum('totalSeat');
            $deck_total_ticket = $group->where('ticketType', 'DECK')->sum('totalSeat');
            $goods_total_ticket = $group->where('ticketType', 'DECK')->sum('totalSeat');
           
            $seat_amount = $group->where('ticketType', 'SEAT')->sum('totalAmount');  //totalAmount
            $deck_amount = $group->where('ticketType', 'DECK')->sum('totalAmount');  //totalAmount
            $goods_amount = $group->where('ticketType', 'GOODS')->sum('totalAmount');  //totalAmount

            $found_company_key = array_search($key, array_column($all_companies_to_array, '_id')); 
            $company_details = $all_companies_to_array[$found_company_key];
            // dd ( $company_details['seatCommissionBase'] );

            $seat_commision_base = $company_details['seatCommissionBase'];
            $deck_commision_base = $company_details['deckCommissionBase'];
            $goods_commision_base = $company_details['goodsCommissionBase'];   
            $seat_commision_rate = $company_details['seatCommissionRate'];


            $deck_commision_rate = $company_details['deckCommissionRate'];
            $goods_commision_rate = $company_details['goodsCommissionRate'];
            
            $seat_toal_commission = $deck_toal_commission = $goods_toal_commission = 0;
            
            if($seat_commision_base == 'NO_OF_TICKETS_PERCENTAGE' ){
                $seat_toal_commission = ($seat_total_ticket * $seat_commision_rate) / 100;
            } else if($seat_commision_base == 'NO_OF_TICKETS_AMOUNT' ){
                $seat_toal_commission = $seat_total_ticket * $seat_commision_rate;
            } else if($seat_commision_base == 'GMV_PERCENTAGE' ){
                $seat_toal_commission = $seat_amount * $seat_commision_rate / 100;
            }

            if($deck_commision_base == 'NO_OF_TICKETS_PERCENTAGE' ){
                $deck_toal_commission = ($deck_total_ticket * $deck_commision_rate) / 100;
            } else if($deck_commision_base == 'NO_OF_TICKETS_AMOUNT' ){
                $deck_toal_commission = $deck_total_ticket * $deck_commision_rate;
            } else if($deck_commision_base == 'GMV_PERCENTAGE' ){
                $deck_toal_commission = $deck_amount * $deck_commision_rate / 100;
            }
            
            if($goods_commision_base == 'NO_OF_TICKETS_PERCENTAGE' ){
                $goods_toal_commission = ($goods_total_ticket * $goods_commision_rate) / 100;
            } else if($goods_commision_base == 'NO_OF_TICKETS_AMOUNT' ){
                $goods_toal_commission = $goods_total_ticket * $goods_commision_rate;
            } else if($goods_commision_base == 'GMV_PERCENTAGE' ){
                $goods_toal_commission = $goods_amount * $goods_commision_rate / 100;
            }


        return [
                $key.'-'.array_column($all_companies_to_array, 'name', '_id')[$key] => [
                    'company' => $key, // $key is what we grouped by
                    
                    'seat_total_ticket' => $seat_total_ticket,
                    'deck_total_ticket' => $deck_total_ticket,
                    'goods_total_ticket' => $goods_total_ticket,

                    'seat_amount' => $seat_amount,
                    'deck_amount' => $deck_amount,
                    'goods_amount' => $goods_amount,

                    'seat_commision_base' => $seat_commision_base,
                    'deck_commision_base' => $deck_commision_base,
                    'goods_commision_base' => $goods_commision_base,

                    'seat_toal_commission' => $seat_toal_commission,
                    'deck_toal_commission' => $deck_toal_commission,
                    'goods_toal_commission' => $goods_toal_commission,
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
