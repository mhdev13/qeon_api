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
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $value = Cache::rememberForever('transaction', function () {
            
            $transaction = DB::table('ktv_tc_supplychain_transaction AS ktst')
            ->select(
                'ktst.SupplyTransID',
                'ktst.SupplychainID',
                'ktst.SupplyBatchID',
                'ktst.TransNumber',
                'ktst.InvoiceNumber',
                'ktst.DateTransaction',
                'ktst.SupplyType',
                'ktst.SupplyID',
                'ktsb.SupplyDestType',
                'ktsb.SupplyDestProcessType',
                'ktst.PlantationNr',
                'ktst.VolumeBruto',
                'ktst.VolumeNetto',
                'ktst.VolumeCutting',
                'ktst.PackageID',
                'ktst.PackageNumber',
                'ktst.PackageWeight',
                'ktst.DetailTypeID',
                'ktst.TransStatusID',
                'ktst.ContractPrice',
                'ktst.NetPrice',
                'ktst.DiscountPrice',
                'ktst.TotalPayment',
                'ktst.PaymentReduction',
                'ktst.PaymentPaid',
                'ktst.Longitude',
                'ktst.Latitude',
                'ktst.Notes',
                'ktst.ChangeLog',
                'ktst.ChangeBy',
                'ktst.DateCreated',
                'ktst.CreatedBy',
                'ktst.DateUpdated',
                'ktst.LastModifiedBy',
                'ktst.DOID',
                'ktst.AgentID',
                'ktst.AgentOther',
                'ktst.AgentOtherNik',
                'ktst.AgentOtherSurvey',
                'ktst.SupplyBatchType',
                'ktst.MillID',
                'ktst.MillOther',
                'ktst.DOOther',
                'ktst.SupplyBatchSourceType',
                'ktst.DeductionPercentage',
                'ktst.DeductionWeight',
                DB::raw('IFNULL(ktst.Bunches,0) AS Bunches'),
                'ktst.CollectpointID',
                'ktst.AutoTransNumber',
                'ktst.isTraceable'
            )
            ->leftJoin('ktv_trace_package AS ktp', 'ktp.PackageID', '=', 'ktst.PackageID')
            ->leftJoin('ktv_tc_supplychain_batch AS ktsb', 'ktsb.SupplyBatchID', '=', 'ktst.SupplyBatchID')
            ->where('ktst.StatusCode', '=', 'active')
            ->where('ktst.SupplychainID', '=', '767')
            ->get();
    
            $total = $transaction->count();
            
        });

        if($value){
            return response()->json([
                'data'    => $value->original
            ]);
        } else {
            return response()->json([
                'success' => failed,
                'message' => 'Data tidak di temukan',
                'code'    => 401  
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // var_dump($request);
        // ddd($request);
        $transaction = DB::table('ktv_tc_supplychain_transaction')
        ->where('SupplyTransID', '>', '0')
        ->where('SupplyID', '=', $request->SupplyID)
        ->where('SupplyType', '=', $request->SupplyType)
        ->get();

        if (DB::table('ktv_tc_supplychain_transaction')->where('SupplyID', '=', $request->SupplyID)->where('SupplyType', '=', $request->SupplyType)->where('SupplyTransID', 1)->exists()) {
            //update
            echo "update";
            exit;
        } else {
            //insert
            // echo "insert";
            // exit;

            // ddd($request->TransNumber);
            unset($request->TransNumber);
            $data['TransNumber'] = $this->_generateTransNumber($request->SupplychainID,$request->DateTransaction); 
            
            $getMembers = DB::table('ktv_members AS b')
            ->leftJoin('ktv_tc_supplychain_farmer AS sf', 'sf.FarmerID', '=', 'b.MemberID')
            ->where('b.MemberID', '=', $request->SupplyType)
            ->get();
            
            if(!empty($Untraceable['MemberID'])){
                foreach($Untraceable['MemberID'] as $k =>$v){
                    $cekFarmUntraceable = array(
                        'FarmerCategory' => $v['FarmerCategory'],
                        'Latitude' => $v['Latitude'],
                        'Longitude' => $v['Longitude'],
                    );
                }
            }
            
            $dataUntraceable = $cekFarmUntraceable;
            
            if($dataUntraceable['FarmerCategory'] == 'Unmapped' && $dataUntraceable['Latitude'] == '' && $dataUntraceable['Longitude'] == ''){
                $data['isTraceable'] = 'NO'; 
            }elseif($dataUntraceable['FarmerCategory'] == 'Mapped' && $dataUntraceable['Latitude'] == '' && $dataUntraceable['Longitude'] == ''){
                $data['isTraceable'] = 'NO'; 
            }elseif($dataUntraceable['FarmerCategory'] == 'Mapped' && $dataUntraceable['Latitude'] != '' && $dataUntraceable['Longitude'] != ''){
                $data['isTraceable'] = 'YES';
            } else {
                $data['isTraceable'] = 'YES';
            }

            $transaction = DB::table('users')->insert([
                'email' => 'kayla@example.com',
                'votes' => 0
            ]);

            //success save to database
            if($transaction) {

                return response()->json([
                    'success' => true,
                    'message' => 'transaction Created',
                    'data'    => $transaction  
                ], 201);

            } 

            //failed save to database
            return response()->json([
                'success' => false,
                'message' => 'transaction Failed to Save',
            ], 409);
        }

        //set validation
        //    $validator = Validator::make($request->all(), [
        //         'uuid'   => 'required',
        //         'user_id' => 'required',
        //         'device_timestamp' => 'required',
        //         'total_amount' => 'required',
        //         'paid_amount' => 'required',
        //         'payment_method' => 'required'
        //     ]);
        
        //response error validation
        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 400);
        // }

        //save to database
        // $transaction = Transaction::create([
        //     'uuid'   => $request->uuid,
        //     'user_id' => $request->user_id,
        //     'device_timestamp' => $request->device_timestamp,
        //     'total_amount' => $request->total_amount,
        //     'paid_amount' => $request->paid_amount,
        //     'payment_method' => $request->payment_method,
        // ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     $transaction = Transaction::findOrfail($id);

    //     //make response JSON
    //     return response()->json([
    //        'success' => true,
    //        'message' => 'Detail Data Transaction',
    //        'data'    => $transaction 
    //     ], 200);
    
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Transaction $transaction)
    // {
    //     //Validate data
    //     $validator = Validator::make($request->all(), [
    //         'uuid'   => 'required',
    //         'user_id' => 'required',
    //         'device_timestamp' => 'required',
    //         'total_amount' => 'required',
    //         'paid_amount' => 'required',
    //         'payment_method' => 'required',
    //     ]);
        
    //     //response error validation
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     //find transaction by ID
    //     $transaction = Transaction::findOrFail($transaction->id);

    //     if($transaction) {

    //         //update transaction
    //         $transaction->update([
    //             'uuid'   => $request->uuid,
    //             'user_id' => $request->user_id,
    //             'device_timestamp' => $request->device_timestamp,
    //             'total_amount' => $request->total_amount,
    //             'paid_amount' => $request->paid_amount,
    //             'payment_method' => $request->payment_method
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'transaction Updated',
    //             'data'    => $transaction  
    //         ], 200);
    //     }

    //     //Transaction updated, return success response
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Transaction updated successfully',
    //         'data' => $transaction
    //     ], Response::HTTP_OK);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Models\Transaction  $Transaction
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Transaction $transaction)
    // {
    //     $transaction->delete();
        
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Transaction deleted successfully'
    //     ], Response::HTTP_OK);
    // }
}