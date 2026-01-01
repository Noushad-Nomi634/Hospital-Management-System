<!--start sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true" id="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="logo-icon">
            <img src="{{ URL::asset('build/images/bodylogo.png') }}" class="logo-img" alt="Logo">
        </div>
        <div class="logo-name flex-grow-1">
            <h5 class="mb-0">Body Experts</h5>
        </div>
        <div class="sidebar-close" id="sidebar-close">
            <span class="material-icons-outlined">close</span>
        </div>
    </div>

    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">

            <!-- ================= Admin Menu ================= -->
            @role('admin','web')
            <li>
                <a href="{{ url('admin/dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <!-- Patients -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">person</i></div>
                    <div class="menu-title">Patients</div>
                </a>
                <ul>
                    <li><a href="{{ url('/patients') }}"><i class="material-icons-outlined">list</i>All Patients</a></li>
                    <li><a href="{{ url('/patients/create') }}"><i class="material-icons-outlined">add</i>Add New Patient</a></li>
                </ul>
            </li>

            <!-- Doctors -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">medical_services</i></div>
                    <div class="menu-title">Doctors</div>
                </a>
                <ul>
                    <li><a href="{{ url('/doctors') }}"><i class="material-icons-outlined">list</i>All Doctors</a></li>
                    <li><a href="{{ url('/doctors/create') }}"><i class="material-icons-outlined">person_add</i>Add New Doctor</a></li>
                </ul>
            </li>

            <!-- Checkups / Appointments -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">assignment</i></div>
                    <div class="menu-title">Appointments</div>
                </a>
                <ul>
                    <li><a href="{{ url('/checkups') }}"><i class="material-icons-outlined">fact_check</i>All Appointments</a></li>
                    <li><a href="{{ url('/checkups/create') }}"><i class="material-icons-outlined">add_circle</i>Book Appointment</a></li>
                </ul>
            </li>

            <!-- Dr Consultations -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">local_hospital</i></div>
                    <div class="menu-title">Dr Consultations</div>
                </a>
                <ul>
                    <li><a href="{{ url('doctor-consultations/0') }}"><i class="material-icons-outlined">medical_information</i>Dr Checkup</a></li>
                    <li><a href="{{ url('/doctor-consultations/1') }}"><i class="material-icons-outlined">history</i>Completed Consultations</a></li>
                </ul>
            </li>

            <!-- Enrollments -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">school</i></div>
                    <div class="menu-title">Enrollments</div>
                </a>
                <ul>
                    <li><a href="{{ url('/enrollments/0') }}"><i class="material-icons-outlined">fact_check</i>Pending Enrollments</a></li>
                    <li><a href="{{ url('/enrollments/1') }}"><i class="material-icons-outlined">fact_check</i>Completed Enrollments</a></li>
                </ul>
            </li>

            <!-- Sessions -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">event</i></div>
                    <div class="menu-title">Sessions</div>
                </a>
                <ul>
                    <li><a href="{{ url('/ongoing-sessions/1') }}"><i class="material-icons-outlined">fact_check</i>Ongoing Sessions</a></li>
                    <li><a href="{{ url('/ongoing-sessions/2') }}"><i class="material-icons-outlined">history</i>Completed Sessions</a></li>
                </ul>
            </li>

            <!-- Feedback -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">feedback</i></div>
                    <div class="menu-title">Feedback</div>
                </a>
                <ul>
                    <li><a href="{{ url('/feedback/doctor-list') }}"><i class="material-icons-outlined">fact_check</i>Doctor Feedback</a></li>
                    <li><a href="{{ url('/feedback/patient-list') }}"><i class="material-icons-outlined">history</i>Patient Feedback</a></li>
                </ul>
            </li>

            <!-- Other Admin Menus (Payments, Employees, Expenses, etc.) -->
            <!-- (Ye aapka existing code hi rehne do, unchanged) -->

            @endrole

            <!-- ================= Doctor Menu ================= -->
            @auth('doctor')
            <li>
                <a href="{{ route('doctor.dashboard') }}">
                    <div class="parent-icon"><i class="material-icons-outlined">home</i></div>
                    <div class="menu-title">Doctor Dashboard</div>
                </a>
            </li>

            <!-- Doctor Appointments -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">assignment</i></div>
                    <div class="menu-title">Appointments</div>
                </a>
                <ul>
                    <li><a href="{{ route('doctor.appointments.index') }}"><i class="material-icons-outlined">fact_check</i>All Appointments</a></li>
                    <li><a href="{{ route('doctor.appointments.create') }}"><i class="material-icons-outlined">add_circle</i>Book Appointment</a></li>
                </ul>
            </li>

            <!-- Doctor Sessions -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">event</i></div>
                    <div class="menu-title">Sessions</div>
                </a>
                <ul>
                    <li><a href="{{ route('doctor.ongoing-sessions', 1) }}"><i class="material-icons-outlined">fact_check</i>Ongoing Sessions</a></li>
                    <li><a href="{{ route('doctor.ongoing-sessions', 2) }}"><i class="material-icons-outlined">history</i>Completed Sessions</a></li>
                </ul>
            </li>

            <!-- Doctor Feedback -->
            <li class="has-arrow">
                <a href="javascript:void(0)">
                    <div class="parent-icon"><i class="material-icons-outlined">feedback</i></div>
                    <div class="menu-title">Feedback</div>
                </a>
                <ul>
                    <li><a href="{{ route('doctor.feedback.doctor-list') }}"><i class="material-icons-outlined">fact_check</i>Doctor Feedback</a></li>
                    <li><a href="{{ route('doctor.feedback.patient-list') }}"><i class="material-icons-outlined">history</i>Patient Feedback</a></li>
                </ul>
            </li>
            @endauth

        </ul>
        <!--end navigation-->
    </div>
</aside>
<!--end sidebar-->

@push('script')
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize MetisMenu
    if(typeof MetisMenu !== "undefined"){
        new MetisMenu("#sidenav", { toggle: true });
    }

    // Highlight active link
    var currentPath = window.location.pathname;
    document.querySelectorAll("#sidenav a").forEach(function(link){
        var linkPath = new URL(link.href).pathname;

        if(currentPath === linkPath || currentPath.startsWith(linkPath + '/')){
            link.classList.add('mm-active');
            var parentLi = link.closest('li.has-arrow');
            if(parentLi) parentLi.classList.add('mm-active');
        }
    });

    // Initialize SimpleBar
    if(typeof SimpleBar !== "undefined"){
        new SimpleBar(document.querySelector(".sidebar-wrapper[data-simplebar]"), { autoHide: false });
    }

    // Sidebar close toggle
    var closeBtn = document.getElementById("sidebar-close");
    closeBtn.addEventListener("click", function(){
        document.getElementById("sidebar-wrapper").classList.toggle("sidebar-collapsed");
    });
});
</script>

<style>
.sidebar-wrapper.sidebar-collapsed {
    transform: translateX(-260px);
    transition: transform 0.3s ease;
}
</style>
@endpush
