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


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = DB::table('ktv_tc_supplychain_transaction')
        ->select(
            'ktv_tc_supplychain_transaction.SupplyTransID',
            'ktv_tc_supplychain_transaction.SupplychainID',
            'ktv_tc_supplychain_transaction.SupplyBatchID',
            'ktv_tc_supplychain_transaction.TransNumber',
            'ktv_tc_supplychain_transaction.InvoiceNumber',
            'ktv_tc_supplychain_transaction.DateTransaction',
            'ktv_tc_supplychain_transaction.SupplyType',
            'ktv_tc_supplychain_transaction.SupplyID',
            'ktv_tc_supplychain_batch.SupplyDestType',
            'ktv_tc_supplychain_batch.SupplyDestProcessType',
            'ktv_tc_supplychain_transaction.PlantationNr',
            'ktv_tc_supplychain_transaction.VolumeBruto',
            'ktv_tc_supplychain_transaction.VolumeNetto',
            'ktv_tc_supplychain_transaction.VolumeCutting',
            'ktv_tc_supplychain_transaction.PackageID',
            'ktv_tc_supplychain_transaction.PackageNumber',
            'ktv_tc_supplychain_transaction.PackageWeight',
            'ktv_tc_supplychain_transaction.DetailTypeID',
            'ktv_tc_supplychain_transaction.TransStatusID',
            'ktv_tc_supplychain_transaction.ContractPrice',
            'ktv_tc_supplychain_transaction.NetPrice',
            'ktv_tc_supplychain_transaction.DiscountPrice',
            'ktv_tc_supplychain_transaction.TotalPayment',
            'ktv_tc_supplychain_transaction.PaymentReduction',
            'ktv_tc_supplychain_transaction.PaymentPaid',
            'ktv_tc_supplychain_transaction.Longitude',
            'ktv_tc_supplychain_transaction.Latitude',
            'ktv_tc_supplychain_transaction.Notes',
            'ktv_tc_supplychain_transaction.ChangeLog',
            'ktv_tc_supplychain_transaction.ChangeBy',
            'ktv_tc_supplychain_transaction.DateCreated',
            'ktv_tc_supplychain_transaction.CreatedBy',
            'ktv_tc_supplychain_transaction.DateUpdated',
            'ktv_tc_supplychain_transaction.LastModifiedBy',
            'ktv_tc_supplychain_transaction.DOID',
            'ktv_tc_supplychain_transaction.AgentID',
            'ktv_tc_supplychain_transaction.AgentOther',
            'ktv_tc_supplychain_transaction.AgentOtherNik',
            'ktv_tc_supplychain_transaction.AgentOtherSurvey',
            'ktv_tc_supplychain_transaction.SupplyBatchType',
            'ktv_tc_supplychain_transaction.MillID',
            'ktv_tc_supplychain_transaction.MillOther',
            'ktv_tc_supplychain_transaction.DOOther',
            'ktv_tc_supplychain_transaction.SupplyBatchSourceType',
            'ktv_tc_supplychain_transaction.DeductionPercentage',
            'ktv_tc_supplychain_transaction.DeductionWeight',
            DB::raw('IFNULL(ktv_tc_supplychain_transaction.Bunches,0) AS Bunches'),
            'ktv_tc_supplychain_transaction.CollectpointID',
            'ktv_tc_supplychain_transaction.AutoTransNumber',
            'ktv_tc_supplychain_transaction.isTraceable'
        )
        ->leftJoin('ktv_trace_package', 'ktv_trace_package.PackageID', '=', 'ktv_tc_supplychain_transaction.PackageID')
        ->leftJoin('ktv_tc_supplychain_batch', 'ktv_tc_supplychain_batch.SupplyBatchID', '=', 'ktv_tc_supplychain_transaction.SupplyBatchID')
        ->where('ktv_tc_supplychain_transaction.StatusCode', '=', 'active')
        ->where('ktv_tc_supplychain_transaction.SupplychainID', '=', '767')
        ->get();

        $count = DB::table('ktv_tc_supplychain_transaction')
        ->leftJoin('ktv_trace_package', 'ktv_trace_package.PackageID', '=', 'ktv_tc_supplychain_transaction.PackageID')
        ->leftJoin('ktv_tc_supplychain_batch', 'ktv_tc_supplychain_batch.SupplyBatchID', '=', 'ktv_tc_supplychain_transaction.SupplyBatchID')
        ->where('ktv_tc_supplychain_transaction.StatusCode', '=', 'active')
        ->where('ktv_tc_supplychain_transaction.SupplychainID', '=', '767')
        ->count();
         
       //make response JSON
       return response()->json([
           'success' => true,
           'message' => 'Data Berhasil Ditampilkan',
           'total' => $count,
           'data'    => $transaction  
       ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //    //set validation
    //    $validator = Validator::make($request->all(), [
    //         'uuid'   => 'required',
    //         'user_id' => 'required',
    //         'device_timestamp' => 'required',
    //         'total_amount' => 'required',
    //         'paid_amount' => 'required',
    //         'payment_method' => 'required'
    //     ]);
    
    //     //response error validation
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     //save to database
    //     $transaction = Transaction::create([
    //         'uuid'   => $request->uuid,
    //         'user_id' => $request->user_id,
    //         'device_timestamp' => $request->device_timestamp,
    //         'total_amount' => $request->total_amount,
    //         'paid_amount' => $request->paid_amount,
    //         'payment_method' => $request->payment_method,
    //     ]);

    //     //success save to database
    //     if($transaction) {

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'transaction Created',
    //             'data'    => $transaction  
    //         ], 201);

    //     } 

    //     //failed save to database
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'transaction Failed to Save',
    //     ], 409);
    // }

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