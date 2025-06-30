 <!-- Sidebar -->
 <div class="sidebar">

    <div class="sidebar-background"></div>
    <div class="sidebar-wrapper scrollbar-inner">
        <div class="sidebar-content">

            <ul class="nav">
                <li class="nav-item">
                    <a href="/admin/dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                        <a href="/admin/pengguna">
                        <i class="fas fa-user"></i>
                        <p>Pengguna</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/aksespintu">
                        <i class="far fa-id-card"></i>
                        <p>Akses Pintu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/historyaksespintu">
                        <i class="fas fa-history"></i>
                        <p>History Akses Pintu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/admin/suhudankelembaban">
                        <i class="fas fa-thermometer-half"></i>
                        <p>Suhu dan Kelembapan</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
    </div>
    <!-- End Sidebar -->

    <div class="main-panel">
        <div class="content">
            @yield('content')
        </div>
    </div>
    </div>
 </div>
