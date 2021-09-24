<?php

namespace App\Repository\AssessmentRepository;

use App\Models\Assessment;
use Illuminate\Support\Facades\DB;

class AssessmentRepository implements AssessmentInterface
{
    private $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function get($filters = array())
    {
        $query = Assessment::query();

        return $query->get();
    }

    public function find($id)
    {
        $query = Assessment::query();
        return $query->findOrFail($id);
    }

    public function store($attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $assessment = new Assessment();
            $assessment->name = $attributes['name'];
            $assessment->date = date('Y-m-d', strtotime($attributes['date']));
            $assessment->number_of_questions = $attributes['number_of_questions'];
            $assessment->status = $attributes['status'];
            $assessment->save();
            if($assessment->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Assessment added successfully');
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
            $assessment = $this->find($id);
            $assessment->name = $attributes['name'];
            $assessment->date = date('Y-m-d', strtotime($attributes['date']));
            $assessment->number_of_questions = $attributes['number_of_questions'];
            if($assessment->status != 2) {
                $assessment->status = $attributes['status'];
            }
            $assessment->update();
            if($assessment->id) {
                if($attributes['status'] == 1) {
                    $query = Assessment::query();
                    $query->where('id', '!=', $assessment->id);
                    $query->where('status', '!=', 2);
                    $query->update(['status' => 0]);
                }
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Assessment updated successfully');
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
            $assessment = $this->find($id);
            if($assessment->delete()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Assessment deleted successfully');
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
