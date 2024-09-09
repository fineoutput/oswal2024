@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<div class="video_sect">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center">Shadi Pulao Recipe</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-12">
                    <iframe id="video" width="100%" height="500px" src="https://www.youtube.com/embed/t0daO1VJWss" frameborder="0" allowfullscreen=""></iframe>
                </div>
            </div>
        </div>
    </div>

    @endsection