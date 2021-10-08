<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\UserAssessment;
use App\Repository\QuestionPaperRepository\QuestionPaperRepository;
use App\Repository\QuestionRepository\QuestionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    private $data;
    private $questionPaperRepository;
    private $questionRepository;

    public function __construct(
        QuestionPaperRepository $questionPaperRepository,
        QuestionRepository $questionRepository)
    {
        $this->data = array();
        $this->questionPaperRepository = $questionPaperRepository;
        $this->questionRepository = $questionRepository;
    }

    public function index()
    {
        return view('user.assessment.list', $this->data);
    }

    public function getMyAssessments(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = UserAssessment::query();
        $query->whereUserId(Auth::id());
        $query->with('assessment');
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            //$query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) { }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('assessment_id', $orderDirection); break;
            case '1': $query->orderBy('date', $orderDirection); break;
            case '2': $query->orderBy('number_of_questions', $orderDirection); break;
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

    public function startAssessment($assessment_id)
    {

        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $userAssessment = new UserAssessment();
            $userAssessment->user_id = Auth::id();
            $userAssessment->assessment_id = base64_decode($assessment_id);
            $userAssessment->save();
            if($userAssessment->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'All the best...');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }

        return redirect()->route('user.assessment.live', $assessment_id);
    }

    public function assessmentLive($assessment_id)
    {
        $this->data['resultAssessment'] = Assessment::findOrFail(base64_decode($assessment_id));
        $this->data['resultUserAssessment'] = UserAssessment::whereAssessmentId(base64_decode($assessment_id))->whereUserId(Auth::id())->first();
        $this->data['resultQuestions'] = Question::with('options')->whereAssessmentId(base64_decode($assessment_id))->whereStatus(1)->get()->shuffle();
        return view('user.assessment.test', $this->data);
    }

    public function submit(Request $request)
    {
        $attributes = $request->all();

        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $userAssessment = UserAssessment::findOrFail(base64_decode($attributes['user_assessment_id']));
            if($userAssessment->submit_count == 0) {
                $userAssessment->answer_given = json_encode($attributes['answer']);
                $userAssessment->attempted = count($attributes['answer']);
                $marks = 0;
                foreach ($attributes['answer'] as $question_id => $option_id) {
                    $resultOption = QuestionOption::findOrFail($option_id);
                    if($resultOption->is_answer) {
                        $marks++;
                    }
                }
                $userAssessment->marks_obtained = $marks;
                $userAssessment->submit_count = 1;
            } else {
                $userAssessment->submit_count = $userAssessment->submit_count + 1;
            }
            $userAssessment->save();
            if($userAssessment->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Submitted Successfully...');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }

        return redirect()->route('user.assessment.result', $attributes['user_assessment_id']);
    }

    public function assessmentResult($userAssessmentId)
    {
        try {
            $this->data['resultUserAssessment'] = UserAssessment::findOrFail(base64_decode($userAssessmentId));
            $this->data['resultAssessment'] = Assessment::findOrFail($this->data['resultUserAssessment']->assessment_id);
            $this->data['resultQuestions'] = Question::with('options')->whereAssessmentId($this->data['resultAssessment']->id)->whereStatus(1)->get();
            return view('user.assessment.result', $this->data);
        } catch (\Exception $exception) {
            //dd($exception->getMessage());
            return view('user.dashboard', $this->data);
        }

    }

}
