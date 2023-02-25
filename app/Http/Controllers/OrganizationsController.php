<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function get_all_organizations (Request $request) {
        $regions = Region::all();
        return response()->json(['data' => ['regions' => $regions], 'status' => 'ok'], 200);
    }

    public function add_region(Request $request) {
        $name = $request->input('name');
        $region = Region::firstOrCreate(['name' => $name]);
        return response()->json(['data' => ['region' => $region], 'status' => 'ok'], 200);
    }

    public function edit_organization(Request $request, $id) {
        $new_name = $request->input('name');
        $region = Region::where('id', $id)->update(['name' => $new_name]);

        if ($region == 0) {
            return response()->json(['data' => ['error' => 'Региона не существует'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['region' => Region::find($id)], 'status' => 'ok'], 200);
    }

    public function delete_organization (Request $request, $id) {

        if (!Region::find($id)) {
            return response()->json(['data' => ['error' => 'Регион не найден'], 'status' => 'error'], 404);
        }

        if (Region::find($id)->delete()) {
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['data' => ['error' => 'Регион содержит организцации'], 'status' => 'ok'], 400);
        }
    }
}
