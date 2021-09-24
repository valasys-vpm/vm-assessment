@extends('layouts.master')

@section('title', 'Dashboard | ')

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
                                        <h5 class="m-b-10">Dashboard</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i
                                                    class="feather icon-home"></i></a></li>
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
                                            <h5>Hello {{ Auth::user()->first_name }}</h5>
                                        </div>
                                        <div class="card-block">
                                            @if(isset($resultUserAssessment) && !empty($resultUserAssessment))
                                                @if(!$resultUserAssessment->attempted)
                                                <button onclick="window.location.href='{{ route('user.assessment.live', base64_encode($resultAssessment->id)) }}'" type="button" class="btn btn-outline-success btn-lg" title="" data-toggle="tooltip" data-original-title="Click to Continue Assessment">Continue Assessment</button>
                                                @else
                                                    <h5>Next Assessment on - {{ date('d/M/Y', strtotime('+7 day')) }}...</h5>
                                                @endif
                                            @elseif(isset($resultAssessment) && !empty($resultAssessment))
                                                <button onclick="window.location.href='{{ route('user.assessment.start_assessment', base64_encode($resultAssessment->id)) }}'" type="button" class="btn btn-outline-success btn-lg" title="" data-toggle="tooltip" data-original-title="Click to Start Test">Start Assessment</button>
                                            @endif
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
