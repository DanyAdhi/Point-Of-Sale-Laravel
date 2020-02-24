@php
  $url  = request()->segment(1);
  $url2 = request()->segment(2);
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('dashboard')}}">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item {{$url=='dashboard'?'active':''}}">
    <a class="nav-link" href="{{url('dashboard')}}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  @if (auth()->user()->can('Show Categories'))
    <li class="nav-item {{$url=='categories'?'active':''}}">
      <a class="nav-link" href="{{url('categories')}}">
        <i class="far fa-fw fa-list-alt"></i>
        <span>Categories</span></a>
    </li>
  @endif

  @if (auth()->user()->can('Show Products'))
    <li class="nav-item {{$url=='products'?'active':''}}">
      <a class="nav-link" href="{{url('products')}}">
        <i class="fas fa-fw fa-box-open"></i>
        <span>Products</span></a>
    </li>
  @endif

  @role('Admin')
    <li class="nav-item {{$url=='users' && $url2=='' ?'active':''}}">
      <a class="nav-link" href="{{url('users')}}">
        <i class="fas fa-fw fa-user-alt"></i>
        <span>Users</span></a>
    </li>

    <li class="nav-item {{$url=='roles'?'active':''}}">
      <a class="nav-link" href="{{url('roles')}}">
        <i class="fas fa-fw fa-user-cog"></i>
        <span>Roles</span></a>
    </li>

    <li class="nav-item {{$url2=='role-permission'?'active':''}}">
      <a class="nav-link" href="{{route('users.roles_permission')}}">
        <i class="fas fa-fw fa-cogs"></i>
        <span>Role Permission</span></a>
    </li>
  @endrole

  @role('Kasir')
    <li class="nav-item {{$url=='transaksi'?'active':''}}">
        <a class="nav-link" href="{{ route('order.transaksi') }}" >
            <i class="fa fa-shopping-cart"></i>
            <span>Transaksi</span>
        </a>
    </li>
    <li class="nav-item {{$url=='orders'?'active':''}}">
      <a class="nav-link" href="{{ route('orders.index') }}" >
          <i class="fa fa-dolly"></i>
          <span>Orders</span>
      </a>
    </li>
  @endrole


  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>