<ul class="nav pcoded-inner-navbar">


    <li class="nav-item @if(Request::route()->getName() == 'user.dashboard') active @endif">
        <a href="{{ route('user.dashboard') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
            <span class="pcoded-mtext">Home</span>
        </a>
    </li>

    
    <li class="nav-item @if(Request::route()->getName() == 'user.dashboard') active @endif">
        <a href="{{ route('user.assessment.list') }}" class="nav-link">
            <span class="pcoded-micon"><i class="feather icon-list"></i></span>
            <span class="pcoded-mtext">My Assessments</span>
        </a>
    </li>
</ul>

