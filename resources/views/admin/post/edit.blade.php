@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="row title-bar">
                    <div class="col-6 page-name">
                        <h1>{{ $title }}</h1>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ $route }}">
                    @if (isset($post))
                        @method('PATCH')
                    @endif
                    @csrf
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title"
                               value="{{ old('title', (isset($post) ? $post->title : '')) }}">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="content"
                                  placeholder="">{{ old('content', (isset($post) ? $post->content : '')) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <input type="text" class="form-control" name="tags"
                               value="{{ old('tags', (isset($post) ? $post->tags : '')) }}">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" value='true' name="is_private"
                        @if ($errors->any())
                            {{ old('is_private') ? 'checked' : '' }}
                        @else
                            {{ isset($post) && $post->is_private ? 'checked' : '' }}
                        @endif
                        >
                        <label class="form-check-label">Private?</label>

                    </div>
                    <button class="btn btn-primary" type="submit">Post</button>
                </form>
            </div>
        </div>
    </div>
@endsection


