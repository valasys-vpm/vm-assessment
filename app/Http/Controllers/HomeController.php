<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repository\UserRepository\UserRepository;


class HomeController extends Controller
{
    private $userRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function updatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->userRepository->update(Auth::id(),$attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => 'Password updated successfully.'));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->logged_on = null;
        $user->save();
        Auth::logout();
        return redirect()->route('login');
    }

}
