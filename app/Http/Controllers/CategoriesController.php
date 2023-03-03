<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function get_all_categories (Request $request) {
        return response()->json(['data' => ['categories' => Categories::all()], 'status' => 'ok'], 200);
    }
    public function create_category (Request $request) {
        $name = $request->input('title');
        return response()->json(['data' => ['category' => Categories::create(['title' => $name])], 'status' => 'ok'], 200);
    }
    public function edit_category (Request $request, $category_id) {
        $name = $request->input('title');

        if (Categories::find($category_id)) {
            Categories::find($category_id)->update(['title' => $name]);
            return response()->json(['data' => ['category' => Categories::find($category_id)], 'status' => 'ok'], 200);
        } else {
            return response()->json(['data' => ['category' => 'Категория не найдена!'], 'status' => 'error'], 404);
        }
    }
    public function delete_category (Request $request, $category_id){

        if (Categories::find($category_id)) {
            try {
                Categories::find($category_id)->delete();
                return response()->json(['status' => 'ok'], 200);
            } catch (QueryException $e) {
                return response()->json(['data' => ['category' => 'Категория содержит вопросы!'], 'status' => 'error'], 400);
            }

        } else {
            return response()->json(['data' => ['category' => 'Категория не найдена!'], 'status' => 'error'], 404);
        }
    }

}
