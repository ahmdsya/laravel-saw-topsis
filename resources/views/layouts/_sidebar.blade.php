<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SAW <sup>X TOPSIS</sup></div>
    </a>
    <hr class="sidebar-divider my-0">

    <li class="nav-item {{(request()->route()->uri == '/' ? 'active' : '')}}">
        <a class="nav-link" href="{{route('dashboard')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <li
        class="nav-item {{(request()->route()->uri == 'karyawan' || request()->route()->uri == 'kriteria' || request()->route()->uri == 'nilai-bobot' ? 'active' : '')}}">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseTwo"
            class="collapse {{(request()->route()->uri == 'karyawan' || request()->route()->uri == 'kriteria' || request()->route()->uri == 'nilai-bobot' ? 'show' : '')}}"
            aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{(request()->route()->uri == 'karyawan' ? 'active' : '')}}"
                    href="{{route('karyawan')}}">Karyawan</a>
                <a class="collapse-item {{(request()->route()->uri == 'kriteria' ? 'active' : '')}}"
                    href="{{route('kriteria')}}">Kriteria</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{(request()->route()->uri == 'tambah-perhitungan' ? 'active' : '')}}">
        <a class="nav-link" href="{{route('tambah.perhitungan')}}">
            <i class="fas fa-fw fa-plus"></i>
            <span>Buat Perhitungan</span></a>
    </li>

    <li class="nav-item {{(request()->route()->uri == 'riwayat-perhitungan' ? 'active' : '')}}">
        <a class="nav-link" href="{{route('riwayat.perhitungan')}}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Perhitungan</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
