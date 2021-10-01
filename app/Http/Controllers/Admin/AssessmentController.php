<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\UserAssessment;
use App\Repository\AssessmentRepository\AssessmentRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AssessmentController extends Controller
{
    private $data;
    private $assessmentRepository;
    private $categoryRepository;

    public function __construct(AssessmentRepository $assessmentRepository, CategoryRepository $categoryRepository)
    {
        $this->data = array();
        $this->assessmentRepository = $assessmentRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return view('admin.assessment.list');
    }

    public function show($id)
    {
        $this->data['resultAssessment'] = $this->assessmentRepository->find(base64_decode($id));
        $this->data['resultCategories'] = $this->categoryRepository->get(array('status' => 1));
        return view('admin.assessment.show', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->assessmentRepository->store($attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->assessmentRepository->find(base64_decode($id));
        if(!empty($result)) {
            return response()->json(array('status' => true, 'data' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->assessmentRepository->update(base64_decode($id),$attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->assessmentRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function getAssessments (Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Assessment::query();
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) { }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('name', $orderDirection); break;
            case '1': $query->orderBy('date', $orderDirection); break;
            case '2': $query->orderBy('number_of_questions', $orderDirection); break;
            case '3': $query->orderBy('status', $orderDirection); break;
            case '4': $query->orderBy('created_at', $orderDirection); break;
            case '5': $query->orderBy('updated_at', $orderDirection); break;
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

    public function getAssessmentResult ($id = null, Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = UserAssessment::query();
        if (isset($id) && !empty($id)) {
            $query->whereAssessmentId(base64_decode($id));
        }
        $query->with('user');
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) { }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('marks_obtained', $orderDirection); break;
            case '1': $query->orderBy('attempted', $orderDirection); break;
            case '2': $query->orderBy('marks_obtained', $orderDirection); break;
            case '3': $query->orderBy('submit_count', $orderDirection); break;
            default: $query->orderBy('marks_obtained', 'DESC'); break;
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

    public function sendAssessmentResult($id)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //Send Mail
            $this->data['resultUserAssessment'] = UserAssessment::with(['user' => function($user){
                $user->with('designation');
            }])->whereAssessmentId(base64_decode($id))->get();
            $this->data['resultAssessment'] = Assessment::findOrFail(base64_decode($id));
            $details = $this->data;
            $html = view('admin.email.assessment_result', $this->data)->render();
            //dd($html);
            //return response()->json(array('status' => true, 'message' => $response['message'], 'html' => $html));
            //dd($this->data['resultUserAssessment']->toArray());
            Mail::send('admin.email.assessment_result', $details, function ($email) use ($details){
                $email->to([
                    'sagar@valasys.com',
                    'tejaswi@valasys.com'
                ])->subject('Result for '.$this->data['resultAssessment']->name.' | '.date('d-M-Y', strtotime($this->data['resultAssessment']->date)));
            });
            $response = array('status' => TRUE, 'message' => 'Mail sent successfully.');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }

        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

}
