<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>{{$title}}</title>

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
		<!--end::Fonts-->

		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/plugins/global/plugins.bundle.css')?>">
        <link rel="stylesheet" type="text/css" href="<?php echo url('public/assets/css/style.bundle.css')?>">
		<!--end::Global Stylesheets Bundle-->
		<script> var SITE_URL = "<?php echo config('constants.SITE_URL');?>/"; </script>
		<script> var ASSETS = "<?php echo config('constants.ASSETS');?>/"; </script>
		<script> var ROOT_DIR = "<?php echo config('constants.ROOT_DIR');?>/"; </script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Page bg image-->
			<style>body { background-image: url('public/assets/media/auth/bg11.jpg'); } [data-bs-theme="dark"] body { background-image: url('public/assets/media/auth/bg4-dark.jpg'); }</style>
			<!--end::Page bg image-->
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-column-fluid flex-lg-row">
				<!--begin::Aside-->
				<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
					<!--begin::Aside-->
					<div class="d-flex flex-center flex-lg-start flex-column">
						<!--begin::Logo-->
						<a href="<?php echo url('/');?>" class="mb-7">
							<img alt="Logo" src="<?php echo url('public/assets/media/logos/logo.png')?>" />
						</a>
						<!--end::Logo-->
						<!--begin::Title-->
						<h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2>
						<!--end::Title-->
					</div>
					<!--begin::Aside-->
				</div>
				<!--begin::Aside-->
				<!--begin::Body-->
				<div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-15">
					<!--begin::Card-->
					<div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-500px p-15">
						<!--begin::Wrapper-->
						<div class="d-flex flex-center flex-column flex-column-fluid px-lg-5 pb-10 pb-lg-10">
							<!--begin::Form-->
							<form id="reset_password_form" name="reset_password_form" class="form w-100" novalidate="novalidate" method="post" >
								<!--begin::Heading-->
								<div class="text-center mb-10">
									<!--begin::Title-->
									<h1 class="text-gray-900 fw-bolder mb-3">Setup New Password</h1>
									<!--end::Title-->
									<!--begin::Link-->
									<div class="text-gray-500 fw-semibold fs-6">Have you already reset the password ? 
									<a href="<?php echo url('/');?>" class="link-primary fw-bold">Sign In</a></div>
									<!--end::Link-->
								</div>
								<!--begin::Heading-->
								<!--begin::Input group-->
								<div class="fv-row mb-8" data-kt-password-meter="true">
									<!--begin::Wrapper-->
									<div class="mb-1">
										<!--begin::Input wrapper-->
										<div class="position-relative mb-3">
											<input type="password" id="New_Password" name="New_Password" placeholder="New Password" autocomplete="off" class="form-control bg-transparent" required />
											<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
												<i class="ki-outline ki-eye-slash fs-2 toggle-password" toggle="#New_Password"></i>
											</span>
										</div>
										<!--end::Input wrapper-->
										<!--begin::Meter-->
										<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
											<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
										</div>
										<!--end::Meter-->
									</div>
									<!--end::Wrapper-->
									<!--begin::Hint-->
									<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
									<!--end::Hint-->
								</div>
								<!--end::Input group=-->
								<!--end::Input group=-->
								<div class="fv-row mb-8">
									<!--begin::Repeat Password-->
									<div class="position-relative mb-3">
										<input type="password" id="Confirm_Password" name="Confirm_Password" placeholder="Confirm Password" autocomplete="off" class="form-control bg-transparent" required />
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="ki-outline ki-eye-slash fs-2 toggle-password" toggle="#Confirm_Password"></i>
										</span>
									</div>
									<!--end::Repeat Password-->
								</div>
								<!--end::Input group=-->
								<!--begin::Input group=-->
								<div class="fv-row mb-8">
									<label class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="toc" value="1" />
										<span class="form-check-label fw-semibold text-gray-700 fs-6 ms-1">I Agree & 
										<a href="javascript:void(0);" class="ms-1 link-primary">Terms and conditions</a>.</span>
									</label>
								</div>
								<!--end::Input group=-->
								<!--begin::Action-->
								<div class="d-grid">
									<button type="button" id="kt_new_password_submit" class="btn btn-primary">
										<!--begin::Indicator label-->
										<span class="indicator-label">Submit</span>
										<!--end::Indicator label-->
										<!--begin::Indicator progress-->
										<span class="indicator-progress">Please wait... 
										<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
										<!--end::Indicator progress-->
									</button>
								</div>
								<!--end::Action-->
							</form>
							<!--end::Form-->
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Card-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->

		<!--begin::Javascript-->
		<script>var hostUrl = "assets/";</script>
		
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script type="text/javascript" src="<?php echo url('public/assets/plugins/global/plugins.bundle.js')?>"></script>
		<script type="text/javascript" src="<?php echo url('public/assets/js/scripts.bundle.js')?>"></script>
		<!--end::Global Javascript Bundle-->
		
        <!--begin::Custom Javascript(created for common function)-->
        <script type="text/javascript" src="<?php echo url('public/assets/js/custom.js')?>"></script>
        <!--end::Custom Javascript(created for common function)-->
		
        <!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>