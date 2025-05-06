<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Achievement;

class AchievementsController extends Controller
{

    public function index()
    {

        $achievements = Achievement::orderby('id', 'desc')->get();

        return view('admin.Achievements.view-achievements', compact('achievements'));
    }

    public function create(Request $request, $id = null)

    {
        $achievements = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $achievements = Achievement::find(base64_decode($id));
            
        }

        return view('admin.Achievements.add-achievements', compact('achievements'));
    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'title'        => 'required|string',
            'short_desc'    => 'required|string',
            'long_desc'     => 'required|string',
          
        ];

        $request->validate($rules);

        if (!isset($request->achievements_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $achievements = new Achievement;

        } else {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $achievements = Achievement::find($request->achievements_id);
            
            if (!$achievements) {
                
                return redirect()->route('achievements.index')->with('error', 'Achievements not found.');
                
            }

        }
        
        $achievements->fill($request->all());

        if ($request->hasFile('img')) {
            $achievements->image = uploadImage($request->file('img'), 'achievements', 'img');
        }

        $achievements->ip = $request->ip();

        $achievements->url = str_replace( ' ', '-',trim($request->title));

        $achievements->date = now();

        $achievements->added_by = Auth::user()->id;

        $achievements->is_active = 1;

        if ($achievements->save()) {

            $message = isset($request->achievements_id) ? 'Achievements updated successfully.' : 'Achievements inserted successfully.';

            return redirect()->route('achievements.index')->with('success', $message);

        } else {

            return redirect()->route('achievements.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $achievements = Achievement::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $achievements->updateStatus(strval(1));
        } else {

            $achievements->updateStatus(strval(0));
        }

        return  redirect()->route('achievements.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Achievement::where('id', $id)->delete()) {

            return  redirect()->route('achievements.index')->with('success', 'Blog Deleted Successfully.');
        } else {
            return redirect()->route('achievements.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('achievements.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

}