<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
//
{
    public function get_all_regions(Request $request) {
        $regions = Region::all();
        return response()->json(['data' => ['regions' => $regions], 'status' => 'ok'], 200);
    }

    public function add_region(Request $request) {
        $name = $request->input('name');
        $region = Region::firstOrCreate(['name' => $name]);
        return response()->json(['data' => ['region' => $region], 'status' => 'ok'], 200);
    }

    public function edit_region(Request $request, $id) {
        $new_name = $request->all();
//        $region = Region::where('id', $id)->update(['name' => $new_name]);

//        if ($region == 0) {
//            return response()->json(['data' => ['error' => 'Региона не существует'], 'status' => 'error'], 404);
//        }
//Region::find($id)
        return response()->json(['data' => ['region' => $new_name], 'status' => 'ok'], 200);
    }

    public function delete_region (Request $request, $id) {

        if (!Region::find($id)) {
            return response()->json(['data' => ['error' => 'Регион не найден'], 'status' => 'error'], 404);
        }

        try {
            Region::find($id)->delete();
            return response()->json(['status' => 'ok'], 200);
        } catch (QueryException $e) {
            return response()->json(['data' => ['error' => 'Регион содержит организцации'], 'status' => 'ok'], 400);
        }


    }
}
