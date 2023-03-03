<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Organizations;
use App\Models\Region;
use App\Models\User;
use http\Env\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Psy\Util\Str;

class ConsultantsController extends Controller
{
    protected function check_region_and_organization ($region_id, $organization_id) {
        if (!(Region::find($region_id))) {
            return response()->json(['data' => ['error' => 'Регион не найден'], 'status' => 'error'], 404);
        }

        if (!(Organizations::find($organization_id))) {
            return response()->json(['data' => ['error' => 'Организация не найдена'], 'status' => 'error'], 404);
        }
    }



    public function get_all_consultants (Request $request, $region_id, $organization_id): JsonResponse {
        $consultants = Consultant::where([['fk_region', $region_id], ['fk_organization', $organization_id]])->get();

        $this->check_region_and_organization($region_id, $organization_id);

        $result = [];
        foreach ($consultants as $consultant) {
            array_push($result, User::find($consultant['fk_user']));
        }

        return response()->json(['data' => ['consultants' => $result], 'status' => 'ok'], 200);
    }

    public function add_consultants(Request $request, $region_id, $organization_id): JsonResponse {

        $this->check_region_and_organization($region_id, $organization_id);

        $validator  =   Validator::make($request->all(), [
            "firstname"  =>  "required",
            "lastname"  =>  "required",
            "email"  =>  "required|email",
            "password"  =>  "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);


        if (is_null(User::where('email', $inputs['email'])->get())) {
            return response()->json(["status" => "failed", "message" => "Email is already in use!"]);
        }

        $user   =   User::create($inputs);

        $consultant = Consultant::create(['fk_region' => $region_id, 'fk_user' => $user['id'], 'fk_organization' => $organization_id]);


        return response()->json(['data' => ['region' => User::find($consultant['fk_user'])], 'status' => 'ok'], 200);
    }

    public function edit_consultants(Request $request, $region_id, $organization_id, $consultant_id): JsonResponse {

        $this->check_region_and_organization($region_id, $organization_id);

        if (!(Consultant::find($consultant_id))) {
            return response()->json(['data' => ['error' => 'Консультант не найден'], 'status' => 'error'], 404);
        }

        $consultant = Consultant::where([['fk_region', $region_id], ['fk_organization', $organization_id], ['id', $consultant_id]])->first();
        if (!$consultant) {
            return response()->json(['data' => ['error' => 'Консультант не найден'], 'status' => 'ok'], 400);
        }

        $validator  =   Validator::make($request->all(), [
            "firstname"  =>  "required",
            "lastname"  =>  "required",
            "email"  =>  "required|email",
            "password"  =>  "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();
        $inputs["password"] = Hash::make($request->password);

        User::find($consultant['fk_user'])->update($inputs);
        return response()->json(['data' => ['region' => User::find($consultant['fk_user'])], 'status' => 'ok'], 200);
    }

    public function delete_consultants (Request $request, $region_id, $organization_id, $consultant_id): JsonResponse {
        $this->check_region_and_organization($region_id, $organization_id);
        $consultant = Consultant::where([['fk_region', $region_id], ['fk_organization', $organization_id], ['id', $consultant_id]])->first();
        if (!$consultant) {
            return response()->json(['data' => ['error' => 'Консультант не найден'], 'status' => 'ok'], 400);
        }

        $user = User::find($consultant['fk_user']);

        try {
            $consultant->delete();
            $user->delete();
            return response()->json(['status' => 'ok'], 200);
        } catch (QueryException $e) {
            return response()->json(['data' => ['error' => 'Организация содержит консультантов'], 'status' => 'error'], 400);
        }

    }
}
