@extends('layout')
@section('content')
    <div class="flex justify-center items-center mt-50">
        <div class="headline text-center mb-5">
            The PM <strong>{{$fsid}}</strong> is already connected to noiseaware.
        </div>
        <div class="text-center">
            <a class="btn btn-primary" href="javascript:void(0);" onclick="window.close();">Back</a>
        </div>
    </div>
@endsection
