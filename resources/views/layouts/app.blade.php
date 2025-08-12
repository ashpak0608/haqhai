<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		

		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta charset="utf-8" />
		<meta name="description" content="HAQHAI" />
		<meta name="keywords" content="HAQHAI" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="HAQHAI" />
		<meta property="og:url" content="{{ config('constants.SITE_URL') }}" />
		<meta property="og:site_name" content="HAQHAI" />

        <link rel="shortcut icon" href="<?php echo url('public/assets/media/logos/favicon.png')?>">

		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts(mandatory for all pages)-->

		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')?>">
		<link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/plugins/custom/datatables/datatables.bundle.css')?>">
		<!--end::Vendor Stylesheets(used for this page only)-->

		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/plugins/global/plugins.bundle.css')?>">
        <link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/css/style.bundle.css')?>">
		<!--end::Global Stylesheets Bundle(mandatory for all pages)-->

		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script type="text/javascript" src="<?php echo url('public/assets/plugins/global/plugins.bundle.js')?>"></script>
		<!--end::Global Javascript Bundle(mandatory for all pages)-->

		<!--begin::Custom Stylesheets(created for common css)-->
		<!-- <link rel="stylesheet" type="text/css" href="</?php echo url('public/assets/css/custom.css')?>"> -->
		<!--end::Custom Stylesheets(created for common css)-->
		<script src="{{ url('public/validation/common.js')}}"></script>
		<script src="{{ url('public/validation/jquery.validate.min.js')}}"></script>
		<script> var SITE_URL = "<?php echo config('constants.SITE_URL');?>/"; </script>
		<script> var ASSETS = "<?php echo config('constants.ASSETS');?>/"; </script>
		<script> var ROOT_DIR = "<?php echo config('constants.ROOT_DIR');?>/"; </script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true" data-kt-app-aside-push-footer="true" class="app-default">
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header d-flex flex-column flex-stack">
					<!--begin::Header main-->
					<div class="d-flex flex-stack flex-grow-1">
						<div class="app-header-logo d-flex align-items-center ps-lg-12" id="kt_app_header_logo">
							<!--begin::Sidebar toggle-->
							<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon bg-body btn-color-gray-500 btn-active-color-primary w-30px h-30px ms-n2 me-4 d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
								<i class="ki-outline ki-abstract-14 fs-3 mt-1"></i>
							</div>
							<!--end::Sidebar toggle-->
							<!--begin::Sidebar mobile toggle-->
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-outline ki-abstract-14 fs-2"></i>
							</div>
							<!--end::Sidebar mobile toggle-->
							<!--begin::Logo-->
							<a href="<?php echo url('/');?>" class="app-sidebar-logo">
								<img alt="Logo" src="<?php echo url('public/assets/media/logos/logo.png')?>" class="h-50px theme-light-show" />
								<img alt="Logo" src="<?php echo url('public/assets/media/logos/logo.png')?>" class="h-50px theme-dark-show" />
							</a>
							<!--end::Logo-->
						</div>
						<!--begin::Navbar-->
						<div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
							<!--begin::User menu-->
							<div class="app-navbar-item ms-2 ms-lg-6" id="kt_header_user_menu_toggle">
								<!--begin::Menu wrapper-->
								<div class="cursor-pointer symbol symbol-circle symbol-30px symbol-lg-45px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
									<img src="<?php echo url('public/assets/media/avatars/300-1.jpg')?>" alt="user" />
								</div>
								<!--begin::User account menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content d-flex align-items-center px-3">
											<!--begin::Avatar-->
											<div class="symbol symbol-50px me-5">
												<img alt="Logo" src="<?php echo url('public/assets/media/avatars/300-1.jpg')?>" />
											</div>
											<!--end::Avatar-->
											<!--begin::Username-->
											<div class="d-flex flex-column">
												<div class="fw-bold d-flex align-items-center fs-5">{{Session::get('full_name')}}
												<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span></div>
											</div>
											<!--end::Username-->
										</div>
									</div>
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
										<a href="#" class="menu-link px-5">
											<span class="menu-title position-relative">Mode 
											<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
												<i class="ki-outline ki-night-day theme-light-show fs-2"></i>
												<i class="ki-outline ki-moon theme-dark-show fs-2"></i>
											</span></span>
										</a>
										<!--begin::Menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-night-day fs-2"></i>
													</span>
													<span class="menu-title">Light</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-moon fs-2"></i>
													</span>
													<span class="menu-title">Dark</span>
												</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3 my-0">
												<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
													<span class="menu-icon" data-kt-element="icon">
														<i class="ki-outline ki-screen fs-2"></i>
													</span>
													<span class="menu-title">System</span>
												</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="<?php echo url('profile');?>" class="menu-link px-5">My Profile</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5 my-1">
										<a href="<?php echo url('change-password');?>" class="menu-link px-5">Change Password</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="<?php echo url('logout');?>" class="menu-link px-5">Sign Out</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::User account menu-->
								<!--end::Menu wrapper-->
							</div>
							<!--end::User menu-->
							<!--begin::Action-->
							<div class="app-navbar-item ms-2 ms-lg-6 me-lg-6">
								<!--begin::Link-->
								<a href="<?php echo url('logout');?>" class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px">
									<i class="ki-outline ki-exit-right fs-1"></i>
								</a>
								<!--end::Link-->
							</div>
							<!--end::Action-->
						</div>
						<!--end::Navbar-->
					</div>
					<!--end::Header main-->
					<!--begin::Separator-->
					<div class="app-header-separator"></div>
					<!--end::Separator-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
						<!--begin::Wrapper-->
						<div class="app-sidebar-wrapper">
							<div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
								<!--begin::Sidebar menu-->
								<div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false" class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
									<div data-kt-menu-trigger="click" class="menu-item">
										<a class="menu-link active" href="<?php echo url('/dashboard');?>">
											<span class="menu-icon">
												<i class="la la-dashboard fs-2 fs-2"></i>
											</span>
											<span class="menu-title">Dashboards</span>
										</a>
									</div>
									<?php 
										$groupedPermissions = collect(Session::get('access_permissions'))
											->groupBy(function ($item) {
												return $item->module_name . '||' . $item->icon;
											});
									?>
									@foreach($groupedPermissions as $compositeKey => $permissions)
										@php
											[$moduleName, $icon] = explode('||', $compositeKey);
										@endphp
									<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
										<span class="menu-link">
											<span class="menu-icon">
												<i class="{{ $icon }}"></i>
											</span>
											<span class="menu-title">{{ $moduleName }}</span>
											<span class="menu-arrow"></span>
										</span>
										@foreach($permissions as $subModule)
										<div class="menu-sub menu-sub-accordion">
											<div class="menu-item">
												<a class="menu-link" href="{{ url($subModule->controller_name) }}">
													<span class="menu-bullet">
														<span class="bullet bullet-dot"></span>
													</span>
													<span class="menu-title">{{ $subModule->sub_module_name }}</span>
												</a>
											</div>
										</div>
										@endforeach
									</div> 
									@endforeach
								</div>
								<!--end::Sidebar menu-->
							</div>
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
									
									@yield('contant')
									
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer">
							<!--begin::Footer container-->
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<!--begin::Copyright-->
								<div class="text-gray-900 order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">2025&copy;</span>
									<a href="javascript:void(0);" target="_blank" class="text-gray-800 text-hover-primary">HAQHAI</a>
								</div>
								<!--end::Copyright-->
								<!--begin::Menu-->
								<ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
									<li class="menu-item">
										<a href="javascript:void(0);" target="_blank" class="menu-link px-2">Visit our Website</a>
									</li>
								</ul>
								<!--end::Menu-->
							</div>
							<!--end::Footer container-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-outline ki-arrow-up"></i>
		</div>
		<!--end::Scrolltop-->
		
		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script type="text/javascript" src="<?php echo url('public/assets/js/scripts.bundle.js')?>"></script>
		<!--end::Global Javascript Bundle-->

		<!--begin::Vendors Javascript(used for this page only)-->
		<script type="text/javascript" src="<?php echo url('public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/plugins/custom/datatables/datatables.bundle.js')?>"></script>
		
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script type="text/javascript" src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		
		<!--end::Vendors Javascript-->

		<!--begin::Custom Javascript(used for this page only)-->
		<script type="text/javascript" src="<?php echo url('public/assets/js/widgets.bundle.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/js/custom/widgets.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/js/custom/apps/chat/chat.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/js/custom/utilities/modals/upgrade-plan.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/js/custom/utilities/modals/users-search.js')?>"></script>
		<!--end::Custom Javascript-->
		
        <!--begin::Custom Javascript(created for common function)-->
        <script type="text/javascript" src="<?php echo url('public/assets/js/custom.js')?>"></script>
        <!--end::Custom Javascript(created for common function)-->
		
        <!--end::Javascript-->
		<script>
		document.addEventListener("DOMContentLoaded", function() {
			const currentUrl = window.location.href;
			const menuLinks = document.querySelectorAll('.menu-link');

			menuLinks.forEach(function(link) {
				// Check if current URL starts with link href
				if(currentUrl.startsWith(link.href)) {
					link.classList.add('active');

					// Expand parent menu if needed
					let parentAccordion = link.closest('.menu-sub-accordion');
					if (parentAccordion) {
						parentAccordion.style.display = 'block'; // Open the submenu
						parentAccordion.closest('.menu-item').classList.add('show'); // Optional: styling
					}
				}
			});
		});
		</script>

	</body>
	<!--end::Body-->
</html>