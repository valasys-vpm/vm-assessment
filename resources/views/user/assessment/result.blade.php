@extends('layouts.master')

@section('title', 'Assessment | Result')

@section('content')
    <div class="pcoded-main-container" @if(Request::route()->getName() == 'user.assessment.live') style="margin-left: 0 !important;" @endif>
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10"></h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Assessment - {{ date('d/M/Y') }} | Result</h5>
                                        </div>
                                        <div class="card-block">
                                            <h4>Marks Obtained: {{ $resultUserAssessment->marks_obtained }}</h4>
                                            <h4>Total Questions: {{ $resultAssessment->number_of_questions }}</h4>
                                            <h4>Attempted: {{ $resultUserAssessment->attempted }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <div class="card">
                                        <div class="card-block">
                                            <h3>Assessment submitted successfully.</h3>
                                            <br>
                                            <h1>
                                                <span class="text-danger">Happy</span>
                                                <span class="text-success">New</span>
                                                <span class="text-warning">Year!</span>
                                            </h1>
                                            <br>
                                            <p>
                                                You can able to see assessment result once submitted by all users.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent

    <script>
        $(document).ready(function() {
            $('body').bind('cut copy', function(e) {
                e.preventDefault();
            });
            $("body").on("contextmenu", function(e) {
                return false;
            });
        });
    </script>
@append
