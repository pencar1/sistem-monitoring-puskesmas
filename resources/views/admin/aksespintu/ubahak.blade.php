@extends('layout.layoutadmin')

@section('content')

<div class="page-inner">
    <div class="page-header">
        <div class="col-md-12">
            <form action="{{ route('aksespintu.update', ['uid' => $data['uid'], 'waktu' => urlencode($data['waktu'])]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Form Ubah Akses Pintu</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" class="form-control" id="nama" value="{{ $data['nama'] }}" placeholder="Masukkan Nama">
                            @error('nama')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>                    
                        <div class="form-group">
                            <label for="nokartu">No Kartu</label>
                            <div class="form-control">{{ $data['uid'] }}</div>
                            <input type="hidden" name="nokartu" value="{{ $data['uid'] }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option disabled hidden {{ $data['status'] == '' ? 'selected' : '' }}>Pilih Status</option>
                                <option value="Pending" {{ $data['status'] == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Aktif" {{ $data['status'] == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Tidak Aktif" {{ $data['status'] == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit"  class="btn btn-success saveButton">Simpan</button>
                        <button type="reset" class="btn btn-danger" onclick="window.location.href='{{ route('aksespintu') }}'">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
