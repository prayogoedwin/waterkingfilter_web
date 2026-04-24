@extends('publik.template.publik')

@section('content')
<style>
  .delete-account-card {
    background-color: #111;
    border-radius: 12px;
    padding: 40px 30px;
    max-width: 460px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
    margin: 40px auto;
  }

  .delete-account-card h2 {
    font-weight: 600;
    text-align: center;
    margin-bottom: 24px;
    color: #e5d8b0;
  }

  .form-label,
  .form-check-label {
    color: #f3f3f3;
  }

  .form-control,
  .form-select {
    background-color: #222;
    border: 1px solid #444;
    color: #fff;
  }

  .form-control:focus,
  .form-select:focus {
    background-color: #222;
    color: #fff;
    border-color: #e5d8b0;
    box-shadow: none;
  }

  .btn-submit {
    width: 100%;
    margin-top: 6px;
    padding: 10px;
    border-radius: 8px;
    font-weight: 600;
    background-color: #e5d8b0;
    color: #000;
  }

  .btn-submit:hover {
    background-color: #d6c79e;
  }
</style>

<div class="delete-account-card">
  <h2>Permohonan Hapus Akun</h2>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <form method="POST" action="{{ route('account.delete.submit') }}" id="deleteAccountForm">
    @csrf

    <input type="hidden" name="recaptcha_token" id="recaptcha_token" />

    <div class="mb-3">
      <label class="form-label" for="account_type">Label akun</label>
      <select class="form-select" id="account_type" name="account_type" required>
        <option value="">Pilih label akun</option>
        <option value="member" {{ old('account_type') === 'member' ? 'selected' : '' }}>Member</option>
        <option value="partner" {{ old('account_type') === 'partner' ? 'selected' : '' }}>Partner</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label" for="email">Email</label>
      <input
        type="email"
        class="form-control"
        id="email"
        name="email"
        value="{{ old('email') }}"
        required
      />
    </div>

    <div class="mb-3">
      <label class="form-label" for="password">Password</label>
      <input
        type="password"
        class="form-control"
        id="password"
        name="password"
        required
      />
    </div>

    <div class="form-check mb-3">
      <input
        class="form-check-input"
        type="checkbox"
        value="1"
        id="confirm_delete"
        name="confirm_delete"
        required
      >
      <label class="form-check-label" for="confirm_delete">
        Konfirmasi hapus akun
      </label>
    </div>

    <button type="submit" class="btn btn-submit">Submit</button>
  </form>
</div>
@endsection

@push('js')
  @if(env('RECAPTCHA_V2') == 1 && env('RECAPTCHA_SITE_KEY'))
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
      grecaptcha.ready(function () {
        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', { action: 'delete_account' }).then(function (token) {
          document.getElementById('recaptcha_token').value = token;
        });
      });
    </script>
  @endif

  <script>
    document.getElementById('deleteAccountForm').addEventListener('submit', function (e) {
      const confirmed = window.confirm('Apakah Anda yakin? Akun akan dihapus (soft delete).');
      if (!confirmed) {
        e.preventDefault();
      }
    });
  </script>
@endpush
