<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KeyValueStore;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Validator;

class KeyValueStoreController extends Controller
{

    public function addToStore(Request $request) {

        $validator = Validator::make($request->all(), [

            'key' => 'required|string',
            'value' => 'required|string',
            'ttl' => 'nullable|integer'

        ]);

        if ($validator->fails()) {

            return response()->json([

                'status' => 400,
                'message' => $validator->messages(),

            ], 400);

        }

        $data = $request->only(['key', 'value']);

        if ($request->has('ttl')) {

            $data['ttl'] = now()->addSeconds($request->input('ttl'));

        }else {

            $data['ttl'] = null;

        }
    
        DB::transaction(function () use ($data) {
        
            KeyValueStore::updateOrCreate(['key' => $data['key']], $data);
        
        });

        return response()->json(['message' => 'Key-value pair added'], 200);

    }

    public function getValue($key) {

        $item = KeyValueStore::where('key', $key)->first();

        if ($item) {

            if ($item->ttl && Carbon::now()->greaterThan($item->ttl)) {

                $item->delete();
                return response()->json(['message' => 'Key expired'], 404);

            }

            return response()->json(['value' => $item->value]);

        }

        return response()->json(['message' => 'Key not found'], 404);
    }

    public function deleteValue($key) {

        $deleted = false;

        DB::transaction(function () use ($key, &$deleted) {

            $item = KeyValueStore::where('key', $key)->lockForUpdate()->first();

            if ($item) {

                $deleted = $item->delete();

            }

        });

        if ($deleted) {
            
            return response()->json(['message' => 'Key deleted'], 200);
        }

        return response()->json(['message' => 'Key not found'], 404);

    }

}
