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

                    <!-- Image field -->
                    <div class="form-group">
                        <label for="image">{{ __('messages.image') }}</label>
                        <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Single link field -->
                    <div class="form-group">
                        <label for="link">{{ __('messages.link') }}</label>
                        <input type="text" class="form-control @error('link') is-invalid @enderror"
                               id="link" name="link" value="{{ old('link') }}">
                        @error('single_link')
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
