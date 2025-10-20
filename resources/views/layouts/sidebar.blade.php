<!--start sidebar-->
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
        <!--navigation-->
        <ul class="metismenu" id="sidenav">


 <!--navigation-->

         @role('admin','web')
          <li>
            <a href="{{ url('admin/dashboard') }}">
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Dashboard</div>
            </a>
          </li>

          <li>
            <a class="has-arrow" href="javascript:;">
              <div class="parent-icon"><i class="material-icons-outlined">person</i>
              </div>
              <div class="menu-title">Patients</div>
            </a>
            <ul>
              <li><a href="{{ url('/patients') }}"><i class="material-icons-outlined">list</i>All Patients</a>
              </li>
              <li><a href="{{ url('/patients/create') }}"><i class="material-icons-outlined">add</i>Add New Patient</a>
              </li>

            </ul>
          </li>

          <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon">
                <i class="material-icons-outlined">medical_services</i>
                </div>
                <div class="menu-title">Doctors</div>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/doctors') }}">
                        <i class="material-icons-outlined">list</i> All Doctors
                    </a>
                </li>
                <li>
                    <a href="{{ url('/doctors/create') }}">
                        <i class="material-icons-outlined">person_add</i> Add New Doctor
                    </a>
                </li>
            </ul>
            </li>

             <!-- Checkups Menu -->
             <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">assignment</i>
                    </div>
                    <div class="menu-title">Appointments</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/checkups') }}">
                        <i class="material-icons-outlined">fact_check</i> All Appointments
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/checkups/create') }}">
                        <i class="material-icons-outlined">add_circle</i>  Book Appointment
                    </a>
                    </li>
                </ul>
            </li>

            <!-- Doctor Consultation Checkups -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">local_hospital</i>
                    </div>
                    <div class="menu-title">Dr Consultations</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('doctor-consultations/0') }}">
                        <i class="material-icons-outlined">medical_information</i> Dr Checkup
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/doctor-consultations/1') }}">
                        <i class="material-icons-outlined">history</i> Completed Consultations
                    </a>
                    </li>
                </ul>
            </li>

             <!-- Enrollments -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">school</i>
                    </div>
                    <div class="menu-title">Enrollments</div>
                </a>
                <ul>
                     <li>
                    <a href="{{ url('/enrollments/0') }}">
                        <i class="material-icons-outlined">fact_check</i> Pending Enrollments
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/enrollments/1') }}">
                        <i class="material-icons-outlined">fact_check</i> Completed Enrollments
                    </a>
                    </li>
                    <li>
                </ul>
            </li>

            <!--  Sessions -->

            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">event</i>
                    </div>
                    <div class="menu-title">Sessions</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/ongoing-sessions/1') }}">
                        <i class="material-icons-outlined">fact_check</i> Ongoing Sessions
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/ongoing-sessions/2') }}">
                        <i class="material-icons-outlined">history</i> Completed Sessions
                    </a>
                    </li>
                </ul>
            </li>

            <!-- Payments Menu -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                        <i class="material-icons-outlined">account_balance_wallet</i>
                    </div>
                    <div class="menu-title">Payments</div>
                </a>
                <ul>
                    <li>
                        <a href="{{ url('/payments/outstanding-invoices') }}">
                            <i class="material-icons-outlined">receipt_long</i> Outstanding Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/payments/completed-invoices') }}">
                            <i class="material-icons-outlined">task_alt</i> Completed Invoices
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/payments/receivable') }}">
                            <i class="material-icons-outlined">payments</i> Payment Receivable
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/payments/return-payments') }}">
                            <i class="material-icons-outlined">undo</i> Payment Returns
                        </a>
                    </li>
                </ul>
            </li>




            <!-- Accounts Menu -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">badge</i>
                    </div>
                    <div class="menu-title">Employees</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/employees') }}">
                        <i class="material-icons-outlined">group</i> All Employees
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/employees/create') }}">
                        <i class="material-icons-outlined">person_add</i> Add New Employee
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/employees/salaries') }}">
                        <i class="material-icons-outlined">attach_money</i> Salaries
                    </a>
                    </li>
                </ul>
            </li>

            <!-- Expenses Management -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">money_off</i>
                    </div>
                    <div class="menu-title">Expenses</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/expenses/types') }}">
                        <i class="material-icons-outlined">category</i> Expense Types
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/expenses/create') }}">
                        <i class="material-icons-outlined">add_circle</i> Create Expense
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/expenses') }}">
                        <i class="material-icons-outlined">visibility</i> View Expenses
                    </a>
                    </li>
                </ul>
            </li>

             <!-- General Settings Menu -->
             <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">settings</i>
                    </div>
                    <div class="menu-title">General Settings</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/branches') }}">
                        <i class="material-icons-outlined">store</i> Branches
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/banks') }}">
                        <i class="material-icons-outlined">account_balance_wallet</i> Banks
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/users') }}">
                        <i class="material-icons-outlined">group</i> Users
                    </a>
                    </li>
                </ul>
            </li>

            {{--session Table--}}
            <li>
                <a href="{{ url('/payments/outstandings') }}">
                <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
                <div class="menu-title">Payments Outstandings</div>
                </a>
            </li>

            {{--Salary Records--}}
            <li>
                <a href="{{ url('/salaries') }}">
                <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
                <div class="menu-title">Salary Records</div>
                </a>
            </li>

          @endrole
{{-- ==================Docter Menu==================== --}}
          @auth('doctor')
          <li>
            <a href="{{ url('doctor/dashboard') }}">
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Doctor Dashboard</div>
            </a>
          </li>
          @endauth

         </ul>
        <!--end navigation-->
    </div>
  </aside>
<!--end sidebar-->
