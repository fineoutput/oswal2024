<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{

    public function index()
    {

        $blogs = Blog::orderby('id', 'desc')->get();

        return view('admin.blogs.view-blog', compact('blogs'));
    }

    public function create(Request $request, $id = null)

    {
        $blog = null;

        if ($id !== null) {

            $admin_position = $request->session()->get('position');

            if ($admin_position !== "Super Admin" && $admin_position !== "Admin") {

                return redirect()->route('blog.index')->with('error', "Sorry You Don't Have Permission To edit Anything.");

            }

            $blog = Blog::find(base64_decode($id));
            
        }

        return view('admin.blogs.add-blog', compact('blog'));
    }

    public function store(Request $request)

    {
        // dd($request->all());

        $rules = [
            'title'        => 'required|string',
            'auther'       => 'required|string',
            'short_des'    => 'required|string',
            'long_des'     => 'required|string',
            'keywords'     => 'nullable|string',
            'meta'         => 'nullable|string',
          
        ];

        $request->validate($rules);

        if (!isset($request->replay_id)) {

            $rules['img'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $blog = new Blog;

        } else {

            $rules['img'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

            $blog = Blog::find($request->blog_id);
            
            if (!$blog) {
                
                return redirect()->route('blog.index')->with('error', 'blog not found.');
                
            }

        }
        
        $blog->fill($request->all());

        if (!$request->has('meta')) {
            $blog->meta = null;
        }

        if ($request->hasFile('img')) {
            $blog->image = uploadImage($request->file('img'), 'blog', 'img');
        }

        $blog->ip = $request->ip();

        $blog->url = str_replace( ' ', '-',trim($request->title));

        $blog->date = now();

        $blog->added_by = Auth::user()->id;

        $blog->is_active = 1;

        if ($blog->save()) {

            $message = isset($request->blog_id) ? 'Blog updated successfully.' : 'Blog inserted successfully.';

            return redirect()->route('blog.index')->with('success', $message);

        } else {

            return redirect()->route('blog.index')->with('error', 'Something went wrong. Please try again later.');

        }
    }

    public function update_status($status, $id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        $blog = Blog::find($id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $blog->updateStatus(strval(1));
        } else {

            $blog->updateStatus(strval(0));
        }

        return  redirect()->route('blog.index')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('blog.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }

    public function destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (Blog::where('id', $id)->delete()) {

            return  redirect()->route('blog.index')->with('success', 'Blog Deleted Successfully.');
        } else {
            return redirect()->route('blog.index')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('blog.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function view_desc($id)
    {
        $id = base64_decode($id);

        $blog = Blog::where('id', $id)->select('short_des', 'long_des')->first();

        return view('admin.blogs.view-des', compact('blog'));
    }

    public function view_comment()
    {
      
        $comments = DB::table('comments')->orderBy('id', 'Desc')->get();

        return view('admin.blogs.comment.view-comment', compact('comments'));
    }

    public function add_replay($id=null) {

        $id = base64_decode($id);

        $comment = DB::table('comments')->where('id',  $id)->first();

        $replay = null;

        return view('admin.blogs.comment.replay', compact('comment' ,'replay'));
    }

    public function edit_replay($id=null) {

        $id = base64_decode($id);

        $comment = DB::table('comments')->join('replay_comments' ,'replay_comments.comment_id' ,'=' ,'comments.id')->select('comments.comment' ,'replay_comments.*')->where('comments.id',  $id)->first();

        $replay = 1;

        return view('admin.blogs.comment.replay', compact('comment' ,'replay'));
    }

    public function replay_store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'reply' => 'required|string',      
        ];
    
        $request->validate($rules);
    
        $replayData = [
            'comment_id' => $request->comment_id,
            'name' => $request->name,
            'reply' => $request->reply,
            'ip' => $request->ip(),
            'cur_date' => now(),
            'added_by' => Auth::user()->id,
        ];
    
        if (!isset($request->replay_id)) {

            $inserted = DB::table('replay_comments')->insert($replayData);

            if ($inserted) {

                DB::table('comments')->where('id' , $request->comment_id)->update(['reply_status' => 1]);

                return redirect()->route('blog.view-comment')->with('success', 'Replay inserted successfully.');

            } else {

                return redirect()->route('blog.view-comment')->with('error', 'Something went wrong. Please try again later.');

            }

        } else {
         
            $replay = DB::table('replay_comments')->where('id', $request->replay_id)->first();
    
            if (!$replay) {

                return redirect()->route('blog.view-comment')->with('error', 'Replay not found.');

            }
    
            $updated = DB::table('replay_comments')->where('id', $request->replay_id)->update($replayData);
    
            if ($updated) {

                return redirect()->route('blog.view-comment')->with('success', 'Replay updated successfully.');

            } else {

                return redirect()->route('blog.view-comment')->with('error', 'Something went wrong. Please try again later.');

            }

        }

    }

    public function comment_destroy($id, Request $request)

    {

        $id = base64_decode($id);

        $admin_position = $request->session()->get('position');

        // if ($admin_position == "Super Admin") {

        if (DB::table('comments')->where('id', $id)->delete()) {

            DB::table('replay_comments')->where('comment_id', $id)->delete();

            return  redirect()->route('blog.view-comment')->with('success', 'Comment Deleted Successfully.');

        } else {
            
            return redirect()->route('blog.view-comment')->with('error', 'Some Error Occurred.');

        }

        // } else {

        // 	return  redirect()->route('blog.index')->with('error', "Sorry You Don't Have Permission To Delete Anything.");

        // }

    }

    public function commentUpdatestatus($status, $id, Request $request)

    {

        $id = base64_decode($id);
    
        $admin_position = $request->session()->get('position');

        $comment = DB::table('comments')->where('id' , $id);

        // if ($admin_position == "Super Admin") {

        if ($status == "active") {

            $comment->update(['is_active' => 1]);
        } else {

            $comment->update(['is_active' => 0]);
        }

        return  redirect()->route('blog.view-comment')->with('success', 'Status Updated Successfully.');

        // } else {

        // 	return  redirect()->route('blog.index')->with('error', "Sorry you dont have Permission to change admin, Only Super admin can change status.");

        // }

    }
}