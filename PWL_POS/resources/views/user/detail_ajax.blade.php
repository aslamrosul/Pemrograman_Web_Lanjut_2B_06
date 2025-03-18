@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Detail User</h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary" id="btn-load-user" data-id="{{ $user_id }}">Muat Data</button>
        </div>
    </div>
    <div class="card-body">
        <div id="user-detail">
            <div class="alert alert-info">Klik tombol "Muat Data" untuk melihat detail user.</div>
        </div>
        <a href="{{ url('user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $("#btn-load-user").on("click", function () {
            let userId = $(this).data("id");

            $.ajax({
                url: `/user/${userId}/detail`,
                type: "GET",
                beforeSend: function () {
                    $("#user-detail").html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
                },
                success: function (response) {
                    if (response.status) {
                        let user = response.user;
                        let userHtml = `
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <tr>
                                    <th>ID</th>
                                    <td>${user.user_id}</td>
                                </tr>
                                <tr>
                                    <th>Level</th>
                                    <td>${user.level.level_nama}</td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td>${user.username}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>${user.nama}</td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td>********</td>
                                </tr>
                            </table>
                        `;
                        $("#user-detail").html(userHtml);
                    } else {
                        $("#user-detail").html('<div class="alert alert-danger">Data user tidak ditemukan.</div>');
                    }
                },
                error: function () {
                    $("#user-detail").html('<div class="alert alert-danger">Terjadi kesalahan saat mengambil data.</div>');
                }
            });
        });
    });
</script>
@endpush
