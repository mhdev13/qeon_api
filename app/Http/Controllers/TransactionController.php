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
use App\Models\User;
use App\Models\Product;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transaction::all();

        return response()->json([
            'success' => true,
            'message' => 'Get All transaction success',
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
        $checkUser = User::where('id', $request->user_id)->first();

        //check user empty or not
        if(!empty($checkUser)) {
            $user_id = $checkUser->id;
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $checkProduct = Product::where('id', $request->product_id)->first();

        //check product empty or not
        if(!empty($checkProduct)) {
            $product_id = $checkProduct->id;
        } else {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        //set validation
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'product_id'        => 'required',
            'total_amount'      => 'required|numeric|min:0|not_in:0',
            'paid_amount'       => 'required|numeric|min:0|not_in:0',
            'payment_method'    => 'required'
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //if value equal
        if($request->total_amount === $request->paid_amount){
            $status = 'paid';
        } else {
            $status = 'unpaid';
        }

        //save to database
        $transaction = Transaction::create([
            'user_id'               => $user_id,
            'product_id'            => $product_id,
            'total_amount'          => $request->total_amount,
            'paid_amount'           => $request->paid_amount,
            'payment_method'        => $request->payment_method,
            'code_voucher'          => $request->code_voucher,
            'phone_number'          => $request->phone_number,
            'status'                => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'transaction Inserted',
            'data'    => $transaction
        ], 200);
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
}
