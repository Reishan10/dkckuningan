 <!-- Navbar Start -->
 <header id="topnav" class="defaultscroll sticky">
     <div class="container">
         <!-- Logo container-->
         <a class="logo" href="index.html">
             <img src="{{ asset('assets_frontend') }}/images/logo-dark.png" height="24" class="logo-light-mode"
                 alt="">
             <img src="{{ asset('assets_frontend') }}/images/logo-light.png" height="24" class="logo-dark-mode"
                 alt="">
         </a>
         <!-- Logo End -->

         <!-- End Logo container-->
         <div class="menu-extras">
             <div class="menu-item">
                 <!-- Mobile menu toggle-->
                 <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                     <div class="lines">
                         <span></span>
                         <span></span>
                         <span></span>
                     </div>
                 </a>
                 <!-- End mobile menu toggle-->
             </div>
         </div>

         <!--Login button Start-->


         <ul class="buy-button list-inline mb-0">
             @guest
                 <li class="list-inline-item mb-0">
                     <a href="{{ route('login') }}" class="login-btn-primary btn btn-primary">Masuk</a>
                     <a href="{{ route('login') }}" class="login-btn-light btn btn-light">Masuk</a>
                 </li>
             @else
                 <li class="list-inline-item mb-0">
                     <div class="dropdown dropdown-primary">
                         <button type="button" class="login-btn-primary btn btn-icon btn-pills btn-primary dropdown-toggle"
                             data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="user"
                                 class="icons"></i></button>
                         <button type="button" class="login-btn-light btn btn-icon btn-pills btn-light dropdown-toggle"
                             data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i data-feather="user"
                                 class="icons"></i></button>
                         <div class="dropdown-menu dd-menu dropdown-menu-end bg-white shadow rounded border-0 mt-3 py-3"
                             style="width: 200px;">
                             <a class="dropdown-item text-dark" href=""><i
                                     class="uil uil-estate align-middle me-1"></i> Dashboard</a>
                             <a class="dropdown-item text-dark" href=""><i
                                     class="uil uil-user align-middle me-1"></i> Akun Anda</a>
                             <a class="dropdown-item text-dark" href=""><i
                                     class="uil uil-key-skeleton align-middle me-1"></i> Ganti Kata Sandi</a>
                             <div class="dropdown-divider my-3 border-top"></div>
                             <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                                 onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i
                                     class="uil uil-sign-out-alt align-middle me-1"></i> Keluar</a>
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                 @csrf
                             </form>
                         </div>
                     </div>
                 </li>
             @endguest
         </ul>
         <!--Login button End-->


         <!--Login button End-->

         <div id="navigation">
             <!-- Navigation Menu-->
             <ul class="navigation-menu">
                 <li><a href="{{ route('beranda.index') }}" class="sub-menu-item">Beranda</a></li>
                 <li><a href="{{ route('pendaftaran.index') }}" class="sub-menu-item">Pendaftaran</a></li>
                 <li><a href="index.html" class="sub-menu-item">Berkas</a></li>
                 <li><a href="index.html" class="sub-menu-item">Timeline</a></li>
                 <li><a href="index.html" class="sub-menu-item">Pengumuman</a></li>
                 <li><a href="index.html" class="sub-menu-item">Blog</a></li>
             </ul><!--end navigation menu-->
         </div><!--end navigation-->
     </div><!--end container-->
 </header><!--end header-->
 <!-- Navbar End -->
