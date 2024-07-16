@extends('admin.base_template')

@section('main')

    <div class="content">

        <div class="container-fluid">

            <div class="row">

                <div class="col-sm-12">

                    <div class="page-title-box">

                        <h4 class="page-title">View Category</h4>

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item active">Product Major Category</li>

                        </ol>

                    </div>

                </div>

            </div>

            <!-- end row -->

            <div class="page-content-wrapper">

                <div class="row">

                    @foreach ($product_categorys as $product_category)

                        <div class="col-xl-4 col-md-6">

                            <div class="card bg-danger mini-stat position-relative">

                                <a href="{{ route('product.index', encrypt($product_category['id']))}}">

                                    <div class="card-body">

                                        <div class="mini-stat-desc">
                                            
                                            <div class="text-white">

                                                <h3 class="mb-3 mt-3 text-center">{{ $product_category['category_name'] }}</h3>

                                            </div>

                                        </div>

                                    </div>

                                </a>

                            </div>

                        </div>

                    @endforeach
                   
                </div>
                
            </div> <!-- container-fluid -->

        </div> <!-- content -->

    </div>

@endsection