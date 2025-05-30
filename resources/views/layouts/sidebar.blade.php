   <div class="sidebar" data-color="purple" data-background-color="black" data-image="assets/img/sidebar-2.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">
          Creative Tim
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item  ">
            <a class="nav-link" href="{{ url('dashboard') }}">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="{{ url('productos') }}">
              <i class="material-icons">sell</i>
              <p>Productos</p>
            </a>
          </li>
          <li class="nav-item active ">
            <a class="nav-link" href="{{ url('lista-prepedidos') }}">
              <i class="material-icons">content_paste</i>
              <p>Prepedidos</p>
            </a>
          </li>

        </ul>
      </div>
    </div>