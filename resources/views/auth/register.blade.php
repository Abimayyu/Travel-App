
<!doctype html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register -Travel App</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=2.0.0') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=2.0.0') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dark.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.min.css') }}" />
  </head>
  <body>
    <div class="wrapper">
      <section class="login-content">
        <div class="row m-0 align-items-center bg-white vh-100">            
          <div class="col-md-6 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
            <img src="{{ asset('assets/images/auth/05.png') }}" class="img-fluid gradient-main animated-scaleX" alt="images">
          </div>
          <div class="col-md-6">               
            <div class="row justify-content-center">
              <div class="col-md-10">
                <div class="card card-transparent auth-card shadow-none d-flex justify-content-center mb-0">
                  <div class="card-body">
                    <a class="navbar-brand d-flex align-items-center mb-3">
                        <div class="logo-main">
                            <div class="logo-normal">
                                <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                    <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                    <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                    <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="logo-mini">
                                <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2" transform="rotate(-45 -0.757324 19.2427)" fill="currentColor"/>
                                    <rect x="7.72803" y="27.728" width="28" height="4" rx="2" transform="rotate(-45 7.72803 27.728)" fill="currentColor"/>
                                    <rect x="10.5366" y="16.3945" width="16" height="4" rx="2" transform="rotate(45 10.5366 16.3945)" fill="currentColor"/>
                                    <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2" transform="rotate(45 10.5562 -0.556152)" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                      <h4 class="logo-title ms-3">Travel App</h4>
                    </a>
                    <h2 class="mb-2 text-center">Register</h2>
                    <p class="text-center">Buat akun baru untuk menikmati fitur-fitur kami</p>
                    <form method="POST" id="register-form">
                      @csrf
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" required>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Buat</button>
                      </div>
                    </form>
                    <p class="mt-3 text-center">
                      Sudah Punya Akun? <a href="{{ route('login') }}" class="text-underline">Masuk</a>
                    </p>
                  </div>
                </div>    
              </div>
            </div>           
          </div>   
        </div>
      </section>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


    <!-- Tambahkan Script untuk Handle Register -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          const registerForm = document.getElementById('register-form');
          if (registerForm) {
            registerForm.addEventListener('submit', function(event) {
              event.preventDefault();
              
              const name = document.getElementById('name').value;
              const email = document.getElementById('email').value;
              const password = document.getElementById('password').value;
              const password_confirmation = document.getElementById('password_confirmation').value;
              
              axios.post('{{ url("api/register") }}', {
                name: name,
                email: email,
                password: password,
                password_confirmation: password_confirmation
              })
              .then(response => {
                Swal.fire({
                  icon: 'success',
                  title: 'Pendaftaran Berhasil!',
                  text: 'Akun Anda telah berhasil dibuat.',
                }).then(() => {
                  window.location.href = '{{ route("dashboard.index") }}';
                });
              })
              .catch(error => {
                if (error.response && error.response.data.errors) {
                  let errors = error.response.data.errors;
                  let errorMessages = Object.values(errors).flat().join('\n');
                  Swal.fire({
                    icon: 'error',
                    title: 'Pendaftaran Gagal!',
                    text: errorMessages,
                  });
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Silakan coba lagi nanti.',
                  });
                }
              });
            });
          }
        });
      </script>
  </body>
  </html>
