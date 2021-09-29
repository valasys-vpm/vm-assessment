<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\UserAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repository\UserRepository\UserRepository;

class HomeController extends Controller
{
    private $data;
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->data = array();
    }

    public function index()
    {
        $this->data['resultAssessment'] = Assessment::whereStatus(1)->first();
        if(!empty($this->data['resultAssessment'])) {
            $this->data['resultUserAssessment'] = UserAssessment::whereAssessmentId($this->data['resultAssessment']->id)->whereUserId(Auth::id())->first();
        }
        return view('user.dashboard', $this->data);
    }
    

    


}
