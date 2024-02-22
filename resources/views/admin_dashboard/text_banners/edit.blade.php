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


                        <!-- Link Input -->
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="text">{{ __('messages.text') }}</label>
                                <input
                                    class="form-control @error('text') is-invalid @enderror"
                                    required value="{{ $text_banner->text ?? '' }}"
                                    name="text">
                                @error('text')
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
