@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <ul class="list-unstyled posts">
                @foreach ($posts as $post)
                <li class="media">
                    <div class="media-body">
                        <h2 class="mt-0 mb-1">{{ $post->title }}</h2>
                        {{ $post->content }}
                    </div>
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</div>
@endsection
