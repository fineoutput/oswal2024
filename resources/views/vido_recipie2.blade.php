
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
                <iframe width="100%" height="500px" src="https://www.youtube.com/embed/H9p8zmKsjiI?si=kG3z0KC8OGNNGVel" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    @endsection