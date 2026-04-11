<form action="{{ route('logout') }}" method="POST">
    @csrf
    <div class="flex d-flex g-2">
        <button type="submit" class="btn btn-md btn-outline-danger">Logout</button>
        <span class="p-2 ">{{ Auth::user()->username }}</span>
    </div>
</form>
