<!--
=========================================================
* Argon Dashboard 3 - v2.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Finflip India | Login
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('assets/css/argon-dashboard.css?v=2.1.0')}}" rel="stylesheet" />
</head>

<body class="">

  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Sign In</h4>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class="card-body">
                  <form method="POST" action="" id="signin-form">
                    @csrf
                    <div class="mb-3">
                      <input type="email" class="form-control form-control-lg"name="email" placeholder="Email" aria-label="Email" required>
                    </div>
                    <div class="mb-3">
                      <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" aria-label="Password" required>
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                    </div>
                  </form>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>


  <script src="{{asset('assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('assets/js/soft-ui-dashboard.min.js?v=1.1.0')}}"></script>

  <script src="{{asset('asset/js/main.js')}}"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.0.18/sweetalert2.min.css">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400&display=swap">

  <script>

      $(document).ready(function(){


		$('#signin-form').on('submit', function(e) {
            // alert('Hello');
    e.preventDefault();

    var formdata = $(this).serialize();

    $.ajax({
        url: "{{route('userLogins')}}",
        type: "POST",
        data: formdata,
        success: function(response) {
            console.log(response);

			if(response.status == "success"){

				Swal.fire({
                title: 'Success!',
                text: 'You have successfully logged in.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
        popup: 'small-swal-popup',
        htmlContainer: 'custom-text-color',
        title: 'custom-title-color'
    }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{route('user.index')}}";
                }
            });

			}

			if(response.status == "error"){

Swal.fire({
// title: 'Ooops....',
text: response.message,
icon: 'error',
confirmButtonText: 'OK',
customClass: {
        popup: 'small-swal-popup',
        htmlContainer: 'custom-text-error-color',
        title: 'custom-title-error-color'
    }
}).then((result) => {
if (result.isConfirmed) {
	// window.location.reload();
}
});

}

        },
        error: function(xhr, status, error) {

            console.error(xhr.responseText);

            Swal.fire({
                title: 'Error!',
                text: 'There was a problem with your login.',
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
        popup: 'small-swal-popup',
        htmlContainer: 'custom-text-error-color',
        title: 'custom-title-error-color'
    }
            });
        }
    });
});


	  });

  </script>

</body>

</html>
