@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
@append

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
                                        <h5 class="m-b-10">Assessments</h5>
                                        <div class="card-header-right mb-1" style="float: right;">
                                            {{-- <a href="{{ route('campaign') }}" class="btn btn-outline-dark btn-square btn-sm" style="font-weight: bold;"><i class="feather icon-arrow-left"></i>Back</a> --}}
                                        </div>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('admin.assessment.list') }}">Assessment Management</a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Assessment Details</a></li>
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
                                <!-- [ task-detail ] start -->
                                <div class="col-xl-3 col-lg-12 task-detail-right">
                                    <div class="card loction-user">
                                        <div class="card-header">
                                            <h5>Assessment Details</h5>
                                        </div>
                                        <div class="card-block p-0">
                                            <div class="row align-items-center justify-content-center">
                                                <div class="col">
                                                    <h6><span class="text-muted">Name: </span><span class="float-right">{{ $resultAssessment->name }}</span></h6>
                                                    <h6><span class="text-muted">Date: </span><span class="float-right">{{ $resultAssessment->date }}</span></h6>
                                                    <h6>
                                                    <span class="text-muted">Status: </span>
                                                    @switch($resultAssessment->status)
                                                        @case(0)
                                                        <span class="badge badge-danger m-1 float-right" style="padding: 5px 15px;">Inactive</span>
                                                        @break
                                                        @case(1)
                                                        <span class="badge badge-info m-1 float-right" style="padding: 5px 15px;">Active</span>
                                                        @break
                                                        @case(2)
                                                        <span class="badge badge-success m-1 float-right" style="padding: 5px 15px;">Completed</span>
                                                        @break
                                                    @endswitch
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-9 col-lg-12">

                                    <div class="card">
                                        <div class="card-header">

                                            <h5><i class="fas fa-chart-pie m-r-5"></i> Result</h5>

                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <span>
                                                        <button type="button" class="btn btn-primary btn-square btn-sm" onclick="addQuestion();"><i class="feather icon-plus"></i>New Question</button>
                                                    </span>
                                                    {{--
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="feather icon-more-vertical"></i>
                                                    </button>
                                                    <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                                        <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                                        <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                                        <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                                    </ul>
                                                    --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block">

                                            <div class="m-b-20">
                                                <div class="table-responsive">
                                                    <table id="table-assessment-result" class="display table nowrap table-striped table-hover">
                                                        <thead>
                                                        <tr class="text-uppercase">
                                                            <th class="text-center">User Name</th>
                                                            <th class="text-center">Attempted</th>
                                                            <th class="text-center">Marks Obtained</th>
                                                            <th class="text-center">Wrong</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="text-center text-muted">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- [ task-detail ] end -->
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12">

                                    <div class="card">
                                        <div class="card-header">

                                            <h5><i class="fas fa-chart-pie m-r-5"></i> Questions</h5>

                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <span>
                                                        <button type="button" class="btn btn-primary btn-square btn-sm" onclick="addQuestion();"><i class="feather icon-plus"></i>New Question</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-block">

                                            <div class="m-b-20">
                                                <div class="table-responsive">
                                                    <table id="table-questions" class="display table nowrap table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Category</th>
                                                                <th>Question</th>
                                                                <th>Options</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Created At</th>
                                                                <th class="text-center">Updated At</th>
                                                                <th class="text-center" style="width: 20%;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- [ task-detail ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalQuestion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="modal-heading">Add new question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="modal-question-form">
                        <input type="hidden" name="question_id" id="question_id" value="">
                        <input type="hidden" name="assessment_id" id="assessment_id" value="{{ $resultAssessment->id }}">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control btn-square" id="category_id" name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($resultCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="status">Status</label>
                                <select class="form-control btn-square" id="status" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="question">Question</label>
                                <input type="text" class="form-control btn-square" id="question" name="question" placeholder="Enter question" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label for="options">Options</label>
                            </div>

                            <div class="col-md-12">
                                <div id="options" class="row">

                                    <div id="div_option_1" class="form-group col-md-6 option">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="radio" name="answer" value="1" required checked>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="options[1]" placeholder="Options..." required>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <button type="button" class="btn btn-icon btn-outline-danger btn-sm btn_remove_option" style="width: 30px;height: 30px;padding: 0px;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="div_option_2" class="form-group col-md-6 option">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <input type="radio" name="answer" value="2" required>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="options[2]" placeholder="Options..." required>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <button type="button" class="btn btn-icon btn-outline-danger btn-sm btn_remove_option" style="width: 30px;height: 30px;padding: 0px;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <button type="button" class="btn btn-info btn-sm btn_add_option"> + Add Option </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-square btn-sm" data-dismiss="modal">Close</button>
                    <button id="modal-form-button-submit" type="button" class="btn btn-primary btn-square btn-sm">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('public/js/admin/question.js?='.time()) }}"></script>
@append
