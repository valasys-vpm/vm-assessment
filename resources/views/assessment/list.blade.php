@extends('assessment.layouts.master')


@section('style')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.css"/>
@append

@section('content')
    <div class="row" onmousedown="return false" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false">
        <form id="form-assessment" action="{{ route('user.assessment.submit') }}" method="post" class="col-md-12">
            @csrf

            <div id="data-container" class="row"></div>

            <div class="row mt-4">
                @php
                $i = 1;
                @endphp
                @foreach($resultQuestions as $key => $question_paper)
                    <div class="offset-2 offset-md-2 offset-sm-0 col-md-8 col-sm-12">
                        <div class="card card-border-c-blue">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-1">
                                        <span class="text-secondary">{{ $i++ }}</span>
                                    </div>
                                    <div class="col-md-11">
                                        <span class="text-secondary">{{ $question_paper->question->question }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-block card-task">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="task-detail">
                                            @foreach($question_paper->question->options as $option)
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
@endsection

@section('javascript')
    @parent
    <script src="https://pagination.js.org/dist/2.1.5/pagination.js"></script>
    {{--
    <script>
        // Set the date we're counting down to
        var year = '';
        var month = '{{ date('m', strtotime(session()->get('registered_user')->test_start_time)) }}';
        var day = '{{ date('d', strtotime(session()->get('registered_user')->test_start_time)) }}';
        var hours = '{{ date('H', strtotime(session()->get('registered_user')->test_start_time)) }}';
        var minutes = '{{ date('i', strtotime(session()->get('registered_user')->test_start_time)) }}';
        var seconds = '{{ date('s', strtotime(session()->get('registered_user')->test_start_time)) }}';

        //var newDate = new Date(year, month, day, hours, minutes, seconds);
        var newDate = new Date("{{ date('M d, Y H:i:s', strtotime(session()->get('registered_user')->test_start_time)) }}");

        var d = new Date(newDate.getTime() + {{ session()->get('resultDesignation')->test_duration }}*60000);

        var countDownDate = new Date(d).getTime();

        //var now = new Date("{{ date('M d, Y H:i:s') }}").getTime();
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
    --}}
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
