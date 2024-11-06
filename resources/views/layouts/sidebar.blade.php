 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

   <ul class="sidebar-nav" id="sidebar-nav">


     @if(auth()->user()->hasRole('admin'))
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
         <i class="bi bi-grid"></i>
         <span>Dashboard</span>
       </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}" href="{{ route('admin.profil') }}">
         <i class="bi bi-person"></i>
         <span>Profil</span>
       </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('index.konsumen') ? 'active' : '' }}" href="{{ route('index.konsumen') }}">
         <i class="bi bi-people"></i>
         <span>Kelola Konsumen</span>
       </a>
     </li>

     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('index.project') ? 'active' : '' }}" href="{{ route('index.project') }}">
         <i class="bi bi-bank"></i>
         <span>Kelola Project</span>
       </a>
     </li>

     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('index.boking') ? 'active' : '' }}" href="{{ route('index.boking') }}">
         <i class="bi bi-calendar"></i>
         <span>Kelola Boking</span>
       </a>
     </li>

     <li class="nav-item">
       <a class="nav-link collapsed" data-bs-target="#kelolaPembayaran" data-bs-toggle="collapse" href="#">
         <i class="bi bi-cash-stack"></i>
         <span>Kelola Pembayaran</span>
         <i class="bi bi-chevron-down ms-auto"></i>
       </a>
       <ul id="kelolaPembayaran" class="nav-content collapse" data-bs-parent="#sidebar-nav">
         <li>
           <a class="nav-link {{ request()->routeIs('index.pembelian') ? 'active' : '' }}" href="{{ route('index.pembelian') }}">
             <i class="bi bi-file-earmark-text"></i>
             <span>Index Pembelian</span>
           </a>
         </li>
         <li>
           <a class="nav-link">
             <i class="bi bi-x-circle"></i>
             <span>Pembatalan</span>
           </a>
         </li>
       </ul>
     </li>
     @elseif(auth()->user()->hasRole('konsumen'))
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}" href="{{ route('profil') }}">
         <i class="bi bi-person"></i>
         <span>Profil</span>
       </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('index.riwayat.boking') ? 'active' : '' }}" href="{{ route('index.riwayat.boking') }}">
         <i class="bi bi-calendar-check"></i>
         <span>Riwayat Boking</span>
       </a>
     </li>
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('index.pembayaran.kavling') ? 'active' : '' }}" href="{{ route('index.pembayaran.kavling') }}">
         <i class="bi bi-credit-card"></i>
         <span>Pembayaran Kavling</span>
       </a>
     </li>
     @endif

     <li>
       <a class="nav-link {{ request()->routeIs('logout') ? 'active' : '' }}" href="{{ route('logout') }}" class="nav-link scrollto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
         <i class="bi bi-box-arrow-right"></i>
         <span>Logout</span>
       </a>
       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
         @csrf
       </form>
     </li>

   </ul>

 </aside><!-- End Sidebar-->