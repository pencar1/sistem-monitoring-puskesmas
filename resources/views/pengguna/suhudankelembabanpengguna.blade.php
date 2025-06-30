@extends('layout.layoutpengguna')

@section('content')
    <!-- Main Content -->
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Suhu dan Kelembapan</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Suhu dan Kelembapan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th style="width: 37%">Suhu (&deg;C)</th>
                                        <th style="width: 37%">Kelembapan (%)</th>
                                        <th style="width: 21%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @if ($data)
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item['suhu'] ?? '-' }}&deg;C</td>
                                                <td>{{ $item['kelembaban'] ?? '-' }}%</td>
                                                <td>{{ $item['waktu'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
