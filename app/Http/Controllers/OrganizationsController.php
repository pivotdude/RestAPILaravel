<?php

namespace App\Http\Controllers;

use App\Models\Organizations;
use App\Models\Region;
use http\Env\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function get_all_organizations (Request $request, $region_id): JsonResponse {
        $organizations = Organizations::where('fk_regions', $region_id)->get();

        if (is_null($organizations)) {
            return response()->json(['data' => ['error' => 'Регион не найдена'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['organizations' => $organizations], 'status' => 'ok'], 200);
    }

    public function add_organization(Request $request, $region_id): JsonResponse {
        $name = $request->input('name');
        $organization = Organizations::create(['fk_regions' => $region_id, 'name' => $name]);

        if (is_null($organization)) {
            return response()->json(['data' => ['error' => 'Регион не найдена'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['region' => $organization], 'status' => 'ok'], 200);
    }

    public function edit_organization(Request $request, $region_id, $organization_id): JsonResponse {
        echo $request;
        $name = $request->input('name');

        if (!(Region::find($region_id))) {
            return response()->json(['data' => ['error' => 'Регион не найден'], 'status' => 'error'], 404);
        }
        if (!(Organizations::find($organization_id))) {
            return response()->json(['data' => ['error' => 'Организация не найдена'], 'status' => 'error'], 404);
        }

        $region = Organizations::where([['fk_regions', $region_id], ['id', $organization_id]])->update(['name' => $name]);


        return response()->json(['data' => ['region' => Organizations::where([['fk_regions', $region_id], ['id', $organization_id]])->get()], 'status' => 'ok'], 200);
    }

    public function delete_organization (Request $request, $region_id, $organization_id): JsonResponse {

        $organization = Organizations::where([['fk_regions', $region_id], ['id', $organization_id]]);

        if (!(Region::find($region_id))) {
            return response()->json(['data' => ['error' => 'Регион не найден'], 'status' => 'error'], 404);
        }
        if (!(Organizations::find($organization_id))) {
            return response()->json(['data' => ['error' => 'Организация не найдена'], 'status' => 'error'], 404);
        }

        try {
            $organization->delete();
            return response()->json(['status' => 'ok'], 200);
        } catch (QueryException $e) {
            return response()->json(['data' => ['error' => 'Организация содержит консультантов'], 'status' => 'error'], 400);
        }
    }
}
