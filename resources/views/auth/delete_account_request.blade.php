@extends('publik.template.publik')

@section('content')
<style>
  body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .delete-account-wrapper {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 28px 16px;
  }

  .delete-account-card {
    background-color: #111;
    border-radius: 14px;
    padding: 34px 30px 28px;
    max-width: 520px;
    width: 100%;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
    border: 1px solid #2f2f2f;
  }

  .delete-account-card h2 {
    font-weight: 700;
    font-size: 32px;
    line-height: 1.2;
    text-align: center;
    margin-bottom: 22px;
    color: #e5d8b0;
  }

  .form-label,
  .form-check-label {
    display: block;
    color: #f3f3f3;
    font-size: 14px;
    margin-bottom: 6px;
  }

  .form-group {
    margin-bottom: 14px;
  }

  .delete-account-card .form-control,
  .delete-account-card select.form-control {
    background-color: #222;
    border: 1px solid #444;
    color: #fff;
    height: 44px;
    border-radius: 8px;
    font-size: 14px;
    width: 100%;
  }

  .delete-account-card select.form-control {
    appearance: auto;
    -webkit-appearance: menulist;
    padding: 0 12px;
  }

  .delete-account-card .form-control:focus {
    background-color: #222;
    color: #fff;
    border-color: #e5d8b0;
    box-shadow: none;
  }

  .btn-submit {
    width: 100%;
    margin-top: 2px;
    padding: 11px;
    border-radius: 8px;
    font-weight: 700;
    background-color: #e5d8b0;
    color: #000;
    border: none;
  }

  .btn-submit:hover {
    background-color: #d6c79e;
  }

  .footer,
  .footer .container,
  .footer p {
    text-align: center !important;
  }

  .footer {
    margin-top: 0 !important;
    padding-top: 16px !important;
    padding-bottom: 16px !important;
  }

  .form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 14px 0;
  }

  .form-check .form-check-input {
    margin: 0;
    width: 16px;
    height: 16px;
    border-color: #777;
    background-color: #111;
  }

  .form-check .form-check-input:checked {
    background-color: #e5d8b0;
    border-color: #e5d8b0;
  }

  @media (max-width: 576px) {
    .delete-account-card {
      padding: 26px 18px 20px;
    }

    .delete-account-card h2 {
      font-size: 24px;
      margin-bottom: 18px;
    }
  }
</style>

<div class="delete-account-wrapper">
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

      <div class="form-group">
        <label class="form-label" for="account_type">Label akun</label>
        <select class="form-control" id="account_type" name="account_type" required>
          <option value="">Pilih label akun</option>
          <option value="member" {{ old('account_type') === 'member' ? 'selected' : '' }}>Member</option>
          <option value="partner" {{ old('account_type') === 'partner' ? 'selected' : '' }}>Partner</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          value="{{ old('email') }}"
          placeholder="Masukkan email akun"
          required
        />
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          placeholder="Masukkan password akun"
          required
        />
      </div>

      <div class="form-check">
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
