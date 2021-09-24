@extends('layouts.master')

@section('title', 'Assessment | ')

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
<!--                                        <h5 class="m-b-10">Dashboard</h5>-->
                                    </div>
                                    <ul class="breadcrumb">
<!--                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i
                                                    class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>-->
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
                                            <h5>Assessment - {{ date('d/M/Y') }} | </h5>
                                            <span class="text-danger">Do not refresh page or go back</span>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-outline-danger btn-lg " onclick="javascript:void(0);"><span id="timer"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" onmousedown="return false" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false">
                                <form id="form-assessment" action="{{ route('user.assessment.submit') }}" method="post" class="col-md-12">
                                    @csrf
                                    <input type="hidden" name="assessment_id" value="{{ base64_encode($resultAssessment->id) }}">
                                    <input type="hidden" name="user_assessment_id" value="{{ base64_encode($resultUserAssessment->id) }}">
                                    <div class="row mt-4">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach($resultQuestions as $key => $question)
                                            <div class="offset-2 offset-md-2 offset-sm-0 col-md-8 col-sm-12">
                                                <div class="card card-border-c-blue">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-md-1 pr-0">
                                                                <span class="text-secondary">{{ $i++ }})</span>
                                                            </div>
                                                            <div class="col-md-11 pl-0">
                                                                <span class="text-secondary">{{ $question->question }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-block card-task">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="task-detail">
                                                                    @foreach($question->options as $option)
                                                                        <div class="form-group">
                                                                            <div class="d-inline">
                                                                                <div class="row">
                                                                                    <div class="col-md-1 pr-0" style="padding-top: 3px;">
                                                                                        <input type="radio" name="answer[{{ $option->question_id }}]" value="{{ $option->id }}" id="option_{{ $option->question_id.'_'.$option->id }}">
                                                                                    </div>
                                                                                    <div class="col-md-11 pl-0">
                                                                                        <label for="option_{{ $option->question_id.'_'.$option->id }}" class="cr">{{ $option->option }}</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="row mt-4">
                                        <div class="offset-2 offset-md-2 offset-sm-0 col-md-8 col-sm-12">
                                            <div class="card card-border-c-blue">
                                                <div class="card-block card-task">
                                                    <div class="task-board">
                                                        <button  type="submit" class="btn btn-primary btn-lg float-right">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </form>
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
        var newDate = new Date("{{ date('M d, Y H:i:s', strtotime($resultUserAssessment->created_at)) }}");

        var d = new Date(newDate.getTime() + 15 * 60000);

        var countDownDate = new Date(d).getTime();

        // Update the count down every 1 second
        $( document ).ready(function() {
            var x = setInterval(function() {

                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                document.getElementById("timer").innerHTML = "<b>" + minutes + "</b>m : <b>" + seconds + "</b>s ";

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("timer").innerHTML = "TIME EXPIRED";
                    $("#form-assessment").submit();
                    return false;
                }

            }, 1000);
        });

    </script>


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
