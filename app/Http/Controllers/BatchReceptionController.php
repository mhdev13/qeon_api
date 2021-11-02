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

class BatchReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $BatchReception = DB::table('ktv_tc_supplychain_batch AS sb')
        ->select(
        DB::raw('IF(SUM(st.VolumeNetto) IS NULL, FORMAT(0,2), SUM(st.VolumeNetto)) AS VolumeNetto,vso.Name AS SupplyOrgName'),
        'sb.*'
        )
        ->leftJoin('view_tc_supplychain_org AS vso', 'vso.SupplychainID', '=', 'sb.SupplyOrgID')
        ->leftJoin('view_tc_supplychain_org AS vso1', 'vso1.SupplychainID', '=', 'sb.SupplyDestMillOrgID')
        ->leftJoin('ktv_tc_supplychain_transaction AS st', 'st.SupplyBatchID', '=', 'sb.SupplyBatchID')
        ->where('sb.StatusCode', '=', 'active')
        ->where('sb.SupplyBatchStatus', '=', 'Sent')
        ->orWhere('sb.SupplyOrgID', '=', $request->SID)
        ->orWhere('sb.SupplyDestMillOrgID', '=', $request->SID)
        ->orWhere('sb.SupplyDestDoOrgID', '=', $request->SID)
        ->orderByDesc('sb.SupplyBatchID')
        ->get();

        $Total = $BatchReception->count();

        if($BatchReception) {
            foreach ($BatchReception as $key => $value) {
                $BatchReception[$key]->Trans = DB::table('ktv_tc_supplychain_transaction')
                ->where('StatusCode', '=', 'active' )
                ->where('SupplyBatchID', '=', $value->SupplyBatchID)
                ->get();
            }
        }

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Ditampilkan',
            'total'   => $Total,
            'data'    => $BatchReception  
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
