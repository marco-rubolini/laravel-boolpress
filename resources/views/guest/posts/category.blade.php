@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1>Post nella categoria: {{ $category->name}}</h1>
            <h2>Ciao {{ $nome ?? '' }}</h2>
            <ul>
                @foreach ($posts as $post)
                    <li>
                        <a href="{{ route('posts.show', ['slug' => $post->slug])}}">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
