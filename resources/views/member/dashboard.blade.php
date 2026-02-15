@extends('publik.template.publik')

@section('content')
  {{-- di isi konten --}}

<div class="col main pt-5 mt-3 container">
            <h1 class="display-4 d-none d-sm-block">
            Halo,  {{ Auth::guard('member')->user()->name }}
            </h1>
            <p class="lead d-none d-sm-block">Terima kasih sudah bergabung di gilaprediksi ya..</p>

            <div class="alert alert-warning fade collapse" role="alert" id="myAlert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
                <strong>Holy guacamole!</strong> It's free.. this is an example theme.
            </div>
            <div class="row mb-3">
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-user fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Memprediksi</h6>
                            <h1 class="">{{ $totalPrediksi }} kali</h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fa fa-list fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Poin Terkini</h6>
                            <h1 class="">{{ $member->poin_terkini ?? 0 }}</h1>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-twitter fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Join Pada</h6>
                            {{-- <h1 class="display-4">{{ $member->created_at }}</h1> --}}
                            <h2>{{ $member->created_at->format('d F Y') }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-share fa-4x"></i>
                            </div>
                            <h6 class="text-uppercase">Tipe akun</h6>
                            @php
                                $tipeAkunLabel = match($member->tipe_akun ?? 0) {
                                    0 => 'Newbie',
                                    1 => 'Middle',
                                    2 => 'Pro',
                                    3 => 'Elite',
                                    4 => 'Legend',
                                    default => 'Unknown',
                                };
                            @endphp

                            <h2 class="">{{ $tipeAkunLabel }}</h1>
                                                    </div>
                    </div>
                </div>
            </div>
            <!--/row-->

        </div>

    @endsection

@push('js')
  {{-- path path js --}}
@endpush