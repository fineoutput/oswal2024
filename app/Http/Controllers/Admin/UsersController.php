<?php
namespace App\Http\Controllers\Admin;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Popupimage;

class UsersController extends Controller
{


    public function popupimage(Request $request){
        $data['popup'] = Popupimage::orderBy('id','DESC')->get();
        return view('admin/popupimage',$data);
    }

    public function addpopupimage(Request $request)
{
    if ($request->method() == 'POST') {

        // Optional validation for image fields
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'web_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // Initialize the variables to hold the image paths, defaulting to null
        $imagePath = null;
        $webimagePath = null;

        // Check if the main image is uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Generate unique name for main image

            // Move the uploaded image to the 'uploads/popup_images' directory
            $image->move(public_path('uploads/popup_images'), $imageName);

            // Store the image path
            $imagePath = 'uploads/popup_images/' . $imageName;
        }

        // Check if the web image is uploaded
        if ($request->hasFile('web_image')) {
            $webimage = $request->file('web_image');
            $webimageName = time() . '_' . uniqid() . '.' . $webimage->getClientOriginalExtension(); // Generate unique name for web image

            // Move the uploaded web image to the 'uploads/popup_images' directory
            $webimage->move(public_path('uploads/popup_images'), $webimageName);

            // Store the web image path
            $webimagePath = 'uploads/popup_images/' . $webimageName;
        }

        // Create a new record in the Popupimage table
        $popupImage = Popupimage::create([
            'image' => $imagePath,    // This will be null if no image is uploaded
            'web_image' => $webimagePath, // This will be null if no web image is uploaded
        ]);

        // Redirect back with a success message
        return redirect('admin/popupimage')->with('success', 'Image file uploaded successfully');
    }

    // Return the view for adding a popup image
    return view('admin.addpopupimage');
}


public function destroypopup($id)
    {
        $popupImage = Popupimage::find($id);

        if ($popupImage) {
            Storage::delete($popupImage->image);
            $popupImage->delete();

            return redirect()->back()->with('success', 'Image deleted successfully');
        }

        // In case the image doesn't exist, redirect with error message
        return redirect()->back()->with('error', 'Image not found');
    }

    public function editpopup($id)
    {
        $popup = Popupimage::findOrFail($id); // Find the popup image by ID
        return view('admin.editpopupimage', compact('popup'));
    }

    // Update the popup image in the database
    public function updatepopup(Request $request, $id)
    {
        // $request->validate([
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate main image
        //     'web_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'  // Validate web image
        // ]);
    
        // Find the existing popup image record
        $popup = Popupimage::findOrFail($id);

        // Handle Image Removal if the checkbox is checked
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Delete the old main image if exists
            $oldImagePath = public_path('uploads/popup_images/' . basename($popup->image));
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
            // Set the image field to null
            $popup->image = null;
        }
    
        // Process the 'image' if a new one is uploaded
        if ($request->hasFile('image')) {
            // Process the new main image if uploaded
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension(); // Generate unique name for the new image
            $image->move(public_path('uploads/popup_images'), $imageName);
            // Update the image path in the database
            $popup->image = 'uploads/popup_images/' . $imageName;
        }
    
        // Handle Web Image Removal if the checkbox is checked
        if ($request->has('remove_web_image') && $request->remove_web_image == '1') {
            // Delete the old web image if exists
            $oldWebImagePath = public_path('uploads/popup_images/' . basename($popup->web_image));
            if (file_exists($oldWebImagePath)) {
                unlink($oldWebImagePath); // Delete the old web image
            }
            // Set the web_image field to null
            $popup->web_image = null;
        }
    
        // Process the 'web_image' if a new one is uploaded
        if ($request->hasFile('web_image')) {
            // Process the new web image if uploaded
            $webimage = $request->file('web_image');
            $webimageName = time() . '_' . uniqid() . '.' . $webimage->getClientOriginalExtension(); // Generate unique name for the new web image
            $webimage->move(public_path('uploads/popup_images'), $webimageName);
            // Update the web image path in the database
            $popup->web_image = 'uploads/popup_images/' . $webimageName;
        }
    
        // Save the updated record
        $popup->save();
    
    
        // Redirect with a success message
        return redirect('admin/popupimage')->with('success', 'Popup image updated successfully!');
    }

    public function updateStatus($id)
{
    // Find the popup image record by its ID
    $popup = Popupimage::findOrFail($id);

    // Toggle the status between 'active' and 'inactive'
    $popup->status = ($popup->status == '1') ? '2' : '1';

    // Save the updated status
    $popup->save();

    // Redirect with a success message
    return redirect()->back()->with('success', 'Image status updated successfully!');
}
    
    
    public function index()
    {
        
        $currentRouteName = Route::currentRouteName();

        if($currentRouteName == 'user.vendor.approve') {

            $pageTittle = 'Vendor Users';

            $users = User::with('vendor')->where('role_type', 2)->where('is_active', 1)->orderBy('id', 'desc')->get();

            return view('admin.Vendor.view-user', compact('users', 'pageTittle'));

        }elseif($currentRouteName == 'user.index'){

            $pageTittle = 'Customer Users';

            $users = User::where('role_type', '!=' , 2)->orderBy('id', 'desc')->get();

            return view('admin.Users.view-user', compact('users', 'pageTittle'));

        }elseif($currentRouteName == 'user.vendor.pending') {

            $pageTittle = 'Vendor Users';

            $users = User::with('vendor')->where('role_type', 2)->where('is_active', 0)->orderBy('id', 'desc')->get();

            return view('admin.Vendor.view-user', compact('users', 'pageTittle'));

        }

    }

    public function create(Request $request, $id = null)

    {
        $user = null;
      
        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin") {

                return redirect()->route('user.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $user = User::find(base64_decode($id));
        }

        return view('admin.Users.add-user', compact('user'));
    }


    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'first_name'              => 'required',
            'email'                   => 'required|email',
            'contact'                 => 'required|digits:10',
            'password'                => 'nullable',
            'img'                     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ];

        if (!isset($request->user_id)) {

            $user = new User;

        } else {

            $user = User::find($request->user_id);
            
            if (!$user) {
                
                return redirect()->route('user.index')->with('error', 'User not found.');
                
            }

        }

        $request->validate($rules);

        $user->fill($request->all());

        if ($request->password) {

            $user->password = Hash::make($request->password);

        }

        if($request->hasFile('img')){

            $user->image = uploadImage($request->file('img'), 'User');

        }

        $user->first_name_hi = lang_change($request->first_name);

        $user->ip = $request->ip();

        $user->date = now();

        $user->added_by = Auth::user()->id;

        $user->is_active = 1;

        if ($user->save()) {

            $message = isset($request->user_id) ? 'User updated successfully.' : 'User inserted successfully.';

            return redirect()->route('user.index')->with('success', $message);

        } else {

            return redirect()->route('user.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $currentRouteName = Route::currentRouteName();

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $user = User::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $user->updateStatus(strval(1));
        } else {

            $user->updateStatus(strval(0));
        }

        if($currentRouteName == 'user.vendor.update-status'){

            return  redirect()->route('user.vendor.approve')->with('success', 'Status Updated Successfully.');

        }else{

            return  redirect()->route('user.index')->with('success', 'Status Updated Successfully.');
        }

        // } else {

        // 	return  redirect()->route('user.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (User::where('id', $id)->delete()) {

            return  redirect()->route('user.index')->with('success', 'User Deleted Successfully.');
        } else {
            return redirect()->route('user.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('user.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function updateWallet(Request $request) {

        $request->validate([
            'wallet_amount' => 'required|numeric',
            'type'          => 'required|in:credit,debit',
            'description'   => 'required|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);

        $transactionData = [
            'user_id' =>  $user->id,
            'transaction_type' => $request->type, 
            'amount' => $request->wallet_amount,
            'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'status' => WalletTransactionHistory::STATUS_COMPLETED,
            'description' => $request->description,
        ];
        
        WalletTransactionHistory::createTransaction($transactionData);
 
        if ($request->type == 'credit') {
            $user->wallet_amount += $request->wallet_amount;
        } else {
            $user->wallet_amount -= $request->wallet_amount;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'Wallet amount updated successfully!');
    }
}