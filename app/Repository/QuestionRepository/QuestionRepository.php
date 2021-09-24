<?php

namespace App\Repository\QuestionRepository;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionPaper;
use Illuminate\Support\Facades\DB;

class QuestionRepository implements QuestionInterface
{
    private $question;
    private $questionOption;

    public function __construct(
        Question $question,
        QuestionOption $questionOption
    )
    {
        $this->question = $question;
        $this->questionOption = $questionOption;
    }

    public function get($filters = array())
    {
        $query = Question::query();
        $query->with([
            'category',
            'options'
        ]);

        return $query->get();
    }

    public function find($id)
    {
        return $this->question->with([ 'category', 'options' ])->findOrFail($id);
    }

    public function store($attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $question = new Question();
            $question->assessment_id = $attributes['assessment_id'];
            $question->category_id = $attributes['category_id'];
            $question->question = $attributes['question'];
            $question->status = $attributes['status'];
            $question->save();
            if($question->id) {
                //Save Options
                foreach ($attributes['options'] as $key => $value) {
                    $questionOption = new QuestionOption();
                    $questionOption->question_id  = $question->id;
                    $questionOption->option  = $value;
                    if($key == $attributes['answer']) {
                        $questionOption->is_answer  = 1;
                    } else {
                        $questionOption->is_answer  = 0;
                    }
                    $questionOption->save();
                }

                //Question Paper
                if(isset($attributes['date']) && !empty($attributes['date'])) {
                    $questionPaper = new QuestionPaper();
                    $questionPaper->question_id = $question->id;
                    $questionPaper->date = date('Y-m-d', strtotime($attributes['date']));
                    $questionPaper->save();
                }
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Question added successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $question = $this->find($id);
            $question->assessment_id = $attributes['assessment_id'];
            $question->category_id = $attributes['category_id'];
            $question->question = $attributes['question'];
            $question->status = $attributes['status'];
            $question->update();
            if($question->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Question updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $question = $this->find($id);
            if($question->delete()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Question deleted successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }
}
