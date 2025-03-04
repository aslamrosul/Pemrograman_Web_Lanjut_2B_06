<body>
    <h1>Data User</h1>
    <a href="{{ url('/user/tambah') }}">+ Tambah User</a>
    <table border="1" cellpadding="2" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->user_id }}</td>
            <td>{{ $row->username }}</td>
            <td>{{ $row->nama }}</td>
            <td>{{ $row->level_id }}</td>
            <td>
                <a href="{{ url('/user/ubah/' . $row->user_id) }}">Ubah</a>
                <a href="{{ url('/user/hapus/' . $row->user_id) }}">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
</body>