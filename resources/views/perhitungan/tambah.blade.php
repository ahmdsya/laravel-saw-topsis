@extends('layouts.app')

@section('title', 'Buat Perhitungan Baru')

@section('content')

<div class="row">
    <div id="errorMsg" class="col-lg-12" style="display:none;">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Terdapat duplikat data karyawan atau data nilai bobot yang masih kosong. Pastikan kembali data yang akan anda kirim.
            <button id="closeAlert" type="button" class="close" onclick="hideAlert()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Buat Perhitungan Baru</h6>
            </div>
            <div class="card-body">

                {{-- prototype --}}
                <table style="display:none">
                    <tr class="tr-blueprint" style="display:none">
                        <td width="5%" class="text-center">
                            <button type="button" class="btn btn-sm btn-danger btn-del-detail">
                                <i class="fas fa-minus"></i>
                            </button>
                        </td>
                        <td width="25%">
                            <select class="form-control" name="" id="id_karyawan" required>
                                <option value="0" selected disabled>Silahkan Pilih</option>
                                @foreach ($karyawans as $karyawan)
                                <option value="{{$karyawan->id}}">{{$karyawan->name}}</option>
                                @endforeach
                            </select>
                        </td>
                        @foreach ($kriterias as $index => $kriteria)
                        <td>
                            <input type="hidden" id="id_kriteria_{{$index}}" value="{{$kriteria->id}}">
                            <input type="number" id="nilai_{{$index}}" class="form-control"
                                required>
                        </td>
                        @endforeach
                    </tr>
                </table>
                {{-- prototype --}}

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" id="table-detail">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center">Alternatif (Karyawan)</td>
                                <th colspan="{{$kriterias->count()}}" class="text-center">Bobot Kriteria</th>
                            </tr>
                            <tr>
                                <th width="5%" class="text-center">
                                    <button id="btn-add-detail" type="button" class="btn btn-sm btn-success"><i
                                            class="fas fa-plus"></i></button>
                                </th>
                                <th width="25%">Nama Karyawan</th>
                                @foreach ($kriterias as $index => $kriteria)
                                <th>{{$kriteria->kriteria}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="tbody_detail"></tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-sm btn-primary float-right mt-2" onclick="onSubmit()">
                    <i class="fas fa-calculator mr-2"></i>
                    Mulai Perhitungan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {

        $("#btn-add-detail").click(function () {
            var tbl = $(this).closest("table");
            var blueprint = $(".tr-blueprint").clone();
            blueprint.appendTo(tbl).removeClass("tr-blueprint").show();

            $(".btn-del-detail", blueprint).click(function () {
                var row = $(this).closest("tr");
                row.remove();
            });
        });

    });

    function hideAlert(){
        $("#errorMsg").hide();
    }

    function hasDuplicates(array) {
        return array.some((item, index) => array.findIndex(obj => obj['IdKaryawan'] === item['IdKaryawan']) !== index);
    }

    function setDataForm(){

        hideAlert();
        
        var validate = true;
        var jlhKriteria = {!! $kriterias->count() !!};
        var trs = $("#tbody_detail tr:not(.tr-blueprint)");
        
        var dataForm = [];

        if(trs.length <= 1)
            validate = false;

        for (var i = 0; i < trs.length; i++) {
            var tr = trs[i];

            var IdKaryawan = $("#id_karyawan", $(tr)).val();
            var arrIdKriteria = [];
            var arrNilai = [];

            $("#id_karyawan", $(tr)).removeClass("is-invalid");

            if(IdKaryawan == null){
                $("#id_karyawan", $(tr)).addClass("is-invalid");
                validate = false;
            }

            for (var j = 0; j < jlhKriteria; j++) {
                var id_kriteria = $("#id_kriteria_"+j, $(tr)).val();
                var nilai = $("#nilai_"+j, $(tr));
                
                nilai.removeClass("is-invalid");

                if(nilai.val() == ''){
                    nilai.addClass("is-invalid");
                    validate = false;
                }

                arrIdKriteria.push(id_kriteria);
                arrNilai.push(nilai.val());
            }

            dataForm.push(
                {
                    IdKaryawan: IdKaryawan,
                    IdKriteria: arrIdKriteria,
                    Nilai: arrNilai
                }
            );
        }

        var hasDuplicate = hasDuplicates(dataForm);

        if(!validate || hasDuplicate){
            $("#errorMsg").show();
            return false;
        }

        return dataForm;
    }

    function onSubmit() {
        var dataForm = setDataForm();
        
        if(dataForm){
            $.ajax({
                type:'POST',
                url:"{{ route('post.tambah.perhitungan') }}",
                data: {
                    _token: '{!! csrf_token() !!}',
                    data: JSON.stringify(dataForm)
                },
                success:function(data){
                    if(data.success){
                        var id_riwayat = data.id_riwayat;
                        window.location.href = "/hasil-perhitungan/"+id_riwayat;
                    }
                }
            });
        }
    }

</script>
@endsection
