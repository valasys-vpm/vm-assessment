@extends('layouts.master')

@section('stylesheet')
    @parent
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset('public/template/assets/plugins/data-tables/css/datatables.min.css')}}">
@append

@section('content')
    <section class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Assessment Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Assessment Management</a></li>
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
                                <!-- [ configuration table ] start -->
                                <div class="col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Assessments</h5>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-square btn-sm" onclick="addAssessment();"><i class="feather icon-plus"></i>New Assessment</button>
                                                <button type="button" class="btn btn-primary btn-square btn-sm" onclick="sendAssessmentResultBulk();"><i class="feather icon-plus"></i>New Assessment</button>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <div class="table-responsive">
                                                <table id="table-assessments" class="display table nowrap table-striped table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Date</th>
                                                        <th>No. of <br>Questions</th>
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
                                <!-- [ configuration table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modalAssessment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="modal-heading">Add new assessment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="modal-assessment-form">
                        <input type="hidden" name="assessment_id" id="assessment_id" value="">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="name">Assessment Name</label>
                                <input type="text" class="form-control btn-square" id="name" name="name" placeholder="Enter assessment name" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="date">Assessment Date</label>
                                <input type="date" class="form-control btn-square" id="date" name="date" required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label for="number_of_questions">Number of questions</label>
                                <input type="number" class="form-control btn-square" id="number_of_questions" name="number_of_questions" placeholder="Enter number of questions" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="status">Status</label>
                                <select class="form-control btn-square" id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    <option value="2">Completed</option>
                                </select>
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

    <input type="hidden" id="auth_user_id" value="{{ Auth::id() }}">
@endsection

@section('javascript')
    @parent
    <!-- datatable Js -->
    <script src="{{ asset('public/template/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('public/js/admin/assessment.js?='.time()) }}"></script>
@append


