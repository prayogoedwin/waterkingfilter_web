@extends('publik.template.publik')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
@section('content')
  {{-- di isi konten --}}
    
    <!-- FAQ Header -->
    <section class="faq-header">
      <div class="container">
        <h1>Riwayat Tukar Poin</h1>
        <p class="lead">
          Informasi Daftar Riwayat Tukar Poin Anda
        </p>
      </div>
    </section>

    


     <!-- Katalog -->
    <section class="py-4 catalog">

     
      <div class="container">


        <div class="card card-default card-body">
                         {{-- <ul id="tabsJustified" class="nav nav-tabs nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" href="" data-target="#tab1" data-toggle="tab">Riwayat Prediksi</a>
                            </li>
                            
                        </ul>  --}}
                        <!--/tabs-->
                     
                        <div id="tabsJustifiedContent" class="tab-content">

                            <div class="tab-pane active" id="tab1">
                              
                              <div class="table-container">
                                <table id="userTable" class="table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Pesanan</th>
                                        <th class="text-center">Ukuran</th>
                                        <th class="text-center">Status Order</th>
                                        <th class="text-center">Status Kirim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($orders as $ord)
                                  <tr>
                                      <td class="text-center">{{ $loop->iteration }}</td>
                                      <td class="text-center">{{ $ord->varian }}</td>
                                      <td class="text-center">{{ $ord->ukuran }}</td>
                                      <td class="text-center">
                                        @php
                                            $statusOrderLabels = [
                                                0 => ['label' => 'Menunggu', 'class' => 'secondary'],
                                                1 => ['label' => 'Diproses', 'class' => 'primary'],
                                                2 => ['label' => 'Selesai', 'class' => 'success'],
                                                3 => ['label' => 'Ditolak', 'class' => 'danger'],
                                            ];
                                            $so = $statusOrderLabels[$ord->status_order] ?? ['label' => 'Tidak Diketahui', 'class' => 'dark'];
                                        @endphp
                                        <span class="badge bg-{{ $so['class'] }}">{{ $so['label'] }}</span>
                                    </td>
                                      <td class="text-center">
                                        @php
                                            $statusKirimLabels = [
                                                0 => ['label' => 'Belum Dikirim', 'class' => 'warning'],
                                                1 => ['label' => 'Sudah Dikirim', 'class' => 'success'],
                                            ];
                                            $sk = $statusKirimLabels[$ord->status_kirim] ?? ['label' => 'Tidak Diketahui', 'class' => 'dark'];
                                        @endphp
                                        <span class="badge bg-{{ $sk['class'] }}">{{ $sk['label'] }}</span>

                                        @if($ord->status_kirim == 1 && !empty($ord->resi))
                                            <br><small class="text-muted">Resi: {{ $ord->resi }}</small>
                                        @endif
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                </table>
                              </div>

                            </div>
                            
                        </div>
                        <!--/tabs content-->
                    </div><!--/card-->

        
      </div>

       <!-- Tombol Kembali -->
    <div class="text-center my-4">
      <a href="{{ route('publik') }}" class="btn btn-secondary btn-sm"
        >← Kembali ke Beranda</a
      >
    </div>
    </section>

   


@endsection

@push('js')
  {{-- path path js --}}
   <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

     <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                pageLength: 10,
                searching: false, // Menyembunyikan fitur pencarian
                language: {
                    lengthMenu: "",
                    info: "",
                    search: "Cari:",
                    zeroRecords: "Data tidak ditemukan",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush