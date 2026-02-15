@extends('publik.template.publik')

@section('content')
{{-- di isi konten --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

   <style>

      .login-card {
        background-color: #111;
        border-radius: 12px;
        padding: 40px 30px;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
        margin: 40px auto;
      }

      .login-card h2 {
        font-weight: 600;
        text-align: center;
        margin-bottom: 30px;
        color: #e5d8b0;
      }

      .form-control {
        background-color: #222;
        border: 1px solid #444;
        color: white;
      }

      .form-control::placeholder {
        color: #aaa;
      }

      .btn-custom {
        width: 100%;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 8px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .btn-facebook {
        background-color: #4267b2;
        color: #fff;
      }

      .btn-facebook:hover {
        background-color: #365899;
      }

      .btn-email {
        background-color: #ea4335;
        color: #fff;
      }

      .btn-email:hover {
        background-color: #c33224;
      }

      .btn-submit {
        background-color: #e5d8b0;
        color: #000;
      }

      .btn-submit:hover {
        background-color: #d6c79e;
      }

      .btn-custom img {
        width: 20px;
        height: 20px;
        margin-right: 10px;
      }

      .toggle-password-btn {
        background: none;
        border: none;
        color: #ccc;
        padding: 0 10px;
        font-size: 18px;
      }

      .link-switch {
        text-align: center;
        margin-top: 10px;
      }

      .link-switch a {
        color: #e5d8b0;
        text-decoration: underline;
        cursor: pointer;
      }

      .form-section {
        display: none;
      }

      .form-section.active {
        display: block;
        animation: fadeIn 0.5s ease;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(15px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .btn-test-user {
        background: linear-gradient(90deg, #cc5500, #ffaa00);
        color: #fff;
        border: none;
        box-shadow: 0 0 15px rgba(255, 165, 0, 0.7);
        transition: all 0.3s ease;
        font-weight: 600;
      }

      .btn-test-user:hover {
        background: linear-gradient(90deg, #ffaa00, #cc5500);
        color: white;
        transform: scale(1.03);
        box-shadow: 0 0 20px rgba(255, 165, 0, 1);
      }

   </style>


     <!-- Login Card -->
  <div class="login">
    <div class="login-card">
      <h2 id="formTitle">Register</h2>

      <!-- Login Form -->
      <div id="loginForm" class="form-section active">

        {{-- <a href="{{ route('member.social.login', 'facebook') }}" class="btn btn-facebook btn-custom">
          <i class="fab fa-facebook"></i>&nbsp;&nbsp;
          Login with Google
        </a> --}}

        <a href="{{ route('member.social.login', 'google') }}" class="btn btn-email btn-custom">
          <i class="fab fa-google"></i>&nbsp;&nbsp;
          Login with Google
        </a>

       <div class="text-center text-muted mb-2">atau masuk manual</div>
       <form method="POST" action="{{ route('member.register.submit') }}">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          @csrf
          <input type="hidden" name="recaptcha_token" id="recaptcha_token" />

          <div class="form-group">
            <input
              type="text"
              class="form-control"
              placeholder="Nama Anda"
               name="name"
              required
            />
          </div>

          <div class="form-group mt-3">
            <input
              type="email"
              class="form-control"
              placeholder="Email Anda"
               name="email"
              required
            />
          </div>
        
          <div class="form-group mt-3">
            <input
              type="password"
              class="form-control"
              placeholder="Kata Sandi"
              name="password" 
              required
            />
          </div>
          <br/>
          <button type="submit" class="btn btn-submit btn-custom">Daftar</button>
        </form>

        <div class="link-switch">
          Sudah Punya Akun?
          <a href="{{ route('member.login') }}">Login Sekarang</a>
        </div>
      </div>

      <!-- Tombol Test User -->
      {{-- <a href="user.html" class="mt-3 btn-test-user btn-custom">
        <i class="fas fa-user-astronaut mr-2"></i> Masuk sebagai Test User
      </a> --}}
    </div>
  </div>
    


@endsection

@push('js')
  {{-- path path js --}}
<script src="https://www.google.com/recaptcha/api.js?render=6LdYxo0rAAAAABFf45sqd6cD1p9E9S6BpUG2ItM5"></script>
<script>
  grecaptcha.ready(function () {
    grecaptcha.execute('6LdYxo0rAAAAABFf45sqd6cD1p9E9S6BpUG2ItM5', { action: 'login' }).then(function (token) {
      document.getElementById('recaptcha_token').value = token;
    });
  });
</script>
@endpush