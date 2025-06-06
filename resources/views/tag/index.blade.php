@extends('layouts.app')


@section('content')
<div class="d-flex justify-content-between">
    <h1>{{ __('Tags') }}</h1>
    <div class="mt-1">
        <a href="{{ url('/tag/create') }}" class="btn btn-primary"><i class="las la-plus"></i> {{ __('Create') }}</a>
    </div>
</div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="get" action="{{ url('/tag') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search') }}" value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="las la-search"></i></button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th class="text-center">{{ __('Color') }}</th>
                        <th class="text-center">{{ __('Created at') }}</th>
                        <th class="text-center">{{ __('Updated at') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td class="text-center">{{ $tag->id }}</td>
                            <td>
                                <span class="badge {{ $tag->color ? '' : $bootstrap_colors[array_rand($bootstrap_colors)] }}" 
                                      style="{{ $tag->color ? 'background-color: '.$tag->color : '' }}">
                                    {{ $tag->name }}
                                </span>
                            </td>
                            <td>{{ $tag->description }}</td>
                            <td class="text-center">
                                @if($tag->color)
                                    <span class="badge" style="background-color: {{ $tag->color }}">{{ $tag->color }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $tag->created_at }}</td>
                            <td class="text-center">{{ $tag->updated_at }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ url('/tag/update/'.$tag->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="las la-pen"></i>
                                    </a>
                                    <a href="{{ url('/tag/delete/'.$tag->id) }}" class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('{{ __('Are you sure?') }}')">
                                        <i class="las la-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $tags->links() }}
        </div>
    </div>
@endsection