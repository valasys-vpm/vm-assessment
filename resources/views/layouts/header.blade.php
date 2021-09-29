<header class="navbar pcoded-header navbar-expand-lg navbar-light">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="{{route($module->route_name)}}"><span></span></a>
        <a href="#" class="b-brand">
            <div class="b-bg">
                <i class="feather icon-trending-up"></i>
            </div>
            <span class="b-title">VM - Assessment</span>
        </a>
    </div>
    <a class="mobile-menu" id="mobile-header" href="#!">
        <i class="feather icon-more-horizontal"></i>
    </a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li><span class="text-secondary"><small>Version 1.0</small></span></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon feather icon-settings"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="{{ asset('public/template') }}/assets/images/user/avatar-2.jpg" class="img-radius" alt="User-Profile-Image">
                            <span>{{Auth::user()->email}}</span>
                            <a href="{{ route('logout') }}" class="dud-logout" title="Logout">
                                <i class="feather icon-log-out"></i>
                            </a>
                        </div>
                        <ul class="pro-body">
                            <li><a href="javascript:void(0)" onclick="changePassword();" class="dropdown-item"><i class="fas fa-key"></i> Change Password</a></li>
                            <li><a href="{{ route('logout') }}" class="dropdown-item"><i class="feather icon-log-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>

<div id="modalChangePassword" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="modal-heading">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="modal-passwordChange-form">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control btn-square" id="password" name="password" placeholder="Enter Password" required>
                            </div>
                            <div class="col-md-12 form-group">
                            <label for="confirmPassword">Confirm Password</label>
                                <input type="password" class="form-control btn-square" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-square btn-sm" data-dismiss="modal">Close</button>
                    <button id="modal-chagepassword-button-submit" type="button" class="btn btn-primary btn-square btn-sm">Save</button>
                </div>
            </div>
        </div>
    </div>
