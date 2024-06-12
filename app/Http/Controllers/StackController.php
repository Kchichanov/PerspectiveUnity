<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stack;
use Illuminate\Support\Facades\DB;
use Validator;

class StackController extends Controller
{

    public function addToStack(Request $request) {

            $validator = Validator::make($request->all(), [

                'value' => 'required|string',

            ]);
        
            if ($validator->fails()) {

                return response()->json([

                    'status' => 400,
                    'message' => $validator->messages(),

                ], 400);

            }
        
            Stack::create($request->only('value'));
        
            return response()->json([

                'status' => 200,
                'message' => 'Added to stack!',

            ], 200);

    }

    public function getFromStack() {

        return DB::transaction(function () {

            $stack = Stack::orderBy('id', 'desc')->lockForUpdate()->first();

            if ($stack) {

                $value = $stack->value;
                $stack->delete();

                return response()->json(['value' => $value]);

            }

            return response()->json([
                
                'message' => 'Stack is empty'
            
            ], 404);

        });
    }

}
