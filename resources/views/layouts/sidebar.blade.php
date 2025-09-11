<!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="{{ URL::asset('build/images/logo-icon.png') }}" class="logo-img" alt="">
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">Maxton</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
        <!--navigation-->
        <ul class="metismenu" id="sidenav">


 <!--navigation-->

          <li>
            <a href="javascript:;">
                @if(Auth::guard('doctor')->check())
                    <a href="{{ url('dr/dashboard') }}">
                @elseif(Auth::guard('web')->user())
                    <a href="{{ url('admin/dashboard') }}">
                @endif
                        {{-- hello just for testing  --}}
              <div class="parent-icon"><i class="material-icons-outlined">home</i>
              </div>
              <div class="menu-title">Dashboard</div>
            </a>
          </li>





          {{--My Code patient --}}

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
                    <div class="menu-title">Consultations</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/checkups') }}">
                        <i class="material-icons-outlined">fact_check</i> All Consultations
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/checkups/create') }}">
                        <i class="material-icons-outlined">add_circle</i> New Consultation
                    </a>
                    </li>
                </ul>
            </li>

            <!-- Prescription Menu -->
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                    <i class="material-icons-outlined">description</i>
                    </div>
                    <div class="menu-title">Prescriptions</div>
                </a>
                <ul>
                    <li>
                    <a href="{{ url('/prescriptions') }}">
                        <i class="material-icons-outlined">list</i> All Prescriptions
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/prescriptions/create') }}">
                        <i class="material-icons-outlined">note_add</i> New Prescription
                    </a>
                    </li>
                    <li>
                    <a href="{{ url('/treatments') }}">
                        <i class="material-icons-outlined">healing</i> Treatment / Sessions
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







{{--Doctor Widget--}}
<li>
  <a href="{{ url('/doctors') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Doctors</div>
  </a>
</li>

{{--Chekups--}}
<li>
  <a href="{{ url('/checkups') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Checkups</div>
  </a>
</li>

{{--treatment-sessions--}}
<li>
  <a href="{{ url('/treatment-sessions') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Treatment-Sessions</div>
  </a>
</li>




{{--session Table--}}
<li>
  <a href="{{ url('/sessions') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Sessions</div>
  </a>
</li>


{{--session Table--}}
<li>
  <a href="{{ url('/payments/outstandings') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Payments Outstandings</div>
  </a>
</li>

{{--General Setting--}}
<li>
  <a href="{{ url('/general-settings') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">General Settings</div>
  </a>
</li>

{{--Add Employes--}}
<li>
  <a href="{{ url('/employees') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Employes</div>
  </a>
</li>

{{--Salary Records--}}
<li>
  <a href="{{ url('/salaries') }}">
    <div class="parent-icon"><i class="material-icons-outlined">widgets</i></div>
    <div class="menu-title">Salary Records</div>
  </a>
</li>

         </ul>
        <!--end navigation-->
    </div>
  </aside>
<!--end sidebar-->
