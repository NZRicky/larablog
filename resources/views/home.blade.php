@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <ul class="list-unstyled posts">
                    @foreach ($posts as $post)
                        <li class="media">
                            <div class="media-img">
                                <img class="align-self-start mr-3"
                                     src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQf9-bTvGEsiKFBPWbvEIZ8PHP9rOZy-OWr3ktOJe-bYZ2oPwE0"
                                     alt="">
                            </div>
                            <div class="media-body">
                                <h2 class="mt-0 mb-1 post-title">{{ $post->title }}</h2>
                                @if ($post->is_private)
                                    <span class="badge badge-primary">Private</span>
                                @endif
                                <div class="post-body">{!!  Markdown::convertToHtml($post->content) !!}</div>
                                <div class="tags">
                                    <ul class="list-unstyled">
                                        <li><a class="label">Laravel</a></li>
                                        <li><a class="label">React</a></li>
                                    </ul>
                                </div>

                            </div>
                        </li>
                    @endforeach

                </ul>
                {{ $posts->links() }}
            </div>
            <div class="col-md-4">
                <div class="sidebar-title">Most Popular</div>
                <ul class="list-unstyled posts hot-posts">
                    @foreach ($posts as $post)
                        <li class="media">

                            <div class="media-body">
                                <div class="post-body">{{ $post->title }}</div>
                            </div>
                            <div class="media-img">
                                <img class="align-self-start mr-3"
                                     src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQf9-bTvGEsiKFBPWbvEIZ8PHP9rOZy-OWr3ktOJe-bYZ2oPwE0"
                                     alt="">
                            </div>
                        </li>
                    @endforeach

                </ul>
            </div>

        </div>

    </div>
@endsection
