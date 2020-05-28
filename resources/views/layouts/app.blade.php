<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<meta charset="utf-8" />
	<title>{{ env('APP_NAME') }} Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
	<link rel="apple-touch-icon" href="pages/ico/60.png">
	<link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
	<link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
	<link rel="icon" type="image/x-icon" href="/favicon.ico" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-touch-fullscreen" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="default">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="{{ asset('dist/assets/plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/assets/plugins/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/assets/plugins/jquery-scrollbar/jquery.scrollbar.css')}}" rel="stylesheet" type="text/css" media="screen" />
	<link href="{{ asset('dist/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
	<link href="{{ asset('dist/assets/plugins/switchery/css/switchery.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
	<link href="{{ asset('dist/assets/plugins/nvd3/nv.d3.min.css')}}" rel="stylesheet" type="text/css" media="screen" />
	<link href="{{ asset('dist/assets/plugins/mapplic/css/mapplic.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/assets/plugins/rickshaw/rickshaw.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/pages/css/pages-icons.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dist/pages/css/themes/corporate.css')}}" rel="stylesheet" type="text/css" class="main-stylesheet"  />
	<link href="{{ asset('dist/assets/custom/css/jasny-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('dist/assets/custom/css/style.css') }}" rel="stylesheet" >
	@stack('styles')
</head>
<body class="fixed-header dashboard menu-pin menu-behind">
	<!-- BEGIN SIDEBPANEL-->
	<nav class="page-sidebar" data-pages="sidebar">
		<!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
		<div class="sidebar-overlay-slide from-top" id="appMenu">
			<div class="row">
				<div class="col-xs-6 no-padding">
					<a href="#" class="p-l-40"><img src="{{ asset('dist/assets/img/demo/social_app.svg')}}" alt="socail">
					</a>
				</div>
				<div class="col-xs-6 no-padding">
					<a href="#" class="p-l-10"><img src="{{ asset('dist/assets/img/demo/email_app.svg')}}" alt="socail">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6 m-t-20 no-padding">
					<a href="#" class="p-l-40"><img src="{{ asset('dist/assets/img/demo/calendar_app.svg')}}" alt="socail">
					</a>
				</div>
				<div class="col-xs-6 m-t-20 no-padding">
					<a href="#" class="p-l-10"><img src="{{ asset('dist/assets/img/demo/add_more.svg')}}" alt="socail">
					</a>
				</div>
			</div>
		</div>
		<!-- END SIDEBAR MENU TOP TRAY CONTENT-->
		<!-- BEGIN SIDEBAR MENU HEADER-->
		<div class="sidebar-header">
			<img src="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" alt="logo" class="brand" data-src="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" data-src-retina="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" width="78" height="22">
			<div class="sidebar-header-controls">
				<button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
				</button>
				<button type="button" class="btn btn-link d-lg-inline-block d-xlg-inline-block d-md-inline-block d-sm-none d-none" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
				</button>
			</div>
		</div>
		<!-- END SIDEBAR MENU HEADER-->
		<!-- START SIDEBAR MENU -->
		<div class="sidebar-menu">
			<!-- BEGIN SIDEBAR MENU ITEMS-->
			<ul class="menu-items">
				<li class="m-t-30 {{ request()->is('/') ? ' active' : '' }}">
					<a href="{{ route('home.index') }}"><span class="title">Dashboard</span></a>
					<span class="icon-thumbnail"><i class="pg-home"></i></span>
				</li>
				<li class="{{ request()->segment(1) == 'project' ? 'active' : '' }}">
					<a href="{{ route('project.index') }}"><span class="title">Projects</span></a>
					<span class="icon-thumbnail"><i class="pg-grid"></i></span>
				</li>
				<li class="{{ request()->segment(1) == 'task' ? 'active' : '' }}">
					<a href="{{ route('task.index') }}"><span class="title">Task</span></a>
					<span class="icon-thumbnail"><i class="pg-note"></i></span>
				</li>
				<!-- <li class="{{ request()->segment(1) == 'calendar' ? 'active' : '' }}">
					<a href="{{ route('calendar.index') }}"><span class="title">Calendar</span></a>
					<span class="icon-thumbnail"><i class="pg-calender"></i></span>
				</li>
				<li class="{{ request()->segment(1) == 'dropbox' ? 'active' : '' }}">
					<a href="{{ route('dropbox.index') }}"><span class="title">Assets</span></a>
					<span class="icon-thumbnail"><i class="fa fa-dropbox"></i></span>
				</li> -->

			</ul>
			<div class="clearfix"></div>
		</div>
		<!-- END SIDEBAR MENU -->
	</nav>
	<!-- END SIDEBAR -->
	<!-- END SIDEBPANEL-->
	<!-- START PAGE-CONTAINER -->
	<div class="page-container ">
		<!-- START HEADER -->
		<div class="header ">
			<!-- START MOBILE SIDEBAR TOGGLE -->
			<a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
			</a>
			<!-- END MOBILE SIDEBAR TOGGLE -->
			<div class="">
				<div class="brand inline m-l-10">
					<img src="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" alt="logo" data-src="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" data-src-retina="{{ asset('dist/assets/custom/'. env('APP_LOGO')) }}" width="170">
				</div>
				<!-- START NOTIFICATION LIST -->
				<ul class="d-lg-inline-block d-none notification-list no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
					<li class="p-r-10 inline">
						<div class="dropdown">
							<a href="javascript:;" id="notification-center" class="header-icon pg pg-world" data-toggle="dropdown">
								@if($navbar_notifications_unseen_count)
									<span class="bubble notification-count">{{ $navbar_notifications_unseen_count }}</span>
								@endif
							</a>

							<div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="notification-center">
								<div class="notification-panel">
									<div class="notification-body scrollable">
										@foreach($navbar_notifications as $navbar_notification)
										<div class="notification-item clearfix" data-notification-id="{{ $navbar_notification->id }}">
											<div class="heading">
												<a href="#" class="pull-left">
													<span class="notification-text {{ $navbar_notification->seen ? '' : 'bold' }}">{{ $navbar_notification->notification->detail }}</span>
													<span class="time">{{ $navbar_notification->created_at }}</span>
												</a>
											</div>

											<div class="option">
												<a href="#" class="mark mark-seen" data-notification-id="{{ $navbar_notification->id }}" {!! $navbar_notification->seen ? 'style="color: green;"' : '' !!}></a>
											</div>
										</div>
										@endforeach
									</div>

									<div class="notification-footer text-center">
										<a href="#" class="notification-mark-seen-all">Read all notifications</a>
									</div>
								</div>
							</div>
						</div>
					</li>
				</ul>
			</div>
			
			<div class="d-flex align-items-center">
				<!-- START User Info-->
				<div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
					<span class="semi-bold">{{ request()->user()->name }}</span>
				</div>
				<div class="dropdown pull-right d-lg-block d-none">
					<button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="thumbnail-wrapper d32 circular inline">
							<img src="{{ request()->user()->profilePicture() }}"  width="32" height="32">
						</span>
					</button>
					<div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
						@if(request()->user()->role == 1)
						<a href="{{ route('user.index') }}" class="dropdown-item"><i class="pg-settings_small"></i> User Management</a>
						@endif
						<a href="{{ route('user.edit', [request()->user()->id]) }}" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
						<a href="{{ route('auth.logout') }}" class="clearfix bg-master-lighter dropdown-item">
							<span class="pull-left">Logout</span>
							<span class="pull-right"><i class="pg-power"></i></span>
						</a>
					</div>
				</div>
				<!-- END User Info-->
			</div>
		</div>
		<!-- END HEADER -->
		<!-- START PAGE CONTENT WRAPPER -->
		@include('layouts.partials.alert')
		@yield('content')
		<!-- END PAGE CONTENT -->
	</div>
	<!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTAINER -->

<!-- BEGIN VENDOR JS -->
<script src="{{ asset('dist/assets/plugins/pace/pace.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery/jquery-3.2.1.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/modernizr.custom.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/popper/umd/popper.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery/jquery-easy.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-unveil/jquery.unveil.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-ios-list/jquery.ioslist.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/jquery-actual/jquery.actual.min.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/classie/classie.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/switchery/js/switchery.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/lib/d3.v3.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/nv.d3.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/utils.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/tooltip.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/interactiveLayer.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/models/axis.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/models/line.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/nvd3/src/models/lineWithFocusChart.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/mapplic/js/hammer.min.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/mapplic/js/jquery.mousewheel.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/mapplic/js/mapplic.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/rickshaw/rickshaw.min.js')}}"></script>
<script src="{{ asset('dist/assets/plugins/jquery-sparkline/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/plugins/skycons/skycons.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/pages/js/pages.js')}}"></script>
<script src="{{ asset('dist/assets/custom/js/jasny-bootstrap.min.js') }}"></script>
<!-- END CORE TEMPLATE JS -->
<script src="{{ asset('dist/assets/js/scripts.js')}}" type="text/javascript"></script>
<script src="{{ asset('dist/assets/custom/chosen/chosen.jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
<script src="{{ asset('dist/assets/custom/js/custom.js')}}" type="text/javascript"></script>

@stack('scripts')
</body>
</html>