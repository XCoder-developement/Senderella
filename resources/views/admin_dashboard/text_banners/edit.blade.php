@extends('admin_layouts.admin_index')
@section('content')
    <div class="container">
        <div class="card card-custom gutter-b">

            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <!-- Your file-plus SVG icon here -->
                        </span>
                    </span>
                    <h3 class="card-label"> {{ __('messages.edit text_banner') }}</h3>
                </div>
            </div>

            <div class="card-body">
                <form method="post" action="{{ route('text_banners.update', $text_banner->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Image Upload -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="image">{{ __('messages.image') }}</label>
                                <input type="file" class="form-control-file" id="image" name="image">
                                <!-- You may also display the current image here if needed -->
                                <!-- <img src="{{ $text_banner->image_path }}" alt="Current Image"> -->
                                @error('image')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Link Input -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="link">{{ __('messages.link') }}</label>
                                <input
                                    class="form-control @error('link') is-invalid @enderror"
                                    required value="{{ $text_banner->link ?? '' }}"
                                    name="link">
                                @error('link')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-shadow btn-primary font-weight-bold mt-5">
                        {{ __('messages.save') }}
                        <span class="svg-icon svg-icon m-0 svg-icon-md">
                            <!-- Your angle-double-left SVG icon here -->
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
