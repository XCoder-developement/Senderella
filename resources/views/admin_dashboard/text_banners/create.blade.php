@extends('admin_layouts.admin_index')

@section('content')
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <!-- Your SVG Icon code -->
                        </span>
                    </span>
                    <h3 class="card-label">{{ __('messages.add text_banners') }}</h3>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('text_banners.store') }}" enctype="multipart/form-data">
                    @csrf



                    <!-- Single text field -->
                    <div class="form-group">
                        <label for="text">{{ __('messages.text') }}</label>
                        <input type="text" class="form-control @error('text') is-invalid @enderror"
                               id="text" name="text" value="{{ old('text') }}">
                        @error('single_text')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-shadow btn-primary font-weight-bold mt-5">
                        {{ __('messages.save') }}
                        <span class="svg-icon svg-icon m-0 svg-icon-md">
                            <!-- Your SVG Icon code -->
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
