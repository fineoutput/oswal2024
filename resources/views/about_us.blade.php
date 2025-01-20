@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
    .abts_imngs img {
    border-radius: 20px;
}

.slept_through {
    align-items: center;
    padding: 80px 5%;
}
.about-bgtextss {
    margin: 0;
    padding: 0;
    /* font: bold 150px Arial; */
    /* background: url(abt_img/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg) no-repeat center center; */
    background-size: cover;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: transparent;
    text-align: center;
    /* -webkit-mask-image: url(abt_img/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg); */
    /* mask-image: url(abt_img/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg); */
    font-weight: 800;
    font-family: sans-serif;
}
.bg-2 {
    background-image: url(abt_img/bg-2.png);
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;
}
.bg-1 {
    background-image: url(abt_img/bg-1.png);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
}
.bg-3 {
    background-image: url(abt_img/bg-3.png);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
}

/* //////// */
.tab-button {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 5px;
    background-color: #feb302;
    color: white;
    font-size: 16px;
    width: 300px;
    font-size: 30px;
    /* background: none; */
    cursor: pointer;
    border-bottom: 5px solid black;
}

.tab-button:hover {
    background-color: #f0563b;
}

.tab-content {
    display: none;
    margin-top: 20px;
}

.tab-content.active {
    display: block;
}
.round_name {
    box-shadow: 0px 10px 0px 0px #ee1f71;
}
p.botmname_top {
    /* background-image:url(abt_img/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg); */
    color: #ee1f71;
    text-align: center;
    font-weight: 700;
    font-size: 22px;
    height: 172px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1rem;
    border-radius: 5px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    margin-bottom: 3rem;
}
p.botmname_top:hover {
    background: #ee1f71 !important;
    color: #fff !important;
}
.owner_frame {
    display: flex;
    justify-content: center;
    align-items: center;
	flex-direction: column;
}

/* 
//////////////////Service Section */

.section-services {
	font-family: "Poppins", sans-serif;
	background-color: #e6edf7;
	color: #202020;
	padding-top: 115px;
    padding-bottom: 120px;
}

.section-services .header-section {
	margin-bottom: 34px;
}

.section-services .header-section .title {
	position: relative;
    padding-bottom: 14px;
    margin-bottom: 25px;
	font-weight: 700;
    font-size: 32px;
}

.section-services .header-section .title:before {
	content: "";
	position: absolute;
	bottom: 0;
	left: 50%;
	transform: translateX(-50%);
	width: 50px;
	height: 3px;
	background-color: #ff4500;
    border-radius: 3px;
}

.section-services .header-section .title:after {
	content: "";
	position: absolute;
	bottom: 0;
	left: 50%;
    transform: translateX(30px);
	width: 10px;
	height: 3px;
	background-color: #504f93;
    border-radius: 3px;
}

.section-services .header-section .description {
	font-size: 14px;
    color: #282828;
}

.section-services .single-service {
    position: relative;
    margin-top: 30px;
    background-color: #fff;
    border-radius: 10px;
    padding: 40px 30px;
    overflow: hidden;
}

.section-services .single-service .content {
	position: relative;
	z-index: 20;
}

.section-services .single-service .circle-before {
    position: absolute;
    top: 0;
    right: 0px;
    transform: translate(40%, -40%);
    width: 150px;
    height: 150px;
    background-color: #d81828;
    border: 6px solid #504f93;
    border-radius: 50%;
    opacity: 0.5;
    z-index: 10;
    transition: all .6s;
}

.section-services .single-service:hover .circle-before {
	width: 100%;
	height: 100%;
	transform: none;
	border: 0;
	border-radius: 0;
	opacity: 1;
}

.section-services .single-service .icon {
	display: inline-block;
	margin-bottom: 26px;
    width: 70px;
    height: 70px;
    background-color: #d81828;
    border-radius: 5px;
    line-height: 70px;
    text-align: center;
    color: #fff;
    font-size: 30px;
    transition: all .3s;
}

.section-services .single-service:hover .icon {
	background-color: #fff;
	color: #ff4500;
}

.section-services .single-service .title {
    margin-bottom: 18px;
	font-weight: 700;
    font-size: 23px;
    transition: color .3s;
}

.section-services .single-service:hover .title {
	color: #fff;
}

.section-services .single-service .description {
    margin-bottom: 20px;
    font-size: 14px;
    transition: color .3s;
}

.section-services .single-service:hover .description {
	color: #fff;
}

.section-services .single-service a {
	position: relative;
	font-size: 18px;
    color: #202020;
    text-decoration: none;
    font-weight: 500;
    transition: color .3s;
}

.section-services .single-service:hover a {
	color: #fff;
}

.section-services .single-service a:after {
	content: "";
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	height: 1px;
	background-color: #ff4500;
	transition: background-color .3s;
}

.section-services .single-service:hover a:after {
	background-color: #fff;
}
</style>

<div class="banner_abbts d-flex justify-content-center" style="{{asset('images/banner')}}">
	<h1>About US</h1>
</div>

<div class="container-fluid">
    <div class="row slept_through bg-2" style="background-image: url('{{ asset('images/bg-2.png') }}');">
        <div class="col-lg-4">
            <div class="abts_imngs">
                <img src="{{asset('images/oswal_shop.png')}}" width="100%" alt="">
            </div>
        </div>
        <div class="col-lg-4">
            <h1 class="about-bgtextss" style="background-image: url('{{ asset('images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg') }}'); -webkit-mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);
    mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);">Founding and Early Success</h3>
        </div>
        <div class="col-lg-4">
            <p> Oswal Soap Group was established by Late Shri Uttamchand Deshraj Jain in 1956 in <span class="red">Johari Bazaar, Jaipur.</span> Driven by his hard work and dedication, the soap quickly gained popularity due to its <span class="red">high quality,</span> making it a preferred choice among consumers. The foundation laid by Late Shri Jain was crucial in setting the stage for future growth. </p>
        </div>
    </div>
    <div class="row slept_through bg-1" style="background-image: url('{{ asset('images/bg-1.png') }}');">
        
        <div class="col-lg-4">
            <h1 class="about-bgtextss" style="background-image: url('{{ asset('images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg') }}'); -webkit-mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);
    mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);">Expansion and Growth</h1>
        </div>
        <div class="col-lg-4">
            <p> Following the initial success, Late Shri Uttamchand Deshraj Jain’s sons took significant steps to <span class="red">formalize and expand the business.</span> They registered the company as <span class="red">“Oswal Soap Group”</span> and began handling the operations more systematically. This expansion included setting up <span class="red">production centers and stores</span> to cater to various cities, towns, and villages. </p>
        </div>
        <div class="col-lg-4">
            <div class="abts_imngs">
                <img src="{{asset('images/kachighani_oil.png')}}" width="100%" alt="">
            </div>
        </div>
    </div>
    <div class="row slept_through bg-3" style="background-image: url('{{ asset('images/bg-3.png') }}');">
        <div class="col-lg-4">
            <div class="abts_imngs">
                <img src="{{asset('images/product_diversification.png')}}" width="100%" alt="">
            </div>
        </div>
        <div class="col-lg-4">
            <h1 class="about-bgtextss" style="background-image: url('{{ asset('images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg') }}'); -webkit-mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);
    mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);">Product Diversification</h1>
        </div>
        <div class="col-lg-4">
            <p> In 1995, the third generation of the Deshraj family took over the business, introducing a broader range of products to meet diverse needs. The Oswal Soap Group expanded its offerings to include <span class="red">washing powder, detergent powder, bath soap, cleaning powders,</span> and other daily-use items such as <span class="red">tea, spices, and sanitary products.</span> This diversification aimed to cater to a wider market and enhance consumer satisfaction. </p>
        </div>
    </div>
    <div class="row slept_through bg-2" style="background-image: url('{{ asset('images/bg-2.png') }}');">
        
        <div class="col-lg-4">
            <h1 class="about-bgtextss" style="background-image: url('{{ asset('images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg') }}'); -webkit-mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);
    mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);">Nationwide Presence and Future Plans</h1>
        </div>
        <div class="col-lg-4">
            <p> With its products available at reasonable prices across <span class="red">Rajasthan, Punjab, Haryana, Gujarat, Maharashtra,</span> and Madhya Pradesh, Oswal Soap Group has built a strong presence. The company continues to innovate and plans to <span class="red">launch new products</span> in the near future. Ongoing research and efforts are dedicated to expanding their product line and reaching new markets. </p>
        </div>
        <div class="col-lg-4">
            <div class="abts_imngs">
                <img src="{{asset('images/nationwide.png')}}" width="100%" alt="">
            </div>
        </div>
    </div>
    <div class="row slept_through bg-3" style="background-image: url('{{ asset('images/bg-3.png') }}');">
        <div class="col-lg-4">
            <div class="abts_imngs">
                <img src="{{asset('images/achive.jpeg')}}" width="100%" alt="">
            </div>
        </div>
        <div class="col-lg-4">
            <h1 class="about-bgtextss" style="background-image: url('{{ asset('images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg') }}'); -webkit-mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);
    mask-image: url(images/pngtree-happy-raksha-bandhan-orange-background-vector-illustration-image_364910.jpg);">Industry Leadership and Opportunities</h1>
        </div>
        <div class="col-lg-4">
           <p> With over 65 years of experience and a <span class="red">billion satisfied customers,</span> Oswal Soap Group has become one of the largest manufacturers of daily-use commodities. The company operates through a network of over <span class="red">1000 distributors, 250,000 wholesale retailers,</span> and 800+ employees. As demand grows, Oswal Soap Group is looking to <span class="red">appoint more agencies</span> in various cities. Interested candidates and agencies are encouraged to get in touch to explore opportunities. </p>
        </div>
    </div>
</div>

<div class="container">
	<div class="owner_frame">
		<img src="{{asset('images/oswalowner.jpg')}}" width="170px" alt="">
		<p style="
    /* border: 1px solid #ee1f71; */
    padding: 10px;
    border-radius: 10px;
    background: #ee1f71d6;
    color: #fff;
    font-weight: 800;
">Late Shri Uttam Chand Desraj</p>
	</div>
    <div class="inside_bttns d-flex justify-content-center">
        <button class="tab-button" onclick="showContent('content1')">Our Directors</button>
        <button class="tab-button" onclick="showContent('content2')">Eight Brothers</button>
    </div>

    <div id="content1" class="tab-content">
        <div class="row mt-5 good_e">
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Devendra Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Surendra Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Yatendra Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Virendra Jain Director</p>
                </div>
            </div>
        </div>
    </div>

    <div id="content2" class="tab-content">
        <div class="row newefe " style="position:relative;">

            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Harsh Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Ajay Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12   m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Sanjay Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Achal Jain Director</p>
                </div>
            </div>
    
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Gaurav Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Saurabh Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Shrenik Jain Director</p>
                </div>
            </div>
            <div class="col-md-3 col-12 m-auto">
                <div class="round_name">
                    <p class="botmname_top" style="background-image: url('{{ asset('images/dir.jpg') }}');">Hemank Jain Director</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-services">
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-10 col-lg-8">
					<div class="header-section">
						<h2 class="title">Our Services</h2>
						
					</div>
				</div>
			</div>
			<div class="row">
				<!-- Single Service -->
				<div class="col-md-6 col-lg-3">
					<div class="single-service">
						<div class="content">
							<span class="icon">
							<i class="fa-regular fa-star"></i>
							</span>
							<h2 class="title">Quality Control</h2>
							<p class="description">The primary objective of our organization is to deliver high-quality products at affordable prices in a safe and hygienic environment.</p>
						</div>
						<span class="circle-before"></span>
					</div>
				</div>
				<div class="col-md-6 col-lg-3">
					<div class="single-service">
						<div class="content">
							<span class="icon">
							<i class="fa-solid fa-building-wheat"></i>
							</span>
							<h2 class="title">Infrastructure</h2>
							<p class="description">In order to extend our outreach to the customers, we are continuously developing our infrastructure with well-equipped machines and facilities for our employees.</p>
						</div>
						<span class="circle-before"></span>
					</div>
				</div>
				<!-- / End Single Service -->
				<!-- Single Service -->
				<div class="col-md-6 col-lg-3">
					<div class="single-service">
						<div class="content">
							<span class="icon">
							<i class="fa-solid fa-gear"></i>
							</span>
							<h2 class="title">Manufacturing</h2>
							<p class="description">We are our own competitors, and hence we are always outdoing ourselves to provide our consumers with a wide range of products using highly advanced machinery.</p>
						</div>
						<span class="circle-before"></span>
					</div>
				</div>
				<!-- / End Single Service -->
				<!-- Single Service -->
				<div class="col-md-6 col-lg-3">
					<div class="single-service">
						<div class="content">
							<span class="icon">
							<i class="fa-solid fa-indian-rupee-sign"></i>
							</span>
							<h2 class="title">Reasonable Prices</h2>
							<p class="description">We provide all products on the affordable prices to all customers which give us a long string of happy customers.</p>
						</div>
						<span class="circle-before"></span>
					</div>
				</div>
			
			</div>
		</div>
	</section>

</div>
<script>
    function showContent(contentId) {
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));

    const selectedContent = document.getElementById(contentId);
    selectedContent.classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
    showContent('content1');
});

</script>
@endsection