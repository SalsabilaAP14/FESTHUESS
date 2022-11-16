<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies =  Company::get();

        if (count($companies) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'Data successfully accepted',
                'data' => $companies
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
            'name' => ['required','string','max:255','unique:companies,name'],
            'phone' => ['required','string','max:255','unique:companies,phone'],
            'email' => ['required','string','max:255','unique:companies,email']
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

        $company = Company::create($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully created',
            'data' => $company
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
        $company = Company::find($id);

        if (!$company) {
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
            'data' => $company
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
            'name' => ['nullable','string','max:255','unique:companies,name'],
            'phone' => ['nullable','string','max:255','unique:companies,phone'],
            'email' => ['nullable','string','max:255','unique:companies,email']
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

        $company = Company::findById($id)->update($validated);

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'Data successfully updated',
            'data' => $company
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
        Company::findById($id)->delete();

        $companies = Company::get();

        if (count($companies) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'Data successfully removed',
                'data' => $companies
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
