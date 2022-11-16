<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::get();

        if (count($transactions) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'Data successfully accepted',
                'data' => $transactions
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully accepted',
            'data' => 'No data available'
        ], 202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'concert' => ['required','numeric'],
            'user' => ['required','numeric'],
            'paid_at' => ['required','string','date_format:d-m-Y'],
            'book_at' => ['required','string','date_format:d-m-Y']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'Data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->getData();

        $validated['paid_at'] = date('Y-m-d', strtotime($validated['paid_at']));
        $validated['book_at'] = date('Y-m-d', strtotime($validated['book_at']));

        $transaction = Transaction::create($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully created',
            'data' => $transaction
        ], 202);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Data not found in our database'
            ], 404);
        }

        return response()->json([
            'code' => 206,
            'status' => 'success',
            'message' => 'Data successfully accepted',
            'data' => $transaction
        ], 206);
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
        $validator = Validator::make($request->all(), [
            'concert' => ['nullable','numeric'],
            'user' => ['nullable','numeric'],
            'paid_at' => ['nullable','string','date_format:d-m-Y'],
            'book_at' => ['nullable','string','date_format:d-m-Y']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'Data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $validated = $validator->getData();

        $validated['paid_at'] = date('Y-m-d', strtotime($validated['paid_at']));
        $validated['book_at'] = date('Y-m-d', strtotime($validated['book_at']));

        $transaction = Transaction::findById($id)->update($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully updated',
            'data' => $transaction
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::findById($id)->delete();

        $transactions = Transaction::get();

        if (count($transactions) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'Data successfully removed',
                'data' => $transactions
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully removed',
            'data' => 'No data available'
        ], 202);
    }
}
