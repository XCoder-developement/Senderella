@extends('admin_layouts.admin_index')
@section('content')
    <!--begin::Card-->
    <div class="container">
        <div class="card card-custom gutter-b">

            <div class="card-header flex-wrap py-3">
                <div class="card-title">
                    <h3 class="card-label">{{ __('messages.countries') }}
                    </h3>
                </div>
                <div class="card-toolbar">


                    <a href="{{ route('countries.create') }}" class="btn btn-light-success font-weight-bold">
                        <i class="ki ki-plus icon-md mr-2"></i>{{ __('messages.add') }}</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->

                {!! $dataTable->table([], true) !!}
                <!--end: Datatable-->


                <!--end::Card-->
            @endsection
            @section('scripts')
                {{ $dataTable->scripts() }}
            @endsection
