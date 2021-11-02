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

class CollectingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Collecting = DB::table('ktv_collecting_point AS a')
        ->select(
        'a.CollectpointID',
        'a.CollectpointUID',
        'a.CollectpointDisplayID',
        'a.OrgType',
        'a.CollectpointName',
        'a.VillageID',
        'a.CollectpointAddress',
        'a.Longitude',
        'a.Latitude',
        'a.StatusCode',
        'a.Remarks',
        'a.CreatedBy',
        'a.DateCreated',
        'a.DateUpdated',
        'a.LastModifiedBy',
        'a.DateSync',
        'a.uid',
        'c.SubDistrictID',
        'd.DistrictID',
        'e.ProvinceID'
        )
        ->leftJoin('ktv_village AS b', 'a.VillageID', '=', 'b.VillageID')
        ->leftJoin('ktv_subdistrict AS c', 'c.SubDistrictID', '=', 'b.SubDistrictID')
        ->leftJoin('ktv_district AS d', 'd.DistrictID', '=', 'c.DistrictID')
        ->leftJoin('ktv_province AS e', 'd.ProvinceID', '=', 'e.ProvinceID')
        ->where('a.StatusCode', '=', 'active')
        ->groupBy('a.CollectpointID')
        ->get();

        $Total = $Collecting->count();

        if($Collecting) {
            foreach ($Collecting as $key => $value) {
                $Collecting[$key]->Member = DB::table('ktv_collecting_point_member')
                ->select('CollectpointID','MemberID')
                ->where('CollectpointID', '=', $value->CollectpointID)
                ->get();
            }
        }

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Ditampilkan',
            'total'   => $Total,
            'data'    => $Collecting  
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
