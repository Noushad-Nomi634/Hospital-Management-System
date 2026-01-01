<!--start doctor sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="logo-icon">
            <img src="{{ URL::asset('build/images/bodylogo.png') }}" class="logo-img" alt="Logo">
        </div>
        <div class="logo-name flex-grow-1">
            <h5 class="mb-0">Body Experts</h5>
        </div>
        <div class="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <ul class="metismenu" id="doctor-sidenav">
            @auth('doctor')
                <!-- Dashboard -->
                 <!-- Dashboard -->
                <li>
                    <a href="{{ route('doctor.dashboard') }}">
                        <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <!-- Appointments -->
                <li class="has-arrow">
                    <a href="javascript:void(0)">
                        <div class="parent-icon"><i class="material-icons-outlined">assignment</i></div>
                        <div class="menu-title">Appointments</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('doctor.appointments.index') }}"><i class="material-icons-outlined">fact_check</i> All Appointments</a>
                        </li>
                        <li>
                            <a href="{{ route('doctor.appointments.create') }}"><i class="material-icons-outlined">add_circle</i> Book Appointment</a>
                        </li>
                    </ul>
                </li>

                <!-- Sessions -->
                <li class="has-arrow">
                    <a href="javascript:void(0)">
                        <div class="parent-icon"><i class="material-icons-outlined">event</i></div>
                        <div class="menu-title">Sessions</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('doctor.ongoing-sessions', 1) }}"><i class="material-icons-outlined">fact_check</i> Ongoing Sessions</a>
                        </li>
                        <li>
                            <a href="{{ route('doctor.ongoing-sessions', 2) }}"><i class="material-icons-outlined">history</i> Completed Sessions</a>
                        </li>
                    </ul>
                </li>

                <!-- Feedback -->
                <li class="has-arrow">
                    <a href="javascript:void(0)">
                        <div class="parent-icon"><i class="material-icons-outlined">feedback</i></div>
                        <div class="menu-title">Feedback</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('doctor.feedback.doctor-list') }}"><i class="material-icons-outlined">fact_check</i> Doctor Feedback</a>
                        </li>
                        <li>
                            <a href="{{ route('doctor.feedback.patient-list') }}"><i class="material-icons-outlined">history</i> Patient Feedback</a>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </div>
</aside>
<!--end doctor sidebar-->

@push('script')
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize MetisMenu
    var menu = document.getElementById("doctor-sidenav");
    if(menu && typeof MetisMenu !== "undefined"){
        if(window.doctorMetisMenu) window.doctorMetisMenu.dispose();
        window.doctorMetisMenu = new MetisMenu(menu, { toggle: true });
    }

    // Open only the active submenu based on current URL
    var currentUrl = window.location.href;

    menu.querySelectorAll('a').forEach(function(link){
        if(link.href === currentUrl){
            link.classList.add('mm-active'); // active link

            let parentLi = link.closest('li.has-arrow');
            if(parentLi){
                parentLi.classList.add('mm-active');
                let submenu = parentLi.querySelector('ul');
                if(submenu){
                    submenu.classList.add('mm-show'); // open only active submenu
                }
            }
        }
    });

    // Initialize SimpleBar for sidebar scrolling
    var sidebar = document.querySelector(".sidebar-wrapper[data-simplebar]");
    if(sidebar && typeof SimpleBar !== "undefined"){
        new SimpleBar(sidebar, { autoHide: false });
    }
});
</script>
@endpush
