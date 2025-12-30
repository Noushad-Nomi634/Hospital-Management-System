<!--start doctor sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="logo-icon">
            <img src="{{ URL::asset('build/images/bodylogo.png') }}" class="logo-img" alt="">
        </div>
        <div class="logo-name flex-grow-1">
            <h5 class="mb-0">Body Experts</h5>
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <ul class="metismenu" id="sidenav">

            @if(auth()->check() && auth()->user()->hasRole('doctor'))
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('doctor.dashboard') }}">
                        <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="{{ route('doctor.appointments.index') }}">
                        <div class="parent-icon"><i class="material-icons-outlined">assignment</i></div>
                        <div class="menu-title">Appointments</div>
                    </a>
                </li>

                <!-- Sessions -->
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="material-icons-outlined">event</i></div>
                        <div class="menu-title">Sessions</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('doctor.ongoing-sessions', 1) }}">
                                <i class="material-icons-outlined">fact_check</i> Ongoing Sessions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('doctor.ongoing-sessions', 2) }}">
                                <i class="material-icons-outlined">history</i> Completed Sessions
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Feedback -->
                <li class="{{ request()->is('feedback*') ? 'mm-active' : '' }}">
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="material-icons-outlined">feedback</i></div>
                        <div class="menu-title">Feedback</div>
                    </a>
                    <ul class="{{ request()->is('feedback*') ? 'mm-show' : '' }}">
                        <li class="{{ request()->is('feedback/doctor-list') ? 'mm-active' : '' }}">
                            <a href="{{ url('/feedback/doctor-list') }}">
                                <i class="material-icons-outlined">fact_check</i> Doctor Feedback
                            </a>
                        </li>
                        <li class="{{ request()->is('feedback/patient-list') ? 'mm-active' : '' }}">
                            <a href="{{ url('/feedback/patient-list') }}">
                                <i class="material-icons-outlined">history</i> Patient Feedback
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</aside>
<!--end doctor sidebar-->
