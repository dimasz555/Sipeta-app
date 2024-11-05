 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

   <ul class="sidebar-nav" id="sidebar-nav">

     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
         <i class="bi bi-grid"></i>
         <span>Dashboard</span>
       </a>
     </li>

     @if(auth()->user()->hasRole('admin'))
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('admin.profil') ? 'active' : '' }}" href="{{ route('admin.profil') }}">
         <i class="bi bi-person"></i>
         <span>Profil</span>
       </a>
     </li>
     @elseif(auth()->user()->hasRole('konsumen'))
     <li class="nav-item">
       <a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}" href="{{ route('profil') }}">
         <i class="bi bi-person"></i>
         <span>Profil</span>
       </a>
     </li>
     @endif


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
       <a class="nav-link">
         <i class="bi bi-bank"></i>
         <span>Kelola Project</span>
       </a>
     </li>

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