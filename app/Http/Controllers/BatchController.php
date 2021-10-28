<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Batch = DB::table('ktv_tc_supplychain_batch AS sb')
        ->select('sb.*',
        DB::raw('IF(SUM(ktst.VolumeBruto) IS NULL, FORMAT(0, 2),SUM(ktst.VolumeBruto)) AS VolumeBruto'),
        DB::raw('IF(SUM(ktst.VolumeNetto) IS NULL, FORMAT(0, 2),SUM(ktst.VolumeNetto)) AS VolumeNetto'),
        'ktst.SupplyTransID')
        ->leftJoin('view_tc_supplychain_org AS vso', 'vso.SupplychainID', '=', 'sb.SupplyOrgID')
        ->leftJoin('view_tc_supplychain_org AS vso1', 'vso1.SupplychainID', '=', 'sb.SupplyDestMillOrgID')
        ->leftJoin('ktv_tc_supplychain_transaction AS ktst', 'ktst.SupplyBatchID', '=', 'sb.SupplyBatchID')
        ->whereIn('sb.SupplyBatchStatus', ['Open','Closed'])
        ->where('sb.StatusCode', '=', 'active')
        ->whereNotNull('ktst.VolumeNetto')
        ->groupBy('sb.SupplyBatchID')
        ->orderByDesc('sb.DateCreated')
        ->where('sb.SupplyOrgID', '=', '767')
        ->get();

        $total = $Batch->count();
        
        if($Batch) {
            foreach ($Batch as $key => $value) {
                $Batch[$key]->trans = DB::table('ktv_tc_supplychain_transaction AS c')
                ->where('c.StatusCode', '=', 'active')
                ->where('c.SupplyBatchID', '=', $value->SupplyBatchID)
                ->get();
            }
        }

       //make response JSON
       return response()->json([
           'success' => true,
           'message' => 'Data Berhasil Ditampilkan',
           'total'   => $total,
           'data'    => $Batch  
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
