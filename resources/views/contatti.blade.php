@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Contattaci</h1>
            <form action="{{ route('contact.store')}}" method="post">
                @csrf
                <div class="form-group">
                    <input class="form-control" type="text" name="name" placeholder="Nome">
                </div>
                <div class="form-group">
                    <input class="form-control" type="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="message" placeholder="messaggio"></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Invia messaggio">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
