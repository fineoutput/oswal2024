@extends('layouts.app')

@section('title', 'Home')

@section('content')

    @include('partials.homeparts.banner')

    @include('partials.homeparts.about')

    @include('partials.homeparts.tranding-products')

    @include('partials.homeparts.advertisers')

    @include('partials.homeparts.featured-products')

    @include('partials.homeparts.sliders')

    @include('partials.homeparts.hotdeal-products')

    @include('partials.homeparts.testimonials')

    @include('partials.homeparts.insta')

@endsection