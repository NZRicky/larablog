@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row title-bar">
                <div class="col-6 page-name">
                    <h1>Blog Lists</h1>
                </div>
                <div class="col-6 buttons">
                    <button type="button" class="btn btn-success float-right" data-link="{{ route('add_post') }}">Add Post</button>
                </div>
            </div>

        </div>
    </div>


</div>
@endsection

