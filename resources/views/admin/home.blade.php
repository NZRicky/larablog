@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row title-bar">
                    <div class="col-6 page-name">
                        <h2>Posts</h2>
                    </div>
                    <div class="col-6 buttons">
                        <button type="button" class="btn btn-success float-right btn-sm"
                                data-link="{{ route('admin.posts.create') }}">Add Post
                        </button>
                    </div>
                </div>

            </div>

            @if (session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            <div class="col-12">
                <table class="table table-sm">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Created At</th>
                        <th scope="col" class="text-right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td scope="row">{{ $post->id }}</td>
                            <td scope="row">{{ $post->title }}</td>
                            <td scope="row">{{ $post->created_at }}</td>
                            <td scope="row" class="text-right">
                                <button class="btn btn-primary btn-sm" type="button"
                                        data-link="{{ route('admin.posts.edit',['id' => $post->id]) }}">Edit
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        data-link-destroy="{{ route('admin.posts.destroy',['id' => $post->id]) }}">Del
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>
@endsection

