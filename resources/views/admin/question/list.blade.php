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
                                        <h5 class="m-b-10">Question Management</h5>
                                    </div>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">Question Management</a></li>
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
                                            <h5>Questions</h5>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-square btn-sm" onclick="addQuestion();"><i class="feather icon-plus"></i>New Question</button>
                                            </div>
                                        </div>
                                        <div class="card-block">
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
                                <!-- [ configuration table ] end -->
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div id="div-input-date" class="col-md-4 form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control btn-square" id="date" name="date" placeholder="">
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


