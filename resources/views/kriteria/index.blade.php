@extends('layouts.app')

@section('title', 'Data kriteria')

@section('content')

<div class="row">
    @if (session('success'))
    <div class="col-lg-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    @endif
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary d-inline-block">Data Kriteria</h6>
                <button class="btn btn-sm btn-primary d-inline-block float-right" data-toggle="modal"
                    data-target="#ModalTambahKriteria">Tambah Kriteria</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="10%" class="text-center">No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Tipe</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $index => $row)
                            <tr>
                                <td class="text-center">{{$index+1}}</td>
                                <td>{{$row->kriteria}}</td>
                                <td>{{$row->bobot}}</td>
                                <td>{{$row->tipe}}</td>
                                <td class="text-center">
                                    <form action="{{route('kriteria.delete', $row->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#ModalUbahKriteria-id-{{$row->id}}">Ubah</button>
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Ubah-->
                            <div class="modal fade" id="ModalUbahKriteria-id-{{$row->id}}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Ubah kriteria</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{route('kriteria.update')}}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id" value="{{$row->id}}">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="kriteria">Nama kriteria</label>
                                                    <input type="text" class="form-control" id="kriteria"
                                                        name="kriteria" value="{{$row->kriteria}}" required
                                                        placeholder="nama kriteria">
                                                </div>
                                                <div class="form-group">
                                                    <label for="bobot">Bobot</label>
                                                    <input type="number" class="form-control" id="bobot" name="bobot"
                                                        value="{{$row->bobot}}" required placeholder="20">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tipe">Tipe</label>
                                                    <select name="tipe" id="tipe" class="form-control" required>
                                                        <option {{ ($row->tipe == 'Benefit' ? 'selected' : '') }}
                                                            value="Benefit">Benefit</option>
                                                        <option {{ ($row->tipe == 'Cost' ? 'selected' : '') }}
                                                            value="Cost">Cost</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END Modal Ubah-->

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah-->
<div class="modal fade" id="ModalTambahKriteria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kriteria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('kriteria.save')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kriteria">Nama kriteria</label>
                        <input type="text" class="form-control" id="kriteria" name="kriteria" required
                            placeholder="nama kriteria">
                    </div>
                    <div class="form-group">
                        <label for="bobot">Bobot</label>
                        <input type="number" class="form-control" id="bobot" name="bobot" required placeholder="20">
                    </div>
                    <div class="form-group">
                        <label for="tipe">Tipe</label>
                        <select name="tipe" id="tipe" class="form-control" required>
                            <option value="Benefit">Benefit</option>
                            <option value="Cost">Cost</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
