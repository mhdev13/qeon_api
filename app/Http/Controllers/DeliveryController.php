<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;


class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Delivery = DB::table('ktv_tc_supplychain_delivery AS ktsd')
        ->select('ktsd.DeliveryID',
        'ktsd.DeliveryNumber',
        'ktsd.ExternalCode',
        'ktsd.DeliveryDate',
        'ktsd.TotalWeight AS DestWeight',
        'ktsd.SupplyDestOrgID',
        'ktsd.SupplyDestMillOrgID',
        DB::raw('IF(ktsd.SupplyDestMillOtherName IS NULL or ktsd.SupplyDestMillOtherName = "", "",ktsd.SupplyDestMillOtherName) AS SupplyDestMillOtherName'),
        'ktsd.SupplyDestMillOtherName',
        'ktsd.SupplyDestType',
        'ktsd.SupplyDestProcessType',
        'ktsd.SupplyBatchCategory',
        'ktsd.Notes',
        'ktsd.ChangeLog',
        'ktsd.ChangeBy',
        'ktsd.AutoBatchNumber AS DeliveryAutoNumber',
        'ktsd.DestPo AS DestPo',
        'ktsd.PackageNumber AS DestNumberPackage',
        'ktsd.Weather',
        'ktsd.ReceivedDate',
        DB::raw('CASE 
        WHEN ktsd.DeliveryStatusID = 1 THEN "Open" 
        WHEN ktsd.DeliveryStatusID = 2 THEN "Close"
        WHEN ktsd.DeliveryStatusID = 3 THEN "Sent"
        WHEN ktsd.DeliveryStatusID = 4 THEN "Delivered"
        WHEN ktsd.DeliveryStatusID = 5 THEN "Finish"
        ELSE "-" END AS StatusDelivery'),
        'ktsd.ArrivalEstimation',
        'ktsd.PackageNumber',
        'ktsd.DestDriver',
        'ktsd.DestTransportID',
        'ktsd.DestTransportNumber',
        'ktsd.CreatedBy',
        'ktsd.DateCreated',
        'ktsd.LastModifiedBy',
        'ktsd.DateUpdated',
        'ktsd.SMESPCodeID',
        'vso.Name AS Destination',
        DB::raw('SUM(ktsdd.Weight) AS VolumeNetto'),
        'ktsdd.StatusCode AS StatusCode',
        'ktsd.FinalCapacity',
        'ktsd.PaymentDelivery')
        ->leftJoin('view_tc_supplychain_org AS vso', 'vso.SupplychainID', '=', 'ktsd.SupplyDestMillOrgID')
        ->leftJoin('ktv_tc_supplychain_delivery_detail AS ktsdd', 'ktsdd.DeliveryID', '=', 'ktsd.DeliveryID')
        ->leftJoin('ktv_tc_supplychain_batch AS ktsb', 'ktsb.SupplyBatchID', '=', 'ktsdd.SupplyBatchID')
        ->where('ktsd.StatusCode', '=', 'active')
        ->where('ktsd.DeliveryStatusID', '!=', '0')
        ->where('ktsd.SupplychainID', '=', '767')
        ->groupBy('ktsd.DeliveryID')
        ->orderByDesc('ktsd.DeliveryID')
        ->get();

        $Total = $Delivery->count();
        
        if($Delivery) {
            foreach ($Delivery as $key => $value) {
                $Delivery[$key]->DeliveryDetail = DB::table('ktv_tc_supplychain_delivery_detail AS ktsdd')
                ->select('ktsdd.DeliveryDetailID',
                'ktsdd.DeliveryID',
                'ktsdd.StatusCode AS Status',
                'ktsd.SupplychainID',
                DB::raw('SUM(ktsdd.Weight) AS Weight'),
                'ktsdd.SupplyBatchID',
                )
                ->leftJoin('ktv_tc_supplychain_delivery AS ktsd', 'ktsd.DeliveryID', '=', 'ktsdd.DeliveryID')
                ->where('ktsd.DeliveryID', '=', $value->DeliveryID)
                ->where('ktsd.StatusCode', '=', 'active' )
                ->get();

                $Delivery[$key]->SupplychainID = DB::table('ktv_tc_supplychain_delivery_detail AS ktsdd')
                ->select('ktsd.SupplychainID')
                ->leftJoin('ktv_tc_supplychain_delivery AS ktsd', 'ktsdd.DeliveryID', '=', 'ktsd.DeliveryID')
                ->where('ktsdd.DeliveryID', '=', $value->DeliveryID)
                ->where('ktsdd.StatusCode', '=', 'active' )
                ->first();
            }
        }

       //make response JSON
       return response()->json([
           'success' => true,
           'message' => 'Data Berhasil Ditampilkan',
           'total'   => $Total,
           'data'    => $Delivery  
       ], 200);
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
