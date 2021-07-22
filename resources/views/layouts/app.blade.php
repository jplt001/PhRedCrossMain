<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Philippine Red Cross</title>
  <!-- Tell the browser to be responsive to screen width -->
  <link rel="icon" href="{{ URL::asset('img/favicon.ico') }}" type="image/x-icon" />

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/select2/select2.min.css')}}">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/dist/css/skins/_all-skins.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/iCheck/all.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/morris/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/datepicker/datepicker3.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ URL::asset('bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css')}}">
  <!-- Toastr styles -->
  <link rel="stylesheet" href="{{ URL::asset('css/toastr.min.css')}}">
  <!-- Custom styles -->
  <link rel="stylesheet" href="{{ URL::asset('css/arcenio.css')}}">
  @yield('add_css_here')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
  span.logo-rc {
    width: 30px;
    height: 30px;  
    position: absolute;
    left: -78px;
    top: 0px;
  }
</style>
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="{{URL::to('/home')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="{{ URL::asset('img/logo.png')}}" style='height:35px; width:35px;'></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ URL::asset('img/logo.png')}}" style='height:40px; width:40px;'> <b>Red</b>Cross</span>
    <!--   <span class="logo-mini">
        <img src="../public/img/logo.png" style='margin-left:95px;height:auto; width:45px;'>
      </span> -->
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->          
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                @if(Auth::user()->gender == 0)
                  <img src="{{ URL::asset('img/boy.png')}}" class="user-image" alt="User Image">
                @else
                  <img src="{{ URL::asset('img/girl.png')}}" class="user-image" alt="User Image">
                @endif
              <!-- <img src="{{ URL::asset('bower_components/AdminLTE/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image"> -->
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                @if(Auth::user()->gender == 0)
                  <img src="{{ URL::asset('img/boy.png')}}" class="img-circle" alt="User Image">
                @else
                  <img src="{{ URL::asset('img/girl.png')}}" class="img-circle" alt="User Image">
                @endif
                

                <p>
                  {{ Auth::user()->name }}
                  <small>Member since {{( date('M. Y', strtotime(Auth::user()->created_at)) )}}</small>
                </p>
              </li>              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                  Sign out
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
                  <!-- <a href="#" class="btn btn-default btn-flat">Sign out</a> -->
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          @if(Auth::user()->gender == 0)
            <img src="{{ URL::asset('img/boy.png')}}" class="img-circle" alt="User Image">
          @else
            <img src="{{ URL::asset('img/girl.png')}}" class="img-circle" alt="User Image">
          @endif
          <!-- <img src="{{ URL::asset('bower_components/AdminLTE/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image"> -->
        </div>
        <div class="pull-left info">

          <p>{{ Auth::user()->name }}</p>
          

          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
            @if(isset($side_active) && $side_active == 'dashboard')
                <li class="active"><a href="{{URL::to('/home')}}"><i class="fa fa-tachometer text-red"></i> <span>Dashboard</span></a></li>
            @else
                <li><a href="{{URL::to('/home')}}"><i class="fa fa-tachometer text-red"></i> <span>Dashboard</span></a></li>
            @endif
            <!-- Blood Referral -->
            @if( acl_roles(Auth::user()->user_type, 'blood_referral') )
              @if(isset($side_active) && $side_active == 'blood_referral')
                  <li class="active"><a href="{{ URL::to('/blood_referral')}}"><i class="fa fa-medkit text-red"></i> <span>Blood Referral</span></a></li>
              @else
                  <li><a href="{{ URL::to('/blood_referral')}}"><i class="fa fa-medkit text-red"></i> <span>Blood Referral</span></a></li>
              @endif
            @endif
            <!-- SEROLOGY -->
            @if( acl_roles(Auth::user()->user_type, 'serology') )
             @if(isset($side_active) && $side_active == 'serology')
                  <li class="active"><a href="{{ URL::to('/serology')}}"><i class="fa fa-medkit text-red"></i> <span> Serology</span></a></li>
              @else
                  <li><a href="{{ URL::to('/serology')}}"><i class="fa fa-medkit text-red"></i> <span>Serology</span></a></li>
              @endif
            @endif
            <!-- BLOOD DONOR REGISTER -->
            @if( acl_roles(Auth::user()->user_type, 'blood_register') )
              @if(isset($side_active) && $side_active == 'blood_register')
                  <li class="active"><a href="{{ URL::to('/blood_register')}}"><i class="fa fa-plus-square text-red"></i> <span>Blood Donor</span></a></li>
              @else
                  <li><a href="{{ URL::to('/blood_register')}}"><i class="fa fa-plus-square text-red"></i> <span>Blood Donor</span></a></li>
              @endif
            @endif
            <!-- BLOOD BANK -->
            @if( acl_roles(Auth::user()->user_type, 'bloodbank') )
              @if(isset($side_active) && $side_active == 'bloodbank')
                  <li class="active"><a href="{{ URL::to('/bloodbank')}}"><i class="fa  fa-university text-red"></i> <span>Blood Bank</span></a></li>
              @else
                  <li><a href="{{ URL::to('/bloodbank')}}"><i class="fa fa-university text-red"></i> <span>Blood Bank</span></a></li>
              @endif
            @endif
            <!-- FORECASTING -->
            <!-- @if( acl_roles(Auth::user()->user_type, 'forecast') )
              @if(isset($side_active) && $side_active == 'bloodbank')
                  <li class="active"><a href="{{ URL::to('/bloodbank')}}"><i class="fa  fa-university text-red"></i> <span>Blood Bank</span></a></li>
              @else
                  <li><a href="{{ URL::to('/bloodbank')}}"><i class="fa fa-university text-red"></i> <span>Blood Bank</span></a></li>
              @endif
            @endif -->
            @if( acl_roles(Auth::user()->user_type, 'forecast') )
              @if(isset($side_active) && $side_active == 'forecast')
                  <li class="treeview">
                        <a href="#">
                            <i class="fa fa-line-chart text-red"></i> <span>Forecasting</span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="active"><a href="{{ URL::asset('/forecast') }}"><i class="fa fa-circle-o text-red"></i> Forecast</a></li>
                            <li><a href="{{ URL::asset('/forecast') }}"><i class="fa fa-circle-o text-red"></i> Forecast History</a></li>
                        </ul>
                    </li>
              @else
                  <li class="treeview">
                      <a href="#">
                          <i class="fa fa-line-chart text-red"></i> <span>Forecasting</span>
                          <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                          </span>
                      </a>
                      <ul class="treeview-menu">
                          <li><a href="{{ URL::asset('/forecast') }}"><i class="fa fa-circle-o text-red"></i> Forecast</a></li>
                          <li><a href="{{ URL::asset('/forecast') }}"><i class="fa fa-circle-o text-red"></i> Forecast History</a></li>
                      </ul>
                  </li>
              @endif
            @endif

           
            <!-- REPORTS -->
         
           <li class="treeview">
              <a href="#">
                  <i class="fa fa-bar-chart text-red"></i> <span>Reports</span>
                  <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu">
                @if( acl_roles(Auth::user()->user_type, 'report_bloodbank') )
                <li @if($side_active == 'report_bloodbank') class="active" @endif ><a href="{{ URL::asset('/report/generateReferralReport') }}"><i class="fa fa-circle-o text-red"></i> Blood Referrral</a></li>
                @endif 
                @if( acl_roles(Auth::user()->user_type, 'report_bloodbank') )
                 <li><a href="{{ URL::asset('/report/blooddonor') }}"><i class="fa fa-circle-o text-red"></i> Blood Register</a></li>
                @endif 
                @if( acl_roles(Auth::user()->user_type, 'report_blood_donor') )
                 <li @if($side_active == 'report_blood_donor') class="active" @endif><a href="{{ URL::asset('/report/blooddonor') }}"><i class="fa fa-circle-o text-red"></i> Blood Register</a></li>
                @endif
                @if( acl_roles(Auth::user()->user_type, 'report_serology') )
                 <li @if($side_active == 'report_serology') class="active" @endif><a href="{{ URL::asset('/report/serology') }}"><i class="fa fa-circle-o text-red"></i> Serology</a></li>
                @endif
                <li class="{{ $side_active =='report_bloodbank' ? 'active' : '' }}"><a href="{{ URL::asset('/report/bloodbank') }}"><i class="fa fa-circle-o text-red"></i> Blood Bank</a></li>
              </ul>
          </li> 
         

            <!-- SET UP -->
            <li class="header">Set Up</li>
            <!-- USERS -->
            @if( acl_roles(Auth::user()->user_type, 'users') )
              @if(isset($side_active) && $side_active == 'user')
              <li class="active"><a href="{{URL::to('/users')}}"><i class="fa fa-users text-red"></i> <span>Users</span></a></li>
              @else
              <li ><a href="{{URL::to('/users')}}"><i class="fa fa-users text-red"></i> <span>Users</span></a></li>
              @endif
            @endif
            <!-- BRANCH -->
            @if( acl_roles(Auth::user()->user_type, 'branch') )              
               @if(isset($side_active) && $side_active == 'branch')
                <li class="active"><a href="{{URL::to('/branch')}}"><i class="fa fa-building text-red"></i> <span>Branch</span></a></li>
                    @else
                    <li ><a href="{{URL::to('/branch')}}"><i class="fa fa-building text-red"></i> <span>Branch</span></a></li>
                    
                @endif
            @endif
            
            <!-- HOSPITAL -->
            @if( acl_roles(Auth::user()->user_type, 'hospitals') )              
               @if(isset($side_active) && $side_active == 'hospital')
                <li class="active"><a href="{{URL::to('/hospital')}}"><i class="fa fa-hospital-o text-red" aria-hidden="true"></i> <span>Hospitals</span></a></li>
                    @else
                    <li ><a href="{{URL::to('/hospital')}}"><i class="fa fa-hospital-o text-red" aria-hidden="true"></i> <span>Hospitals</span></a></li>
                    
                @endif
            @endif
            
            <!-- PATIENT -->
            @if( acl_roles(Auth::user()->user_type, 'patient') )              
              @if(isset($side_active) && $side_active == 'patient')
                    <li class="active"><a href="{{ URL::to('/patient')}}"><i class="fa  fa-wheelchair text-red"></i> <span>Patient</span></a></li>
                @else
                    <li><a href="{{ URL::to('/patient')}}"><i class="fa fa-wheelchair text-red"></i> <span>Patient</span></a></li>
                @endif
            @endif
            
            <!-- ORGANIZATION -->
            @if( acl_roles(Auth::user()->user_type, 'organization') )              
              @if(isset($side_active) && $side_active == 'organization')
                    <li class="active"><a href="{{ URL::to('/organization')}}"><i class="fa  fa-building text-red"></i> <span> Organization</span></a></li>
                @else
                    <li><a href="{{ URL::to('/organization')}}"><i class="fa fa-building text-red"></i> <span> Organization</span></a></li>
                @endif
            @endif
            
            
            
            
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; {{ date('Y')}} <a href="http://www.redcross.org.ph/" target="_blank">Philippine Red Cross</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('bower_components/AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<!-- <script src="{{ URL::asset('bower_components/AdminLTE/plugins/morris/morris.min.js')}}"></script> -->
<!-- Sparkline -->
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/knob/jquery.knob.js')}}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{URL::asset('bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{URL::asset('bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{URL::asset('bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{URL::asset('bower_components/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{URL::asset('bower_components/AdminLTE/plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('bower_components/AdminLTE/dist/js/app.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{URL::asset('bower_components/AdminLTE/dist/js/pages/dashboard.js')}}"></script> -->

<script src="{{ URL::asset('bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/chartjs/Chart.min.js')}}"></script>
<script src="{{ URL::asset('bower_components/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ URL::asset('js/toastr.min.js')}}"></script>
<script src="{{ URL::asset('js/arcenio-chart-options.js')}}"></script>
<script src="{{ URL::asset('js/arcenio.js')}}"></script>
<script src="{{ URL::asset('js/dashboard.js')}}"></script>
<script src="{{ URL::asset('js/jplt.js')}}"></script>
@yield('custom_js')
<script type="text/javascript">
  $(function () {
    !isUserValid() && disableButtons();
    $(".select2").select2();    
  });
</script>
</body>
</html>
