<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Problems;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProblemsController extends Controller
{
    private function check_category ($id) {
        if (!Categories::find($id)) {
            return response()->json(['data' => ['error' => 'Категория не найдена'], 'status' => 'error'], 400);
        }
    }
    private function check_problems ($id) {
        if (!Problems::find($id)) {
            return response()->json(['data' => ['error' => 'Проблема не найдена'], 'status' => 'error'], 400);
        }
    }
    private function check_all ($category_id, $problem_id) {
        $res = Problems::where([['id', $problem_id], ['fk_categories', $category_id]])->get();
        if ($res) {
            if (Categories::find($category_id)) {
                return response()->json(['data' => ['error' => 'Вопрос не найдена'], 'status' => 'error'], 400);
            } else {
                return response()->json(['data' => ['error' => 'Категория не найдена'], 'status' => 'error'], 400);
            }
        }
    }


    public function get_all_problems (Request $request, $category_id): JsonResponse {
        $this->check_category($category_id);
        $all = Problems::where('fk_categories', $category_id)->get();
        return response()->json(['data' => [$all], 'status' => 'ok'], 200);
    }
    public function create_problems (Request $request, $category_id): JsonResponse {
        $this->check_category($category_id);
        $title = $request->input('title');
        $created = Problems::create(['title' => $title, 'fk_categories' => $category_id]);
        return response()->json(['data' => [$created], 'status' => 'ok'], 200);
    }
    public function edit_problems (Request $request, $category_id, $problem_id): JsonResponse {
        $this->check_all($category_id, $problem_id);
        $title = $request->input('title');
        $where = Problems::where([['id', $problem_id], ['fk_categories', $category_id]]);
        $update =  $where->update(['title' => $title]);

        if ($update == 0) {
            return response()->json(['data' => ['error' => 'not found'], 'status' => 'error'], 404);
        }

        return response()->json(['data' => [$where->get()], 'status' => 'ok'], 200);
    }
    public function delete_problems (Request $request, $category_id, $problem_id): JsonResponse {
        $res = Problems::where([['id', $problem_id], ['fk_categories', $category_id]])->get();
        if ($res) {
            try {
                Problems::where([['id', $problem_id], ['fk_categories', $category_id]])->delete();
                return response()->json(['status' => 'ok'], 200);
            } catch (QueryException $e) {
                return response()->json(['data' => ['category' => 'Категория содержит вопросы!'], 'status' => 'error'], 400);
            }

        } else {
            if (Categories::find($category_id)) {
                return response()->json(['data' => ['error' => 'Вопрос не найден'], 'status' => 'error'], 404);
            } else {
                return response()->json(['data' => ['error' => 'Категория не найдена'], 'status' => 'error'], 404);
            }
        }
    }
}
