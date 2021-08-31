<?php
namespace App\Http\Controllers;

use JWTAuth;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $transaction = Transaction::latest()->get();

       //make response JSON
       return response()->json([
           'success' => true,
           'message' => 'List Data Transaction',
           'data'    => $transaction  
       ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //set validation
       $validator = Validator::make($request->all(), [
            'uuid'   => 'required',
            'user_id' => 'required',
            'device_timestamp' => 'required',
            'total_amount' => 'required',
            'paid_amount' => 'required',
            'payment_method' => 'required'
        ]);
    
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $transaction = Transaction::create([
            'uuid'   => $request->uuid,
            'user_id' => $request->user_id,
            'device_timestamp' => $request->device_timestamp,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'payment_method' => $request->payment_method,
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrfail($id);

        //make response JSON
        return response()->json([
           'success' => true,
           'message' => 'Detail Data Transaction',
           'data'    => $transaction 
        ], 200);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //Validate data
        $validator = Validator::make($request->all(), [
            'uuid'   => 'required',
            'user_id' => 'required',
            'device_timestamp' => 'required',
            'total_amount' => 'required',
            'paid_amount' => 'required',
            'payment_method' => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find transaction by ID
        $transaction = Transaction::findOrFail($transaction->id);

        if($transaction) {

            //update transaction
            $transaction->update([
                'uuid'   => $request->uuid,
                'user_id' => $request->user_id,
                'device_timestamp' => $request->device_timestamp,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'payment_method' => $request->payment_method
            ]);

            return response()->json([
                'success' => true,
                'message' => 'transaction Updated',
                'data'    => $transaction  
            ], 200);
        }

        //Transaction updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Transaction updated successfully',
            'data' => $transaction
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $Transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully'
        ], Response::HTTP_OK);
    }
}