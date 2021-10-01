<?php

namespace App\Repository\UserAssessmentRepository;

use App\Models\UserAssessment;

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
}
