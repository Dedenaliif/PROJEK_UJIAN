<form action="{{ route('logout') }}" method="POST">
    @csrf
    <div class="flex d-flex g-2">
        @php
            $user = auth()->user();
            $nama = $user->role == 'siswa'
            ? ($user->siswa->nama_siswa ?? $user->username)
            : $user->username;
        @endphp
        <button type="submit" class="btn btn-md btn-outline-danger">Logout</button>
        <span class="p-2 ">{{ $nama}}</span>
    </div>
</form>
