<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\IssueTimeLog;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $_notify_message = "User saved.";
    private $_notify_type = "success";
    private $_user_image_location = 'uploads/user';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', 1)->orderBy('created_at', 'desc')->get();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $data = $request->all();
            $data['password'] = bcrypt($request['password']);
            $image = $this->uploadProfilePicture($request);
            $image ? $data['image'] = $image : false ;

            $user = User::create($data);
        } catch (Exception $e) {
            $this->_notify_message = "Failed to create user, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->route('user.index', 'jarss')->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    public function uploadProfilePicture($request, $user = false) {
        if($request->hasFile('image')) {
            if($user && is_file($this->_user_image_location. '/' .$user->image)) {
                unlink($this->_user_image_location. '/' .$user->image);
            }

            $file = $request->file('image');
            $fileName = time() ."-". $file->getClientOriginalName();
            $fileName = str_replace(' ', '-', $fileName);

            $image = $this->_user_image_location. '/' .$fileName;
            $upload_success= $file->move($this->_user_image_location, $fileName);

            $upload = Image::make($image);
            $upload->fit(200, 200)->save($this->_user_image_location .'/'. $fileName, 100);
            
            return $fileName;
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user = User::findOrFail($id);

        //Set start and end
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);

        $this_week = true;
        if($request->start && $request->end) {
            $this_week = false;
        }
        $request['start'] = $request->start ?: Carbon::now()->startOfWeek();
        $request['end'] = $request->end ?: Carbon::now()->endOfWeek();


        $time_logs = IssueTimeLog::with('project')
            ->where('user_id', $id)
            ->whereBetween('created_at', [$request['start'], $request['end']])
            ->groupBy('project_id')
            ->selectRaw('sum(time_log) as total_time, project_id')
            ->get()
            ->toArray();

        return view('user.show', compact('user', 'time_logs', 'this_week'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        if(request()->user()->role == 1) {
            $roles = [ 1=>'Admin', 2=>'Normal' ];
        } else {
            $roles = [ 2=>'Normal' ];
        }
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'confirmed',
        ]);
        
        try {
            $data = $request->all();
            if($request->password) {
                $data['password'] = bcrypt($request['password']);
            } else {
                unset($data['password']);
            }

            $user = User::findOrFail($id);
            $image = $this->uploadProfilePicture($request, $user);
            $image ? $data['image'] = $image : false ;
            $user->update($data);
        } catch (Exception $e) {
            $this->_notify_message = "Failed to save user, Try again.";
            $this->_notify_type = "danger";
        }

        return redirect()->back()->with([
            'notify_message' => $this->_notify_message,
            'notify_type' => $this->_notify_type,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = 0;
            $user->save();
        } catch (Exception $e) {
            
        }

        return redirect()->back()->with([
            'notify_message' => 'User deleted.',
            'notify_type' => 'success'
        ]);
    }
}
