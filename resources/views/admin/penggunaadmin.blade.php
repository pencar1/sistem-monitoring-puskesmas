@extends('layout.layoutadmin')

@section('content')
    <!-- Main Content -->
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Pengguna</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{-- Cek apakah ada pesan sukses --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                {{-- Cek apakah ada pesan error --}}
                @if (session('error'))
                    <div class="alert alert-danger mb-4" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- Cek apakah ada pesan warning --}}
                @if ($errors->has('warning'))
                    <div class="alert alert-warning mb-4" role="alert">
                        {{ $errors->first('warning') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Pengguna</h4>
                            <a href="{{ route('pengguna.create') }}" class="btn btn-primary btn-round ml-auto">
                                <i class="fa fa-plus"></i> Tambah Pengguna
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @if ($data)
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $item['nama'] ?? '-' }}</td>
                                                <td>{{ $item['email'] ?? '-' }}</td>
                                                <td>{{ $item['status'] ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('pengguna.edit', $key) }}" data-toggle="tooltip" title="Ubah Pengguna" class="btn btn-sm btn-success btn-lg">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form id="deleteForm-user-{{ $key }}" action="{{ route('pengguna.hapus', $key) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger deleteButton" data-form-id="deleteForm-user-{{ $key }}" data-toggle="tooltip" title="Hapus Pengguna">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
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
