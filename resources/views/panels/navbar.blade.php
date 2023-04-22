
<!--Start topbar header-->
<header class="topbar-nav">
  <nav class="navbar navbar-expand fixed-top">
   <ul class="navbar-nav mr-auto align-items-center">
     <li class="nav-item">
       <a class="nav-link toggle-menu" href="javascript:void();">
        <i class="icon-menu menu-icon"></i>
      </a>
     </li>
     {{-- <li class="nav-item">
       <form class="search-bar">
         <input type="text" class="form-control" placeholder="Enter keywords">
          <a href="javascript:void();"><i class="icon-magnifier"></i></a>
       </form>
     </li> --}}
   </ul>
      
   <ul class="navbar-nav align-items-center right-nav-link">
     {{-- <li class="nav-item dropdown-lg">
       <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
       <i class="fa fa-envelope-open-o"></i></a>
     </li>
     <li class="nav-item dropdown-lg">
       <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
       <i class="fa fa-bell-o"></i></a>
     </li> --}}
 
     <li class="nav-item">
       <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
         <span class="user-profile"><img src="https://via.placeholder.com/110x110" class="img-circle" alt="user avatar"></span>
       </a>
       <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-item user-details">
         <a href="javaScript:void();">
            <div class="media">
              <div class="avatar"><img class="align-self-start mr-3" src="https://via.placeholder.com/110x110" alt="user avatar"></div>
             <div class="media-body">
              @auth
             <h6 class="mt-2 user-title">{{Auth::user()->first_name}}</h6>
             <p class="user-subtitle">{{Auth::user()->email}}</p>
             @endauth
             </div>
            </div>
           </a>
         </li>
         <li class="dropdown-divider"></li>
         <li class="dropdown-item"><a href="{{route('profile')}}"><i class="icon-wallet mr-2"></i> Account </a></li>
         <li class="dropdown-divider"></li>
         <li class="dropdown-item"><a href="{{route('logout')}}"><i class="icon-power mr-2"></i> Logout </a></li>
       </ul>
     </li>
   </ul>
 </nav>
 </header>
 <!--End topbar header-->

