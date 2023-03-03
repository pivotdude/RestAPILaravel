<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Consultant;
use App\Models\Consultation;
use App\Models\Organizations;
use App\Models\Problems;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationsController extends Controller
{
    public function get_all_consultations (Request $request) {
        // Тут меби какие-то параметры
        $region_id = $request->query('region_id');
        $organization_id = $request->query('organization_id');
        $consultant_id = $request->query('consultant_id');
        $category_id = $request->query('category_id');
        $problem_id = $request->query('problemId');
        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $status = $request->query('status');
        return response()->json(['data' => ['consultation' => Consultation::all()], 'status' => 'ok'], 201);
    }


    public function get_consultations (Request $request, $consultation_id) {
        $find = Consultation::find($consultation_id);
        if ($find) {
            return response()->json(['data' => ['consultation' => $find], 'status' => 'ok'], 201);
        } else {
            return response()->json(['data' => ['error' => 'Консультация не найдена'], 'status' => 'error'], 201);
        }
    }


    public function create_consultation (Request $request) {
        $validator = Validator::make($request->all(), [
            "firstname" =>  "required|min:2",
            "lastname" =>  "required|min:3",
            "email" => "required|email|unique:consultations|min:3",
            "tel" => 'required',
            "kid" => 'required',
            "age" => 'required',
            "region_id" => 'required',
            "organization_id" => 'required',
            "category_id" => 'required',
            "problem_id" => 'required',
            "consultant_id" => 'required',
            "date" => 'required|date',
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }

        if (!Region::find($request->input('region_id'))) {
            return response()->json(['data' => ['error' => 'Региона не существует'], 'status' => 'error'], 404);
        }
        if (!Organizations::find($request->input('region_id'))) {
            return response()->json(['data' => ['error' => 'Органзиации не существует'], 'status' => 'error'], 404);
        }
        if (!Categories::find($request->input('region_id'))) {
            return response()->json(['data' => ['error' => 'Категории не существует'], 'status' => 'error'], 404);
        }
        if (!Problems::find($request->input('region_id'))) {
            return response()->json(['data' => ['error' => 'Проблемы не существует'], 'status' => 'error'], 404);
        }
        if (!Consultant::find($request->input('region_id'))) {
            return response()->json(['data' => ['error' => 'Консультант не существует'], 'status' => 'error'], 404);
        }

        try {
            $consultation = Consultation::create($request->all(), ['code' => rand(000000, 999999)]);
            return response()->json(['data' => ['consultation' => $consultation], 'status' => 'ok'], 201);
        } catch (QueryException $e) {
            return response()->json(['data' => ['error' => 'Ошибка'], 'status' => 'error'], 400);
        }
    }
    public function mark_consultation (Request $request, $consultation_id) {
        $email = $request->query('email');
        $code = $request->query('code');
        $rating = $request->query('rating');
        $consult = Consultation::where([['code', $code], ['email', $email]]);

        if ($rating == 5 or $rating == 4 or $rating == 3 or $rating == 2 or $rating == 1 and $consult)  {
            $consult->update(['rating' => $rating]);
            return response()->json(['data' => ['consultation' => Consultation::where('code', $code)], 'status' => 'ok'], 201);
        } else {
            return response()->json(['data' => ['error' => 'Неверный код или не коректная оценка'], 'status' => 'error'], 400);
        }

    }
}
