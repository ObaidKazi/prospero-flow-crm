@extends('layouts.app')

@section('header')
    <h1>{{ $tag->id ? __('Update Tag') : __('Create Tag') }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="post" action="{{ url('/tag/save') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $tag->id }}">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name', $tag->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="color">{{ __('Color') }}</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="las la-palette"></i></span>
                            <input type="color" name="color" id="color" class="form-control form-control-lg @error('color') is-invalid @enderror"
                                   value="{{ old('color', $tag->color ?? '#3498db') }}">
                            @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description">{{ __('Description') }}</label>
                        <textarea name="description" id="description" class="form-control form-control-lg @error('description') is-invalid @enderror"
                                  rows="3">{{ old('description', $tag->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> {{ __('Save') }}
                        </button>
                        <a href="{{ url('/tag') }}" class="btn btn-outline-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection