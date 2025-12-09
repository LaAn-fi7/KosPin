@auth
  @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.index') }}" class="ml-4 text-sm text-gray-700">Admin</a>
  @endif
@endauth
