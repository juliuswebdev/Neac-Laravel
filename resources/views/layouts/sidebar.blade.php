<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{route('home')}}" class="brand-link" style="text-align: center;">
        <img src="{{asset('storage/photos/logo.png')}}"
        alt="AdminLTE Logo"
        class="brand-image elevation-3"
        style="float: none;">
        <span class="brand-text font-weight-light">&nbsp;</span>
    </a>
    @php 
        $routename = Route::currentRouteName(); 
        $routeparent = explode('.', $routename)[0];
    @endphp
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="@if(auth()->user()->employee->image) /documents/{{auth()->user()->employee->image}} @else ../../dist/img/user2-160x160.jpg @endif">
            </div>
            <div class="info">
                <a href="{{ route('profile.show') }}" class="d-block">{{auth()->user()->first_name}}  {{auth()->user()->last_name}}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @can('applicant-list')    
                    <li class="nav-item has-treeview @if($routename == 'applicants.index' || $routename == 'applicants.create') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-user-nurse"></i>
                            <p>Applicants<i class="right fas fa-angle-left"></i></p>
                        </a> 
                        <ul class="nav nav-treeview">
                            @can('applicant-add')
                            <li class="nav-item @if($routename == 'applicants.create') active @endif">
                                <a href="{{ route('applicants.create') }}" class="nav-link"><p>Add Applicant</p></a>
                            </li>
                            @endcan
                            @can('applicant-list')
                            <li class="nav-item @if($routename == 'applicants.index') active @endif">
                                <a href="{{ route('applicants.index') }}" class="nav-link"><p>Applicants</p></a>
                            </li>
                            @endcan    
                        </ul>
                    </li>
                @endcan

                @can('employee-list')   
                <li class="nav-item has-treeview @if($routename == 'employees.index' || $routename == 'employees.create') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Employees<i class="right fas fa-angle-left"></i></p>
                    </a> 
                    <ul class="nav nav-treeview">
                        @can('employee-add')
                            <li class="nav-item @if($routename == 'employees.create') active @endif">
                                <a href="{{ route('employees.create') }}" class="nav-link"><p>Add Employee</p></a>
                            </li> 
                        @endcan
                        @can('employee-list') 
                            <li class="nav-item @if($routename == 'employees.index') active @endif">
                                <a href="{{ route('employees.index') }}" class="nav-link"><p>Employees</p></a>
                            </li> 
                        @endcan   
                    </ul>
                </li>
                @endcan

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon far fa-list-alt"></i>
                        <p>Services<i class="right fas fa-angle-left"></i></p>
                    </a> 
                    <ul class="nav nav-treeview">
                      
                        <li class="nav-item">
                            <a href="{{ route('services.create') }}" class="nav-link"><p>Add Service</p></a>
                        </li>    
                 
                        <li class="nav-item">
                            <a href="{{ route('services.index') }}" class="nav-link"><p>Services</p></a>
                        </li>
                   
                    </ul>
                </li>
                
                @can('transactions')
                <li class="nav-item has-treeview @if($routename == 'transactions.index') menu-open @endif">
                    <a href="{{ route('transactions.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Transactions</p>
                    </a> 
                </li>
                @endcan
                @can('reseller')
                <li class="nav-item has-treeview @if($routename == 'reseller.index') menu-open @endif">
                    <a href="{{ route('reseller.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Reseller</p>
                    </a> 
                </li>
                @endcan

                @can('testimonial-list')
                <li class="nav-item has-treeview @if($routename == 'testimonials.index') menu-open @endif">
                    <a href="{{ route('testimonials.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-star-half-alt"></i>
                        <p>Testimonials</p>
                    </a> 
                </li>
                @endcan

                @if (   auth()->user()->can('forms-list') ||
                        auth()->user()->can('application-status-list') ||
                        auth()->user()->can('applicant-profile-form') ||
                        auth()->user()->can('roles-permissions-list')  ||
                        auth()->user()->can('email-settings') 
                    )
                    <li class="nav-item has-treeview 
                        @if(
                            $routeparent == 'forms' ||
                            $routeparent == 'applications' ||
                            $routeparent == 'applicant-profile-form' ||
                            $routeparent == 'roles-permissions' ||
                            $routeparent == 'email-settings'
                        ) menu-open @endif
                        ">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Settings<i class="right fas fa-angle-left"></i></p>
                        </a> 
                        <ul class="nav nav-treeview">
                            @can('forms-list')
                                <li class="nav-item @if($routeparent == 'forms') active @endif">
                                    <a href="{{ route('forms.index') }}" class="nav-link"><p>Applicant Forms</p></a>
                                </li>
                            @endcan
                            @can('application-status-list')
                                <li class="nav-item @if($routeparent == 'applications') active @endif">
                                    <a href="{{ route('applications.index') }}" class="nav-link"><p>Application Status</p></a>
                                </li>
                            @endcan
                            @can('applicant-profile-form')
                            <li class="nav-item @if($routeparent == 'applicant-profile-form') active @endif">
                                <a href="{{ route('applicant-profile-form.index') }}" class="nav-link"><p>Applicant Profile Form</p></a>
                            </li> 
                            @endcan

                            <li class="nav-item">
                                <a href="{{ route('service-category.index') }}" class="nav-link"><p>Service Category</p></a>
                            </li> 

                            <li class="nav-item">
                                <a href="{{ route('currency.index') }}" class="nav-link"><p>Currency</p></a>
                            </li> 

                            @can('roles-permissions-list')
                                <li class="nav-item @if($routeparent == 'roles-permissions') active @endif">
                                    <a href="{{ route('roles-permissions.index') }}" class="nav-link"><p>Roles and Permissions</p></a>
                                </li> 
                            @endcan
                            @can('email-settings')
                                <li class="nav-item @if($routeparent == 'email-settings') active @endif">
                                    <a href="{{ route('email-settings.index') }}" class="nav-link"><p>Email Settings</p></a>
                                </li> 
                            @endcan
                        </ul>
                    </li>
                @endif
                <li class="nav-item has-treeview  @if($routeparent == 'profile') menu-open @endif">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Account<i class="right fas fa-angle-left"></i></p>
                    </a> 
                    <ul class="nav nav-treeview">
                        <li class="nav-item @if($routename == 'profile.show') active @endif">
                            <a href="{{ route('profile.show') }}" class="nav-link"><p>My Profile</p></a>
                        </li>  
                        <li class="nav-item @if($routename == 'profile.edit') active @endif">
                            <a href="{{ route('profile.edit') }}" class="nav-link"><p>Edit Profile</p></a>
                        </li>  
                        <li class="nav-item">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('exit').submit();" class="nav-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                            <form action="{{ route('logout') }}" method="POST" id="exit">@csrf</form>
                        </li>  
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>