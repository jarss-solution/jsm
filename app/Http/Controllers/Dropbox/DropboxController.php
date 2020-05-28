<?php

namespace App\Http\Controllers\Dropbox;

use Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DropboxController extends Controller
{
	public function index() {
		if(env('DROPBOX_AUTH_TOKEN')) {
			$all_files = Storage::disk('dropbox')->allFiles();
		} else {
			$all_files = [];
		}

		return view('dropbox.index', compact('all_files'));
	}

	public function delete($file) {
		Storage::disk('dropbox')->delete($file);

		return redirect()->back();
	}


	public function upload(Request $request) {
		try {
			//Upload file to dropbox
			$file_src= $request->file("file"); //file src
			$file_name = $file_src->getClientOriginalName();
  			$is_file_uploaded = Storage::disk('dropbox')->putFileAs(null, $file_src, $file_name);
		} catch (Exception $e) {
			
		}

		return redirect()->back();
	}
}
