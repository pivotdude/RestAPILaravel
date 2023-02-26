<?php

namespace App\Http\Controllers;

use App\Models\Organizations;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function get_all_organizations (Request $request, $region_id): JsonResponse {
        $organizations = Organizations::where('fk_regions', $region_id)->get();

        if (is_null($organizations)) {
            return response()->json(['data' => ['error' => 'Организация не найден'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['regions' => $organizations], 'status' => 'ok'], 200);
    }

    public function add_organization(Request $request, $region_id): JsonResponse {
        $name = $request->input('name');
        $region = Organizations::create(['fk_regions' => $region_id, 'name' => $name]);

        if (is_null($region)) {
            return response()->json(['data' => ['error' => 'Организация не найдена'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['region' => $region], 'status' => 'ok'], 200);
    }

    public function edit_organization(Request $request, $region_id, $id): JsonResponse {
        $name = $request->input('name');
        $region = Organizations::where([['fk_regions', $region_id], ['id', $id]])->update(['name' => $name]);
        if ($region == 0) {
            return response()->json(['data' => ['error' => 'Организации не существует'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => ['region' => Organizations::where([['fk_regions', $region_id], ['id', $id]])->get()], 'status' => 'ok'], 200);
    }

    public function delete_organization (Request $request, $region_id, $id): JsonResponse {

        $organization = Organizations::where([['fk_regions', $region_id], ['id', $id]]);

        if (is_null($organization)) {
            return response()->json(['data' => ['error' => 'Организация не найдена'], 'status' => 'error'], 404);
        }

        if ($organization->delete()) {
            return response()->json(['status' => 'ok'], 200);
        } else {
            return response()->json(['data' => ['error' => 'Регион содержит консультантов'], 'status' => 'ok'], 400);
        }
    }
}
