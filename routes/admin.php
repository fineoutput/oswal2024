<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\TeamController;

use App\Http\Controllers\Admin\CrmController;

use App\Http\Controllers\Auth\AdminLoginController;

use App\Http\Controllers\Admin\Ecommerce\CategoryController;

use App\Http\Controllers\Admin\Ecommerce\ProductController;

use App\Http\Controllers\Admin\Ecommerce\TypeController;

use App\Http\Controllers\Admin\Ecommerce\ShipingChargeController;

use  App\Http\Controllers\Admin\Major\MajorCategoryController;

use App\Http\Controllers\Admin\Major\MajorProductController;

use App\Http\Controllers\Admin\Dealer\DealerController;

use App\Http\Controllers\Admin\RetailShopController;

use App\Http\Controllers\Admin\EmailController;

use App\Http\Controllers\Admin\BlogController;

use App\Http\Controllers\Admin\ContactusController;

use App\Http\Controllers\Admin\HomeController;

use App\Http\Controllers\Admin\AchievementsController;

use App\Http\Controllers\Admin\PromocodeController;

use App\Http\Controllers\Admin\PushNotificationController;

use App\Http\Controllers\Admin\SliderController;

use App\Http\Controllers\Admin\OfferSliderfirstController;

use App\Http\Controllers\Admin\OfferSliderSecoundController;

use App\Http\Controllers\Admin\FooterImageController;

use App\Http\Controllers\Admin\WebSliderController;

use App\Http\Controllers\Admin\WebSliderSecoundController;

use App\Http\Controllers\Admin\RecentController;

use App\Http\Controllers\Admin\TrendingController;

use App\Http\Controllers\Admin\ThemeTrendingController;

use App\Http\Controllers\Admin\RewardController;

use App\Http\Controllers\Admin\UsersController;

use App\Http\Controllers\Admin\GiftCardController;

use App\Http\Controllers\Admin\GiftCardSecController;

use App\Http\Controllers\Admin\ComboProductController;

use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\Admin\Delivery\DeliveryBoyController;


/*========= Home Routes ========*/

Route::prefix('home')->name('home.')->group(function () {

    Route::get('career', [HomeController::class, 'career'])->name('career');

    Route::get('carrer-destroy/{id}', [HomeController::class, 'carrerDestroy'])->name('destroy');

    Route::get('Popup/view-popup', [HomeController::class, 'view_popup'])->name('view-popup');

    Route::get('Popup/create/{id?}', [HomeController::class, 'popupCreate'])->name('create-popup');

    Route::post('Popup/store', [HomeController::class, 'popupStore'])->name('store-popup');

    Route::get('Popup/update-status/{status}/{id}', [HomeController::class, 'Popup_update_status'])->name('popup-update-status');

    Route::get('view-gift-promo', [HomeController::class, 'view_gift_promo'])->name('view-gift-promo');

    Route::get('view-gift-promo/status//{status}/{id}', [HomeController::class, 'gift_promo_status'])->name('gift-promo-status');

    

});

Route::prefix('setting')->name('setting.')->group(function() {

    Route::any('set-constant' ,[HomeController::class, 'constant'])->name('constant');

    Route::any('set-crm' ,[HomeController::class, 'crm'])->name('crm');

});

Route::get('/index', [TeamController::class, 'admin_index'])->name('admin_index');

Route::get('/logout', [AdminLoginController::class, 'admin_logout'])->name('admin_logout');

Route::get('/profile', [AdminLoginController::class, 'admin_profile'])->name('admin_profile');

Route::get('/view_change_password', [AdminLoginController::class, 'admin_change_pass_view'])->name('view_change_password');

Route::post('/admin_change_password', [AdminLoginController::class, 'admin_change_password'])->name('admin_change_password');


// Admin Team routes

Route::get('/view_team', [TeamController::class, 'view_team'])->name('view_team');

Route::get('/add_team_view', [TeamController::class, 'add_team_view'])->name('add_team_view');

Route::post('/add_team_process', [TeamController::class, 'add_team_process'])->name('add_team_process');

Route::get('/UpdateTeamStatus/{status}/{id}', [TeamController::class, 'UpdateTeamStatus'])->name('UpdateTeamStatus');

Route::get('/deleteTeam/{id}', [TeamController::class, 'deleteTeam'])->name('deleteTeam');


// Admin CRM settings routes

Route::get('/add_settings', [CrmController::class, 'add_settings'])->name('add_settings');

Route::get('/view_settings', [CrmController::class, 'view_settings'])->name('view_settings');

Route::get('/update_settings/{id}', [CrmController::class, 'update_settings'])->name('update_settings');

Route::post('/add_settings_process', [CrmController::class, 'add_settings_process'])->name('add_settings_process');

Route::post('/update_settings_process/{id}', [CrmController::class, 'update_settings_process'])->name('update_settings_process');

Route::get('/deletesetting/{id}', [CrmController::class, 'deletesetting'])->name('deletesetting');

/*=========Ecommerce Category Routes ========*/

Route::prefix('ecom/category')->name('category.')->group(function () {

    Route::get('index', [CategoryController::class, 'index'])->name('index');

    Route::get('create/{id?}', [CategoryController::class, 'create'])->name('create');

    Route::post('store', [CategoryController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [CategoryController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');

});

/*=========Ecommerce Products Routes ========*/

Route::prefix('ecom/product')->name('product.')->group(function () {

    Route::get('category', [ProductController::class, 'category'])->name('category');

    Route::get('index/{id?}', [ProductController::class, 'index'])->name('index');

    Route::get('create/{id?}', [ProductController::class, 'create'])->name('create');

    Route::post('store', [ProductController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [ProductController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{pid}/{id}', [ProductController::class, 'destroy'])->name('destroy');

});

/*=========Ecommerce Type Routes ========*/

Route::prefix('ecom/type')->name('type.')->group(function () {

    Route::get('index/{pid}/{cid}/{pcid}', [TypeController::class, 'index'])->name('index');

    Route::get('create/{pid}/{cid}/{pcid}', [TypeController::class, 'create'])->name('create');

    Route::get('edit/{pid}/{cid}/{pcid}/{tid}', [TypeController::class, 'edit'])->name('edit');
  
    Route::post('store', [TypeController::class, 'store'])->name('store');

    Route::get('update-status/{pid}/{cid}/{pcid}/{tid}/{status}', [TypeController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{pid}/{cid}/{pcid}/{tid}', [TypeController::class, 'destroy'])->name('destroy');

    Route::post('update-city-type' , [TypeController::class , 'updateCityType'])->name('update_city_type');

    Route::get('update-all/{pid}/{cid}/{pcid}' , [TypeController::class , 'updateAll'])->name('update_all');

    Route::post('update-all-data' ,[TypeController::class, 'updateAllData'])->name('update-all-data');

    Route::get('change-product-price' ,[TypeController::class, 'changeProductPrice'])->name('change-product-price');
    
});


/*=========Ecommerce vendor Type Routes ========*/

Route::prefix('ecom/vendor/type')->name('vendor.type.')->group(function () {

    Route::get('index/{pid}/{cid}/{pcid}', [TypeController::class, 'vendorIndex'])->name('index');

    Route::get('create/{pid}/{cid}/{pcid}', [TypeController::class, 'vendorCreate'])->name('create');

    Route::get('edit/{pid}/{cid}/{pcid}/{tid}', [TypeController::class, 'vendorEdit'])->name('edit');
  
    Route::post('store', [TypeController::class, 'VendorStore'])->name('store');

    Route::get('update-status/{pid}/{cid}/{pcid}/{tid}/{status}', [TypeController::class, 'vendor_update_status'])->name('update-status');

    Route::get('destroy/{pid}/{cid}/{pcid}/{tid}', [TypeController::class, 'vendor_destroy'])->name('destroy');

    Route::post('update-city-type' , [TypeController::class , 'updateCityType'])->name('update_city_type');

    Route::get('update-all/{pid}/{cid}/{pcid}' , [TypeController::class , 'updateAll'])->name('update_all');

    Route::post('update-all-data' ,[TypeController::class, 'updateAllData'])->name('update-all-data');

    Route::get('change-product-price' ,[TypeController::class, 'changeProductPrice'])->name('change-product-price');
    
});

/*=========Ecommerce Shiping Charges Routes ========*/

Route::prefix('ecom/shipping-charge')->name('shipping-charge.')->group(function () {

    Route::get('index', [ShipingChargeController::class, 'index'])->name('index');

    Route::get('create', [ShipingChargeController::class, 'create'])->name('create');

    Route::get('get-city', [ShipingChargeController::class, 'getCity'])->name('getcity');

    Route::get('edit/{id}', [ShipingChargeController::class, 'edit'])->name('edit');
  
    Route::post('store', [ShipingChargeController::class, 'store'])->name('store');

    Route::get('update-status/{id}/{status}', [ShipingChargeController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [ShipingChargeController::class, 'destroy'])->name('destroy');

    Route::get('create-city', [ShipingChargeController::class, 'create_city'])->name('create-city');

    Route::post('store-city', [ShipingChargeController::class, 'store_city'])->name('store-city');

    Route::get('set-all-shipping-charges' , [ShipingChargeController::class , 'setAllshippingCharges'])->name('set-all-shipping-charges');

    Route::post('shipping-charges-store' , [ShipingChargeController::class , 'store_shipping_charge'])->name('shipping-charges-store');

});

/*=========Ecommerce view Cart Route ========*/

Route::get('ecom/cart/view-cart' , [ProductController::class, 'view_cart'])->name('cart.view-cart');

/*=========Major Categor  Route ========*/

Route::prefix('major/category')->name('majorcategory.')->group(function () {

    Route::get('index', [MajorCategoryController::class, 'index'])->name('index');

    Route::get('create/{id?}', [MajorCategoryController::class, 'create'])->name('create');

    Route::post('store', [MajorCategoryController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [MajorCategoryController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [MajorCategoryController::class, 'destroy'])->name('destroy');

});

/*=========Major Products Routes ========*/

Route::prefix('major/product')->name('majorproduct.')->group(function () {

    Route::get('index', [MajorProductController::class, 'index'])->name('index');

    Route::get('create/{id?}', [MajorProductController::class, 'create'])->name('create');

    Route::post('store', [MajorProductController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [MajorProductController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [MajorProductController::class, 'destroy'])->name('destroy');

});

/*=========Dealer  Routes ========*/

Route::prefix('dealer')->name('dealer.')->group(function () {

    Route::get('index', [DealerController::class, 'index'])->name('index');

    Route::get('create/{id?}', [DealerController::class, 'create'])->name('create');

    Route::post('store', [DealerController::class, 'store'])->name('store');

    Route::get('destroy/{id}', [DealerController::class, 'destroy'])->name('destroy');

    Route::get('dealer-enquiry', [DealerController::class, 'dealer_enquiry'])->name('dealer-enquiry');

});


/*=========Major Retails Routes ========*/

Route::prefix('shops')->name('shop.')->group(function () {

    Route::get('index', [RetailShopController::class, 'index'])->name('index');

    Route::get('create/{id?}', [RetailShopController::class, 'create'])->name('create');

    Route::post('store', [RetailShopController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [RetailShopController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [RetailShopController::class, 'destroy'])->name('destroy');

});


/*=========Emails Routes ========*/

Route::prefix('emailer')->name('email.')->group(function () {

    Route::get('index', [EmailController::class, 'index'])->name('index');

    Route::get('send-mail', [EmailController::class, 'send_mail'])->name('send-mail');

    Route::post('store', [EmailController::class, 'store'])->name('store');

    Route::get('destroy/{id}', [EmailController::class, 'destroy'])->name('destroy');

});


/*=========Blog Routes ========*/

Route::prefix('blogs')->name('blog.')->group(function () {

    Route::get('index', [BlogController::class, 'index'])->name('index');

    Route::get('create/{id?}', [BlogController::class, 'create'])->name('create');

    Route::post('store', [BlogController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [BlogController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [BlogController::class, 'destroy'])->name('destroy');

    Route::get('view-desc/{id}', [BlogController::class, 'view_desc'])->name('view-desc');

    Route::get('comments' , [BlogController::class, 'view_comment'])->name('view-comment');

    Route::get('add-replay/{id?}', [BlogController::class, 'add_replay'])->name('add-replay');

    Route::get('edit-replay/{id?}', [BlogController::class, 'edit_replay'])->name('edit-replay');

    Route::post('replay-store', [BlogController::class, 'replay_store'])->name('replay-store');

    Route::get('comment-destroy/{id}', [BlogController::class, 'comment_destroy'])->name('comment-destroy');

    Route::get('comment-update-status/{status}/{id}', [BlogController::class, 'commentUpdatestatus'])->name('comment-update-status');
    
});


/*=========Contact us Routes ========*/

Route::prefix('contact-us')->name('contact-us.')->group(function () {

    Route::get('index', [ContactusController::class, 'index'])->name('index');

    Route::get('send-reply/{id}', [ContactusController::class, 'send_reply'])->name('send-reply');

    Route::post('store', [ContactusController::class, 'store'])->name('store');

    Route::get('destroy/{id}', [ContactusController::class, 'destroy'])->name('destroy');

});

/*=========Achievements Routes ========*/

Route::prefix('achievements')->name('achievements.')->group(function () {

    Route::get('index', [AchievementsController::class, 'index'])->name('index');

    Route::get('create/{id?}', [AchievementsController::class, 'create'])->name('create');

    Route::post('store', [AchievementsController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [AchievementsController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [AchievementsController::class, 'destroy'])->name('destroy');

});

/*=========Promocode Routes ========*/

Route::prefix('promocode')->name('promocode.')->group(function () {

    Route::get('index', [PromocodeController::class, 'index'])->name('index');

    Route::get('create/{id?}', [PromocodeController::class, 'create'])->name('create');

    Route::post('store', [PromocodeController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [PromocodeController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [PromocodeController::class, 'destroy'])->name('destroy');

});

/*=========Notifaction Routes ========*/

Route::prefix('notification')->name('notification.')->group(function () {

    Route::get('index', [PushNotificationController::class, 'index'])->name('index');

    Route::get('create/{id?}', [PushNotificationController::class, 'create'])->name('create');

    Route::post('store', [PushNotificationController::class, 'store'])->name('store');

});


/*=========Festival Slider Routes ========*/

Route::prefix('slider')->name('slider.')->group(function () {

    Route::get('index', [SliderController::class, 'index'])->name('index');

    Route::get('create/{id?}', [SliderController::class, 'create'])->name('create');

    Route::get('get-product',[SliderController::class ,'GetProduct'])->name('get-product');

    Route::post('store', [SliderController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [SliderController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [SliderController::class, 'destroy'])->name('destroy');

});

/*=========Offer Sliders First Routes ========*/

Route::prefix('offer-slider-1')->name('offersliderfirst.')->group(function () {

    Route::get('index', [OfferSliderfirstController::class, 'index'])->name('index');

    Route::get('create/{id?}', [OfferSliderfirstController::class, 'create'])->name('create');

    Route::post('store', [OfferSliderfirstController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [OfferSliderfirstController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [OfferSliderfirstController::class, 'destroy'])->name('destroy');

});


/*=========Offer Sliders Secound Routes ========*/

Route::prefix('offer-slider-2')->name('offerslidersecound.')->group(function () {

    Route::get('index', [OfferSliderSecoundController::class, 'index'])->name('index');

    Route::get('create/{id?}', [OfferSliderSecoundController::class, 'create'])->name('create');

    Route::post('store', [OfferSliderSecoundController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [OfferSliderSecoundController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [OfferSliderSecoundController::class, 'destroy'])->name('destroy');

});


/*=========Footer Image Routes ========*/

Route::prefix('footer-image')->name('footerimage.')->group(function () {

    Route::get('index', [FooterImageController::class, 'index'])->name('index');

    Route::get('create/{id?}', [FooterImageController::class, 'create'])->name('create');

    Route::post('store', [FooterImageController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [FooterImageController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [FooterImageController::class, 'destroy'])->name('destroy');

});


/*=========Web Slider Routes ========*/

Route::prefix('web-slider')->name('webslider.')->group(function () {

    Route::get('index', [WebSliderController::class, 'index'])->name('index');

    Route::get('create/{id?}', [WebSliderController::class, 'create'])->name('create');

    Route::post('store', [WebSliderController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [WebSliderController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [WebSliderController::class, 'destroy'])->name('destroy');

});

/*=========Web Slider Secound Routes ========*/

Route::prefix('web-slider-2')->name('webslidersecound.')->group(function () {

    Route::get('index', [WebSliderSecoundController::class, 'index'])->name('index');

    Route::get('create/{id?}', [WebSliderSecoundController::class, 'create'])->name('create');

    Route::post('store', [WebSliderSecoundController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [WebSliderSecoundController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [WebSliderSecoundController::class, 'destroy'])->name('destroy');

});

/*=========Recent Routes ========*/

Route::prefix('recent')->name('recent.')->group(function () {

    Route::get('index', [RecentController::class, 'index'])->name('index');

    Route::get('create/{id?}', [RecentController::class, 'create'])->name('create');

    Route::post('store', [RecentController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [RecentController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [RecentController::class, 'destroy'])->name('destroy');

});


/*=========Trending Routes ========*/

Route::prefix('trending')->name('trending.')->group(function () {

    Route::get('index', [TrendingController::class, 'index'])->name('index');

    Route::get('create/{id?}', [TrendingController::class, 'create'])->name('create');

    Route::post('store', [TrendingController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [TrendingController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [TrendingController::class, 'destroy'])->name('destroy');

});

/*=========ThemeTrending Routes ========*/

Route::prefix('theme-trending')->name('theme-trending.')->group(function () {

    Route::get('index', [ThemeTrendingController::class, 'index'])->name('index');

    Route::get('create/{id?}', [ThemeTrendingController::class, 'create'])->name('create');

    Route::post('store', [ThemeTrendingController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [ThemeTrendingController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [ThemeTrendingController::class, 'destroy'])->name('destroy');

    Route::get('destroy/{id}', [ThemeTrendingController::class, 'destroy'])->name('destroy');

});

/*=========Stickers Routes ========*/

Route::prefix('rewards')->name('reward.')->group(function () {

    Route::get('index', [RewardController::class, 'index'])->name('index');

    Route::get('create/{id?}', [RewardController::class, 'create'])->name('create');

    Route::post('store', [RewardController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [RewardController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [RewardController::class, 'destroy'])->name('destroy');

});

/*=========Users Routes ========*/

Route::prefix('user')->name('user.')->group(function () {

    Route::get('index', [UsersController::class, 'index'])->name('index');

    Route::get('vendor/approve', [UsersController::class, 'index'])->name('vendor.approve');

    Route::get('vendor/pending', [UsersController::class, 'index'])->name('vendor.pending');

    Route::get('create/{id?}', [UsersController::class, 'create'])->name('create');

    Route::post('store', [UsersController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [UsersController::class, 'update_status'])->name('update-status');

    Route::get('vendor/update-status/{status}/{id}', [UsersController::class, 'update_status'])->name('vendor.update-status');

    Route::get('destroy/{id}', [UsersController::class, 'destroy'])->name('destroy');

   Route::post('update-wallet', [UsersController::class, 'updateWallet'])->name('update-wallet');

});


/*=========Gift Cards Routes ========*/

Route::prefix('gift-card')->name('gift-card.')->group(function () {

    Route::get('index', [GiftCardController::class, 'index'])->name('index');

    Route::get('create/{id?}', [GiftCardController::class, 'create'])->name('create');

    Route::post('store', [GiftCardController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [GiftCardController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [GiftCardController::class, 'destroy'])->name('destroy');

});


/*=========Gift Cards Routes ========*/

Route::prefix('gift-card-1')->name('gift-card-1.')->group(function () {

    Route::get('index', [GiftCardSecController::class, 'index'])->name('index');

    Route::get('create/{id?}', [GiftCardSecController::class, 'create'])->name('create');

    Route::get('get-type', [GiftCardSecController::class, 'getType'])->name('get-type');

    Route::post('store', [GiftCardSecController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [GiftCardSecController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [GiftCardSecController::class, 'destroy'])->name('destroy');

});


/*=========Combo Product Routes ========*/

Route::prefix('combo-product')->name('comboproduct.')->group(function () {

    Route::get('index', [ComboProductController::class, 'index'])->name('index');

    Route::get('create/{id?}', [ComboProductController::class, 'create'])->name('create');

    Route::get('get-type', [ComboProductController::class, 'getType'])->name('get-type');

    Route::post('store', [ComboProductController::class, 'store'])->name('store');

    Route::get('update-status/{status}/{id}', [ComboProductController::class, 'update_status'])->name('update-status');

    Route::get('destroy/{id}', [ComboProductController::class, 'destroy'])->name('destroy');

});


/*=========Order Routes ========*/

Route::prefix('order')->name('order.')->group(function () {

    Route::get('new', [OrderController::class, 'index'])->name('new-order');

    Route::get('dispatched', [OrderController::class, 'index'])->name('dispatched-order');

    Route::get('completed', [OrderController::class, 'index'])->name('completed-order');

    Route::get('rejected', [OrderController::class, 'index'])->name('rejected-order');


    Route::get('vendor/new', [OrderController::class, 'VendorIndex'])->name('vendor.new-order');

    Route::get('vendor/dispatched', [OrderController::class, 'VendorIndex'])->name('vendor.dispatched-order');

    Route::get('vendor/completed', [OrderController::class, 'VendorIndex'])->name('vendor.completed-order');

    Route::get('vendor/rejected', [OrderController::class, 'VendorIndex'])->name('vendor.rejected-order');


    Route::get('update-status/{id}/{status}', [OrderController::class, 'update_status'])->name('update-status');

    Route::get('vendor/update-status/{id}/{status}', [OrderController::class, 'update_status'])->name('vendor.update-status');


    Route::get('view-product/{id}', [OrderController::class, 'view_product'])->name('view-product');

    Route::get('vendor/view-product/{id}', [OrderController::class, 'view_product'])->name('vendor.view-product');


    Route::get('view-bill/{id}', [OrderController::class, 'view_bill'])->name('view-bill');

    Route::get('vendor/view-bill/{id}', [OrderController::class, 'view_bill'])->name('vendor.view-bill');


    Route::get('view-delivery-challan/{id}', [OrderController::class, 'deliveryChallan'])->name('view-delivery-challan');

    Route::get('vendor/view-delivery-challan/{id}', [OrderController::class, 'deliveryChallan'])->name('vendor.view-delivery-challan');

    Route::get('destroy/{id}', [ComboProductController::class, 'destroy'])->name('destroy');

    Route::get('vendor/destroy/{id}', [ComboProductController::class, 'destroy'])->name('vendor.destroy');

});


/*=========Users Routes ========*/

Route::prefix('delivery')->name('delivery.')->group(function () {

    Route::get('boy/index', [DeliveryBoyController::class, 'index'])->name('index');

    Route::get('boy/create/{id?}', [DeliveryBoyController::class, 'create'])->name('create');

    Route::post('boy/store', [DeliveryBoyController::class, 'store'])->name('store');

    Route::get('boy/update-status/{status}/{id}', [DeliveryBoyController::class, 'update_status'])->name('update-status');

    Route::get('boy/destroy/{id}', [DeliveryBoyController::class, 'destroy'])->name('destroy');

    Route::get('boy/order/{id}', [DeliveryBoyController::class, 'Order'])->name('order');

});