@extends('layouts.master')

@section('title', 'Assessment | View Details')

@section('content')
    <div class="pcoded-main-container">
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
                                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('user.assessment.list') }}">My Assessments</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">View Assessment Details</a></li>
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
                                            <h5>Assessment - {{ date('d/M/Y', strtotime($resultUserAssessment->assessment->date)) }} | Result</h5>
                                        </div>
                                        <div class="card-block">
                                            <h4>Marks Obtained: {{ $resultUserAssessment->marks_obtained }}</h4>
                                            <h4>Total Questions: {{ $resultUserAssessment->assessment->number_of_questions }}</h4>
                                            <h4>Attempted: {{ $resultUserAssessment->attempted }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" onmousedown="return false" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false">
                                <form class="col-md-12">
                                    @csrf
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
                                                                                        @php
                                                                                        $is_checked = '';
                                                                                        $text_class = '';
                                                                                        if(!empty($resultUserAssessment->answer_given)) {
                                                                                            foreach(json_decode($resultUserAssessment->answer_given) as $question_id => $option_id) {
                                                                                                if($question->id == $question_id && $option->id == $option_id) {
                                                                                                    $is_checked = 'checked';
                                                                                                }
                                                                                            }
                                                                                        }

                                                                                        if($option->is_answer) {
                                                                                            $text_class = 'text-success';
                                                                                        } elseif($is_checked == 'checked') {
                                                                                            $text_class = 'text-danger';
                                                                                        }
                                                                                        @endphp
                                                                                        <input type="radio" disabled {{ $is_checked }}>
                                                                                    </div>
                                                                                    <div class="col-md-11 pl-0">
                                                                                        <label for="option_{{ $option->question_id.'_'.$option->id }}" class="cr {{ $text_class }}">{{ $option->option }}</label>
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
