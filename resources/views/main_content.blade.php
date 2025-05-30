    @include('layouts.sidebar')
    <div class="main-panel">
      <!-- Navbar -->
      @include('layouts.navbar')
      <!-- End Navbar -->
      @yield('content')
    </div>