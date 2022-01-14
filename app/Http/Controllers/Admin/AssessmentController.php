<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\User;
use App\Models\UserAssessment;
use App\Repository\AssessmentRepository\AssessmentRepository;
use App\Repository\CategoryRepository\CategoryRepository;
use App\Repository\UserAssessmentRepository\UserAssessmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AssessmentController extends Controller
{
    private $data;
    private $assessmentRepository;
    private $userAssessmentRepository;
    private $categoryRepository;

    public function __construct(
        AssessmentRepository $assessmentRepository,
        UserAssessmentRepository $userAssessmentRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->data = array();
        $this->assessmentRepository = $assessmentRepository;
        $this->userAssessmentRepository = $userAssessmentRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        //echo date('M-Y', strtotime('2021-10-01'));die;
        return view('admin.assessment.list');
    }

    public function show($id)
    {
        $this->data['resultAssessment'] = $this->assessmentRepository->find(base64_decode($id));
        $this->data['resultCategories'] = $this->categoryRepository->get(array('status' => 1));
        return view('admin.assessment.show', $this->data);
    }

    public function showUserAssessment($id)
    {
        $this->data['resultUserAssessment'] = $this->userAssessmentRepository->find(base64_decode($id));
        $this->data['resultQuestions'] = Question::with('options')->whereAssessmentId($this->data['resultUserAssessment']->assessment_id)->whereStatus(1)->get();
        return view('admin.assessment.show_user_assessment', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->assessmentRepository->store($attributes);
        if ($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function edit($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->assessmentRepository->find(base64_decode($id));
        if (!empty($result)) {
            return response()->json(array('status' => true, 'data' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->assessmentRepository->update(base64_decode($id), $attributes);
        if ($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $response = $this->assessmentRepository->destroy(base64_decode($id));
        if ($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function getAssessments(Request $request): \Illuminate\Http\JsonResponse
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
        if (isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if (!empty($filters)) {
        }


        //Order By
        $orderColumn = null;
        if ($request->has('order')){
            $order = $request->get('order');
            $orderColumn = $order[0]['column'];
            $orderDirection = $order[0]['dir'];
        }

        switch ($orderColumn) {
            case '0':
                $query->orderBy('created_at', 'DESC');
                break;
            case '1':
                $query->orderBy('date', $orderDirection);
                break;
            case '2':
                $query->orderBy('number_of_questions', $orderDirection);
                break;
            case '3':
                $query->orderBy('status', $orderDirection);
                break;
            case '4':
                $query->orderBy('created_at', $orderDirection);
                break;
            case '5':
                $query->orderBy('updated_at', $orderDirection);
                break;
            default:
                $query->orderBy('created_at', 'DESC');
                break;
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

    public function getAssessmentResult($id = null, Request $request): \Illuminate\Http\JsonResponse
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
        if (isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if (!empty($filters)) {
        }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0':
                $query->orderBy('marks_obtained', $orderDirection);
                break;
            case '1':
                $query->orderBy('attempted', $orderDirection);
                break;
            case '2':
                $query->orderBy('marks_obtained', $orderDirection);
                break;
            case '3':
                $query->orderBy('submit_count', $orderDirection);
                break;
            default:
                $query->orderBy('marks_obtained', 'DESC');
                break;
        }

        $totalFilterRecords = $query->count();

        if ($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }
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
            $this->data['resultUserAssessment'] = UserAssessment::with(['user' => function ($user) {
                $user->with('designation');
            }])->whereAssessmentId(base64_decode($id))->get();
            $this->data['resultAssessment'] = Assessment::findOrFail(base64_decode($id));
            $details = $this->data;
            $html = view('admin.email.assessment_result', $this->data)->render();
            dd($html);
            //return response()->json(array('status' => true, 'message' => $response['message'], 'html' => $html));
            //dd($this->data['resultUserAssessment']->toArray());
            Mail::send('admin.email.assessment_result', $details, function ($email) use ($details) {
                $email->to([
                    'sagar@valasys.com',
                    'tejaswi@valasys.com'
                ])->subject('Result for ' . $this->data['resultAssessment']->name . ' | ' . date('d-M-Y', strtotime($this->data['resultAssessment']->date)));
            });
            $response = array('status' => TRUE, 'message' => 'Mail sent successfully.');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }

        if ($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    // public function sendAssessmentResultBulk()
    // {
    //     $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
    //     try {
    //         //Send Mail
    //         $query = User::query();
    //         $query->whereRoleId(2);

    //         $query->whereDepartmentId(2);
    //         //$query->whereDesignationId(2);
    //         //$query->whereDesignationId(17);
    //         //$query->whereIn('department_id', [4,5]);

    //         $query->whereNotIn('employee_code', ['VBS034']);
    //         $query->orderBy('employee_code');
    //         $this->data['results'] = $query->get();
    //         dd($this->data['results']->toArray());
    //         $this->data['resultAssessments'] = Assessment::where('group_id', 1)->where('status', 2)->OrderBy('created_at')->whereMonth('date', 10)->whereYear('date', 2021)->get();

    //         $details = $this->data;
    //         return view('admin.email.assessment_result_bulk', $this->data);
    //         $html = view('admin.email.assessment_result_bulk', $this->data)->render();
    //         dd($html);
    //         //return response()->json(array('status' => true, 'message' => $response['message'], 'html' => $html));
    //         //dd($this->data['resultUserAssessment']->toArray());
    //         Mail::send('admin.email.assessment_result_bulk', $details, function ($email) use ($details){
    //             $email->to([
    //                 'sagar@valasys.com',
    //                 'tejaswi@valasys.com'
    //             ])->subject('Result for '.$this->data['resultAssessment']->name.' | '.date('d-M-Y', strtotime($this->data['resultAssessment']->date)));
    //         });
    //         $response = array('status' => TRUE, 'message' => 'Mail sent successfully.');
    //     } catch (\Exception $exception) {
    //         dd($exception->getMessage());
    //         $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
    //     }

    //     if($response['status'] == TRUE) {
    //         return response()->json(array('status' => true, 'message' => $response['message']));
    //     } else {
    //         return response()->json(array('status' => false, 'message' => $response['message']));
    //     }
    // }
    public function sendAssessmentResultBulk(Request $request)
    {
        $department = $request->get('department');
        $month = $request->get('month');
        $year = $request->get('year');

        if (!empty($year)) {
            $yearName = date('Y', strtotime($year . '-01-01'));
        } else {
            $yearName = 'All';
        }

        if (!empty($month)) {
            $monthName = date('M', strtotime('2021-' . $month . '-01'));
            if (!empty($year)) {
                $monthName = $monthName . '-';
            } else {
                $yearName = '';
            }
        } else {
            $monthName = '';
        }



        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //Send Mail
            $query = User::query();
            $query->whereRoleId(2);
            if ($department == 'operation') {
                $query->whereDepartmentId(2);
                $groupId = 1;

                $departmentName = 'Operation-' . $monthName . $yearName;
            } else {
                $query->whereNotIn('department_id', [1, 2]);
                $groupId = 2;
                $departmentName = 'Creative-' . $monthName . $yearName;
            }

            $query->whereNotIn('employee_code', ['VBS034']);
            $query->orderBy('employee_code');
            $this->data['results'] = $query->get();

            $query2 = Assessment::query();
            if (!empty($groupId)) {
                $query2->where('group_id', $groupId);
            }
            $query2->where('status', 2);
            $query2->OrderBy('created_at');

            if (isset($month) && !empty($month)) {
                $query2->whereMonth('date', $month);
            }
            if (isset($year) && !empty($year)) {
                $query2->whereYear('date', $year);
            }

            $this->data['resultAssessments'] = $query2->get();

            //return view('admin.email.assessment_result_bulk', $this->data);
            $html = view('admin.email.assessment_result_bulk', $this->data)->render();
            Storage::makeDirectory('public/result');
            $html_filename = $departmentName . '.html';
            $filename = 'public/result/' . $html_filename;
            Storage::put($filename, $html);


            $response = array('status' => TRUE, 'message' => $html_filename);
        } catch (\Exception $exception) {
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }

        if ($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }
}
