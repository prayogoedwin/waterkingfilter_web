@extends('publik.template.publik')

@section('content')
  {{-- di isi konten --}}

<!-- Hero -->
<div id="ufcCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indikator -->
    <ul class="carousel-indicators">
        @foreach ($banners as $index => $banner)
            <li data-target="#ufcCarousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
        @endforeach
    </ul>

    <!-- Slide -->
    <div class="carousel-inner">
        @foreach ($banners as $index => $banner)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="background-image: url('{{ asset('storage/'.$banner->foto) }}'); background-size: cover; background-position: center;">
                {{-- Optional caption --}}
                {{-- <div class="carousel-caption d-none d-md-block">
                    <h3>{{ $banner->judul }}</h3>
                    <p>{{ $banner->deskripsi }}</p>
                </div> --}}
            </div>
        @endforeach
    </div>

    <!-- Kontrol Navigasi -->
    <a class="carousel-control-prev" href="#ufcCarousel" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#ufcCarousel" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>
</div>


@endsection

@push('js')
  {{-- path path js --}}
    {{-- <script>
        document.querySelectorAll('[id^="alert-success-"]').forEach(alert => {
            setTimeout(() => {
                alert.remove();
            }, 3000);
        });
    </script> --}}
@endpush