@extends('layout.layoutadmin')

@section('content')
    <!-- Main Content -->
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Akses Pintu</h4>
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
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Data Akses Pintu</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>Nama</th>
                                        <th>No Kartu</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                        <th style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr>
                                            <td>{{ $item['no'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>{{ $item['uid'] }}</td>
                                            <td>{{ $item['status'] }}</td>
                                            <td>{{ $item['waktu'] }}</td>
                                            <td>
                                                <a href="{{ route('aksespintu.edit', ['uid' => $item['uid'], 'waktu' => urlencode($item['waktu'])]) }}" data-toggle="tooltip" title="Ubah Akses Pintu" class="btn btn-sm btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form id="deleteForm-akses-{{ $item['uid'] }}-{{ md5($item['waktu']) }}" action="{{ route('aksespintu.destroy', ['uid' => $item['uid'], 'waktu' => urlencode($item['waktu'])]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger deleteButton" data-form-id="deleteForm-akses-{{ $item['uid'] }}-{{ md5($item['waktu']) }}" data-toggle="tooltip" title="Hapus Akses Pintu">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
