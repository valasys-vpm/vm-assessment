<?php

namespace App\Repository\UserAssessmentRepository;

use App\Models\UserAssessment;
use Illuminate\Support\Facades\DB;

class UserAssessmentRepository implements UserAssessmentInterface
{
    private $userAssessment;
    public function __construct(UserAssessment $userAssessment)
    {
        $this->userAssessment = $userAssessment;
    }

    public function find($id)
    {
        $query = UserAssessment::query();
        $query->with(['user', 'assessment']);
        return $query->findOrFail($id);
    }

    public function destroy($id)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $user_assessment = UserAssessment::find($id);
            if($user_assessment->forceDelete()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'User assessment deleted successfully');
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
