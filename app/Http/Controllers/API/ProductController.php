<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'qty' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'qty' => $request->qty,
                'user_id' => $request->user_id,
            ]);

            return [
                'status' => 200,
                'message' => 'Record added successfully',
            ];
        } catch (\Exception $e) {

            return [
                'status' => 100,
                'error' => $e,
            ];
        }


    }

    public function edit(Request $request, $id)
    {
        try {
            $product = Product::where('id', $id)->first();
            return [
                'status' => 200,
                'data' => $product
            ];
        } catch (\Exception $e) {
            return [
                'status' => 100,
                'error' => $e
            ];
        }


    }

    public function update(Request $request, $id)
    {

        $data = $request->all();
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'qty' => 'required|integer|min:1',
            'user_id' => 'required|exists:users,id'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {

            $product = Product::where('id', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'qty' => $request->qty,
                'user_id' => $request->user_id,
            ]);

            return [
                'status' => 200,
                'message' => 'Record Updated Successfully'
            ];

        } catch (\Exception $e) {

            return [
                'status' => 100,
                'error' => $e
            ];
        }

    }

    public function list()
    {
        try {
            $productList = Product::with('users')->get();

            return [
                'status' => 200,
                'data' => $productList
            ];
        } catch (\Exception $e) {
            return [
                'status' => 100,
                'error' => $e
            ];
        }

    }

    public function delete($id)
    {
        try {

          $delete = Product::where('id',$id)->delete();

            return [
                'status' => 200,
                'message' => 'Record Deleted Successfully'
            ];

        }
        catch (\Exception $e){
            return [
                'status' => 200,
                'error' => $e
            ];
        }

    }
}
