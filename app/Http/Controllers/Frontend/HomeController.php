<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\EcomCategory;

use App\Models\EcomProduct;

use App\Models\ContactUs;

use App\Models\Carrier_contact;

use App\Models\DealerEnquiry;
use Illuminate\Support\Facades\DB;

use App\Models\Address;
use App\Models\VisitedCategory;
use App\Models\VisitedUsers;
use App\Models\Popupimage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


use App\Models\Type;
use App\Models\UserActivity;

class HomeController extends Controller
{
    // ============================= START INDEX ============================ 
    public function index(Request $request)
    {   
        $ipAddress = $request->ip();
        
        $currentDate = Carbon::now()->toDateString();

        if (!$request->session()->has('visited_ip') || $request->session()->get('visited_ip') !== $ipAddress) {
            
            $existingVisit = VisitedUsers::where('ip_address', $ipAddress)
                ->whereDate('visited_at', $currentDate)
                ->first();

            if (!$existingVisit) {
                VisitedUsers::create([
                    'ip_address' => $ipAddress,
                    'visited_at' => now(),
                ]);

                $request->session()->put('visited_ip', $ipAddress);
            }
        }
        $data['latestPopupImage'] = Popupimage::where('status','1')->latest()->first();

        return view('index',$data)->with('title', 'Oswal');
    }

    public function storecategory(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'ip_address' => 'required|ip',
            'visited_at' => 'required|date',
        ]);

        // Store the visit in the database
        VisitedCategory::create([
            'category_id' => $request->category_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'ip_address' => $request->ip_address,
            'visited_at' => $request->visited_at,
        ]);

        // Return a success response
        return response()->json(['message' => 'Visit recorded successfully!']);
    }

    public function category(Request $request,$type=null)
    {
        
        return view('products.category', compact('type'))->with('title', 'Category List');
    }
    public function find_shop(Request $request)
    {

        return view('find_shop')->with('title', 'find_shop');
    }
    
    public function services(Request $request)
    {

        return view('services')->with('title', 'services');
    }

    public function dealer_enq(Request $request)
    {

        return view('dealer_enq')->with('title', 'dealer_enq');
    }
    public function manufacture(Request $request)
    {

        return view('manufacture')->with('title', 'manufacture');
    }
    public function contact(Request $request)
    {

        return view('contact')->with('title', 'contact');
    }
    public function recipes(Request $request)
    {

        return view('recipes')->with('title', 'recipes');
    }
    public function video(Request $request)
    {

        return view('video')->with('title', 'video');
    }
    public function all_products(Request $request)
    {

        return view('all_products')->with('title', 'all_products');
    }
    public function vido_recipie2(Request $request)
    {

        return view('vido_recipie2')->with('title', 'vido_recipie2');
    }
    public function vido_recipie3(Request $request)
    {

        return view('vido_recipie3')->with('title', 'vido_recipie3');
    }
    
    public function privacy_policy(Request $request)
    {

        return view('privacy_policy')->with('title', 'privacy_policy');
    }
    public function terms_conditions(Request $request)
    {

        return view('terms_conditions')->with('title', 'terms_conditions');
    }
    public function about_us(Request $request)
    {

        return view('about_us')->with('title', 'about_us');
    }
    public function career(Request $request)
    {

        return view('career')->with('title', 'career');

    }
    public function achivements1(Request $request)
    {

        return view('achivements1')->with('title', 'achivements1');
    }
    
    public function contact_us(Request $request){
        $request -> validate([
            'name'=> 'required|string',
            'phone'=> 'required|numeric|min:11',
            'email'=> 'required|string|email',
            'message'=> 'required|string|max:255'

        ]);
        $data = new ContactUs;

        $data->fname = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->message = $request->message;
        $data->ip = $request->ip();

        $data->save();

        return redirect()->route('contact')->with('success', 'Message sent succesfully');

    }
    
    public function career_contact(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|numeric', 
            'email' => 'required|string|email',
            'message' => 'required|string|max:255'
        ]);
    
        $data = new Carrier_contact;
    
        $data->fname = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->message = $request->message;
        $data->ip = $request->ip();
    
        $data->save();
    
        return redirect()->route('career')->with('success', 'Message sent successfully');
    }

    public function dealer_contact( Request $request ){
        $request->validate([
            'name' => 'required|string',
            'age' => 'nullable|integer',
            'qualification' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'district' => 'nullable|string',
            'firmname' => 'nullable|string',
            'firmaddress' => 'nullable|string',
            'businessname' => 'nullable|string',
            'businessexperience' => 'nullable|string',
            'businesstype' => 'nullable|string',
            'mobile' => 'nullable|string',
            'annualturnover' => 'nullable|numeric',
            'type' => 'nullable|string',
            'vehicle' => 'nullable|string',
            'vehicle_count' => 'nullable|integer',
            'manpower' => 'nullable|integer',
            'capacity' => 'nullable|string',
            'gstcertificate' => 'nullable',
            'agencyName' => 'nullable|string',
            'details' => 'nullable|string',
            'businessbrief' => 'nullable|string',
        ]);

        if($request->hasFile('gstcertificate')){

            $gstfile = uploadImage($request->file('gstcertificate'), 'gstcertificate');
        }

        $data= new DealerEnquiry;
        
        $data->name = $request->name;
        $data->age = $request->age;
        $data->qualification = $request->qualification;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->district = $request->district;
        $data->firmname = $request->firmname;
        $data->file = $gstfile;
        $data->firmaddress = $request->firmaddress;
        $data->businessname = $request->businessname;
        $data->businessexperience = $request->businessexperience;
        $data->businesstype = $request->businesstype;
        $data->mobile = $request->mobile;
        $data->annualturnover = $request->annualturnover;
        $data->type = $request->type;
        $data->vehicle = $request->vehicle;
        $data->manpower = $request->manpower;
        $data->ip = $request->ip();
    
        $data->save();

        return redirect()->route('dealer_enq')->with('success', 'Message sent successfully');
    }

    public function productDetail(Request $request, $slug)
    {
        $product = EcomProduct::where('url', $slug)->first();

        // Agar product null hai, toh empty images array bhej do
        $images = [];

        if ($product) {
            for ($i = 1; $i <= 4; $i++) {
                $images[] = [
                    'img' => $product->{"img$i"},
                ];
            }
        }

        return view('products.productdetails', compact('product', 'images'))
                ->with('title', $product ? 'Product Details' : 'Product Not Found');
    }

    // public function productDetail(Request $request, $slug)
    // {
    //     $product = EcomProduct::where('url', $slug)->first();

    //     $images = [];

    //     for ($i = 1; $i <= 4; $i++) {
    //         $images[] = [
    //             'img' => $product->{"img$i"}, 
    //         ];
    //     }

    //     return view('products.productdetails', compact('product', 'images'))->with('title', 'Product Details');
    // }

    public function renderProducts($slug, $type = null)
    {
        $products = [];

        $categoryDetails = [
            'description' => null,
            'banner_image' => null,
            'category_name' => null,
        ];

        if ($type === 'category' && $slug) {
            $category = EcomCategory::where('url', $slug)->first();
            

            if ($category) {
                $products = sendProduct($category->id, false, false, false, false, false, false, 6);

                $categoryDetails = [
                    'description' => $category->long_desc ?? null,
                    'banner_image' => $category->image ? asset($category->image) : null,
                    'category_name' => $category->name,
                ];
                $this->storeCategoryVisit($category->id);
            }
            
        } elseif ($type === 'search' && $slug) {
            $products = sendProduct(false, false, false, false, false, $slug, false, 6);
        }

        
        // dd($products);
        $htmlProducts = view('products.partials.product-list', compact('products'))->render();
        $htmlPagination = $products->links('vendor.pagination.bootstrap-4')->render();

        return response()->json([
            'categoryDetails' => $categoryDetails,
            'products' => $htmlProducts,
            'pagination' => $htmlPagination,
        ]);
       
    }

    private function storeCategoryVisit($categoryId)
    {
        $ipAddress = request()->ip();
        $currentDate = Carbon::today();
        
        // Check if the user has already visited this category today
        $existingVisit = VisitedCategory::where('ip_address', $ipAddress)
            ->where('category_id', $categoryId)
            ->whereDate('visited_at', $currentDate)
            ->first();
        
        // Get the latest state and city data
        $statedata = DB::table('user_state_city')->latest()->first();
        
        if ($existingVisit) {
            // Use existing state_id and city_id from the existing visit
            $state_id = $existingVisit->state_id;
            $city_id = $existingVisit->city_id;
        } else {
            // If no previous visit, use the latest state and city from the user_state_city table
            $state_id = $statedata->state_id ?? null;
            $city_id = $statedata->city_id ?? null;
        
            // Store the visit record if it doesn't already exist
            $visitedCategory = new VisitedCategory();
            $visitedCategory->ip_address = $ipAddress;
            $visitedCategory->category_id = $categoryId;
            $visitedCategory->state_id = $state_id;
            $visitedCategory->city_id = $city_id;
            $visitedCategory->visited_at = Carbon::now();
            $visitedCategory->save();
        }
        
    }
    


    public function renderProduct(Request $request)

    {

        $currentRouteName = Route::currentRouteName();

        $typeId     = $request->type_id;

        $product_id = $request->product_id;

        $stateId = view()->shared('globalState');

        $cityId  = view()->shared('globalCity');

        $seltedType = Type::Where('id', $typeId)->where('product_id' , $product_id)->first();

        $productType = Type::where('product_id', $product_id)->where('state_id', $stateId)->where('city_id', $cityId)->get();

        $product = sendProduct(false, $product_id, false, false, false, false, false, false)[0];
    
        if($currentRouteName == 'getproduct'){
            
            $htmlwebProduct = view('products.partials.render.webproduct', compact('product','productType','seltedType'))->render();
    
            $htmlmobProduct = view('products.partials.render.mobileproduct', compact('product','productType','seltedType'))->render();

        }elseif($currentRouteName == 'home.getproduct') {

            $htmlwebProduct = view('partials.homeparts.render.webproduct', compact('product','productType','seltedType'))->render();
    
            $htmlmobProduct = view('partials.homeparts.render.mobileproduct', compact('product','productType','seltedType'))->render();

        }

        return response()->json(['webproduct' => $htmlwebProduct ,'mobproduct' => $htmlmobProduct]);
    }

    // public function getAddress(Request $request, $place = null) {

    //     $addresses = Address::where('user_id', Auth::User()->id)->get();

    //     $address_data = [];

    //     foreach ($addresses as $address) {

    //         $address->load('states', 'citys');

    //         $addr_string = "Doorflat {$address->doorflat}, ";

    //         if (!empty($address->landmark)) {
    //             $addr_string .= "{$address->landmark}, ";
    //         }
    //         $addr_string .= "{$address->address}, {$address->location_address}, {$address->zipcode}";

    //         $address['custom_address'] = $addr_string;

    //         $address_data[] =  $address;
    //     }
        
    //     if(session()->has('address_id') && $place == null){
    //         return redirect()->route('checkout.process');
    //     }else{

    //         return view('selectaddress' , compact('address_data','place'));
    //     }
            
    // }

    public function getAddress(Request $request, $place = null) {

        $ipAddress = $request->ip();
        
        $currentDate = Carbon::now()->toDateString();

            $existingVisit = UserActivity::where('ip_address', $ipAddress)->where('status', 3)
            ->whereDate('created_at', $currentDate)
                ->first();

            if (!$existingVisit) {
                UserActivity::create([
                    'ip_address' => $ipAddress,
                    'status' => 3,
                ]);

                $request->session()->put('visited_ip', $ipAddress);
            }


        // Get all addresses for the logged-in user
        // return Auth::user()->id;
        // return $request;
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        // Initialize an array to hold the address data
        $address_data = [];
    
        // Check if the user has any existing addresses
        if ($addresses->isNotEmpty()) {
            // Load related data (states, cities) for each address
            foreach ($addresses as $address) {
                $address->load('states', 'citys');
    
                // Create a custom address string
                $addr_string = "Doorflat {$address->doorflat}, ";
    
                if (!empty($address->landmark)) {
                    $addr_string .= "{$address->landmark}, ";
                }
                $addr_string .= "{$address->address}, {$address->location_address}, {$address->zipcode}";
    
                $address['custom_address'] = $addr_string;
    
                // Add the address to the address_data array
                $address_data[] = $address;
            }
        }
    
        // If no address exists, provide an empty array
        if ($addresses->isEmpty()) {
            $address_data = []; // Ensure it's an empty array, not null
        }
    
        // Return the view with the address data
        return view('selectaddress', compact('address_data', 'place'));
    }

    public function Search(Request $request) {

        $query = $request->input('query');

        if (empty($query)) {
            return redirect()->back()->with('error', 'Search query cannot be empty.');
        }
    
        return view('all_products', compact('query'));
    }
    
}
