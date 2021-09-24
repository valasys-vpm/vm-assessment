<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Repository\QuestionRepository\QuestionRepository;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private $data;
    private $categoryRepository;
    private $questionRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        QuestionRepository $questionRepository
    )
    {
        $this->data = array();
        $this->categoryRepository = $categoryRepository;
        $this->questionRepository = $questionRepository;
    }

    public function index()
    {
        $this->data['resultCategories'] = $this->categoryRepository->get(array('status' => 1));
        return view('admin.question.list', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->questionRepository->store($attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->questionRepository->find(base64_decode($id));
        if(!empty($result)) {
            return response()->json(array('status' => true, 'data' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->questionRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->questionRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function getQuestions (Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Question::query();
        $query->with([
            'category',
            'options'
        ]);

        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("question", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) { }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('category_id', $orderDirection); break;
            case '1': $query->orderBy('question', $orderDirection); break;
            case '2': $query->orderBy('options', $orderDirection); break;
            case '3': $query->orderBy('created_at', $orderDirection); break;
            case '4': $query->orderBy('updated_at', $orderDirection); break;
            default: $query->orderBy('created_at'); break;
        }

        $totalFilterRecords = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $result = $query->get();

        $ajaxData = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFilterRecords,
            "aaData" => $result
        );

        return response()->json($ajaxData);
    }
}
