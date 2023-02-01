{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-th-list"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-th-list"></i> Categories</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('introduction') }}"><i class="nav-icon la la-th-list"></i> Introductions</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('tip') }}"><i class="nav-icon la la-th-list"></i> Tips</a></li>