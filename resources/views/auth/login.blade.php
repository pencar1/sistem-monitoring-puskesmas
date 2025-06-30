<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Puskesmas Martapura Timur</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset ('images/logo/logo_puskes.png')}}" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="{{ asset ('azzara/assets/js/plugin/webfont/webfont.min.js')}}"></script>
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['{{ asset ('azzara/assets/css/fonts.css')}}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset ('azzara/assets/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{ asset ('azzara/assets/css/azzara.min.css')}}">
</head>
<body class="login">
	<div class="wrapper wrapper-login">
		<div class="container container-login animated fadeIn">
			<h3 class="text-center">Selamat Datang</h3>
			<div class="login-form">
				<form method="POST" action="{{ route('firebase.login') }}">
					@csrf
					<div class="form-group form-floating-label">
						<input id="email" name="email" type="email" class="form-control input-border-bottom @error('email') is-invalid @enderror" value="{{ old('email') }}">
						<label for="email" class="placeholder">Email</label>
						@error('email')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group form-floating-label">
						<input id="password" name="password" type="password" class="form-control input-border-bottom @error('password') is-invalid @enderror">
						<label for="password" class="placeholder">Password</label>
						@error('password')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-action mb-3">
						<button type="submit" class="btn btn-primary btn-rounded btn-login">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="{{ asset ('azzara/assets/js/core/jquery.3.2.1.min.js')}}"></script>
	<script src="{{ asset ('azzara/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
	<script src="{{ asset ('azzara/assets/js/core/popper.min.js')}}"></script>
	<script src="{{ asset ('azzara/assets/js/core/bootstrap.min.js')}}"></script>
	<script src="{{ asset ('azzara/assets/js/ready.js')}}"></script>

	<!-- SweetAlert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	@if (session('error'))
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: '{{ session('error') }}',
				confirmButtonColor: '#3085d6',
			});
		});
	</script>
	@endif

	@if ($errors->any())
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			Swal.fire({
				icon: 'error',
				title: 'Validasi Gagal',
				html: `{!! implode('<br>', $errors->all()) !!}`,
				confirmButtonColor: '#d33',
			});
		});
	</script>
	@endif

	@if (session('success'))
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			Swal.fire({
				icon: 'success',
				title: 'Berhasil',
				text: '{{ session('success') }}',
				confirmButtonColor: '#3085d6',
			});
		});
	</script>
	@endif

</body>
</html>