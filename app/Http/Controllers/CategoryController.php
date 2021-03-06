<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Category;
use App\Util\CategoryTreeBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class CategoryController extends Controller
{
    public function add(Request $request): JsonResponse
    {
        $input = $request->all();

        if (empty($input['title']))
        {
            return response()->json(['status' => false, 'message' => 'Paramater \'title\' is required.']);
        }

        $input['parent_id'] = empty($input['parent_id']) ? null : $input['parent_id'];

        /** @var \Illuminate\Database\Query\Builder $query */
        $query = Category::where('id', '=', $input['parent_id']);

        if ($input['parent_id'] !== null && $query->first() === null)
        {
            return response()->json(['status' => false, 'message' => 'Parent category not exist.']);
        }

        Category::create($input);

        return response()->json(['status' => true]);
    }

    public function retrieve(Request $request): JsonResponse
    {
        $input = $request->all();

        if (empty($input['method']))
        {
            return response()->json(['status' => false, 'message' => 'Paramater \'method\' is required.']);
        }

        $temp = Category::all();
        $categories = [];

        foreach ($temp as $t)
        {
            $categories[] = ['id' => (int) $t->id, 'parent_id' => (int) $t->parent_id, 'title' => $t->title];
        }

        switch ($input['method'])
        {
            case 'recursive':
                $response['status'] = true;
                $response['categories'] = CategoryTreeBuilder::buildUsingRecursiveMethod($categories);
                break;

            case 'iterative':
                $response['status'] = true;
                $response['categories'] = CategoryTreeBuilder::buildUsingIterativeMethod($categories);
                break;

            default:
                $response['status'] = false;
                $response = ['message' => 'Unknown method.'];
        }

        return response()->json($response);
    }
}
