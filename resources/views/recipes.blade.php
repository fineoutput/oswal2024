@extends('layouts.app')

@section('title', $title ?? '')

@section('content')

<section class="resipie">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="text-center"> Recipes
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-sm-12 col-md-4 ddss">
                <div class="resp-card">
                    <header class="resp-card__header" style=" background-image: url('{{ asset('images/chawal.jpg') }}'); ">
                      <span class="mdi mdi-share resp-card__header__share"></span>
                      <button class="resp-card__header__button">
                        <a href="{{route('video')}}">
                            
                        <span class="mdi mdi-play"><i class="fa-solid fa-play"></i></span>
                        </a>
                      </button>
                      <span class="mdi mdi-dots-horizontal resp-card__header__actions"></span>
                    </header>
                    <main class="resp-card__content">
                      <h2 class="resp-card__content__title">Shadi Pulao Recipe</h2>
                      <span class="resp-card__content__description text-white">1 Jan, 2020 By Oswal</span>
                    </main>
                  </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 ddss">
                <div class="resp-card">
                    <header class="resp-card__header_two" style=" background-image: url('{{ asset('images/tikki.jpg') }}'); ">
                      <span class="mdi mdi-share resp-card__header__share"></span>
                      <button class="resp-card__header__button">
                        <a href="{{route('vido_recipie2')}}">
                            
                        <span class="mdi mdi-play"><i class="fa-solid fa-play"></i></span>
                        </a>
                      </button>
                      <span class="mdi mdi-dots-horizontal resp-card__header__actions"></span>
                    </header>
                    <main class="resp-card__content">
                      <h2 class="resp-card__content__title">Sabudana Tikki Recipe(Vrat Special)</h2>
                      <span class="resp-card__content__description text-white">1 Jan, 2020 By Oswal</span>
                    </main>
                  </div>
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4 ddss">
                <div class="resp-card">
                    <header class="resp-card__header_three" style=" background-image: url('{{ asset('images/paneer.jpg') }}'); ">
                      <span class="mdi mdi-share resp-card__header__share"></span>
                      <button class="resp-card__header__button">
                        <a href="{{route('vido_recipie3')}}">
                            
                        <span class="mdi mdi-play"><i class="fa-solid fa-play"></i></span>
                        </a>
                      </button>
                      <span class="mdi mdi-dots-horizontal resp-card__header__actions"></span>
                    </header>
                    <main class="resp-card__content">
                      <h2 class="resp-card__content__title">Dhaba Paneer Recipe</h2>
                      <span class="resp-card__content__description text-white">1 Jan, 2020 By Oswal</span>
                    </main>
                  </div>
            </div>
        </div>
    </div>
   </section>

   @endsection