@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('member.password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="email" name="email" value="{{ $email }}" required readonly>
    <input type="password" name="password" placeholder="Password Baru" required>
    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
    <button type="submit">Reset Password</button>
</form>
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