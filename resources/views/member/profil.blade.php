@extends('publik.template.publik')

@section('content')
  {{-- di isi konten --}}
  <style>
    /* Semua input tetap font hitam */
.howtoplay-container input,
.howtoplay-container textarea {
  color: #000 !important;
  background-color: #f8f9fa !important;
  border: 1px solid #ffc107; /* optional: kuning gold ala tema kamu */
}

/* Saat input di focus, tetap terang */
.howtoplay-container input:focus,
.howtoplay-container textarea:focus {
  background-color: #fff !important;
  color: #000 !important;
  border-color: #ffc107;
  outline: none;
  box-shadow: 0 0 5px #ffc107;
}

/* Jika readonly: tetap warna terang */
.howtoplay-container input[readonly],
.howtoplay-container textarea[readonly] {
  background-color: #e9ecef !important;
  color: #000 !important;
}

/* Jika disabled: tetap warna terang dan font jelas */
.howtoplay-container input[disabled],
.howtoplay-container textarea[disabled] {
  background-color: #e9ecef !important;
  color: #000 !important;
  opacity: 1;
}

/* Label kalau perlu disesuaikan juga */
.howtoplay-container label {
  color: #ffc107;
}
  </style>
   
   <div class="container howtoplay-container">
  <h1 class="howtoplay-title">Profil Member<br/>

     <small>Email: {{ $profil->email }}</small>
  </h1>
 

  <form id="formProfilMember" method="POST" action="{{ route('member.profil_update') }}">
    @csrf
    <div class="step-box">

    @if (session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif


      <div class="form-group">
        <label>Nama</label>
        <input type="hidden" class="form-control" name="id" value="{{ $profil->id }}" >
        <input type="hidden" class="form-control" name="email" value="{{ $profil->email }}" >
        <input type="text" class="form-control" name="name" value="{{ $profil->name }}" >
        <br/>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="******" >
        <br/>
      </div>

      <div class="form-group">
        <label>WhatsApp</label>
        <input type="text" class="form-control" name="whatsapp" value="{{ $profil->whatsapp }}" >
        <br/>
      </div>

      <div class="form-group">
        <label>Alamat</label>
        <textarea class="form-control" name="alamat" rows="3" >{{ $profil->alamat }}</textarea>
      </div>
    </div>

    <div class="text-center mt-4">
      <a href="{{ route('publik') }}" class="btn btn-warning">Kembali ke Beranda</a>
      <button type="submit" class="btn btn-success" id="btnSimpan">Simpan Perubahan</button>
    </div>
  </form>
</div>


@endsection

@push('js')
  {{-- path path js --}}



@endpush