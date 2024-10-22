<!-- resources/views/admin/dashboard.blade.php -->
@extends('main')

@section('content')
    <section class="adminPanel--sec" style="background-color:#252729;">
        <div class="container-fluid">
            <div class="row">
                {{-- Admin -- Panel -- Side -- Bar --}}
                <div class="col-md-2 sidebar--main p-0">
                    {{-- Side -- Bar -- Here --}}
                    @include('admin.layouts.sideMenu')
                </div>
                {{-- Admin -- Panel -- Page Content --}}
                <div class="col-md-10 dashboard-main p-0">

                    <div class="admin--header mb-4">
                        {{-- Admin -- Header -- Here  --}}
                        @include('admin.layouts.adminHeader')
                    </div>

                    {{-- Pages -- Content -- Here --}}

                    @yield('adminSection');

                </div>
            </div>
        </div>
    </section>
@endsection
