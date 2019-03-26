<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	public function login()
	{
		return view('admin.login');
	}
	
	public function create_login()
	{
		return view('admin.create_login');
	}
	
	public function post_login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		
		if (Auth::attempt($credentials,  true)) {
			// Authentication passed...
		//	return redirect()->intended('admin/authors');
			return redirect()->route('admin.dashboard');
			//return redirect()->intended('admin/content');
		}//Временно убрал
		return view('admin.login');
	}
	
	public function dashboard(Request $request)
	{
		
		return view('admin.dashboard');
	}
	
	
	public  function send_file(Request $request) {
		if ($request->file('photo')->isValid()) {
			$photo_file = $request->photo->getClientOriginalName();
			$photo_filename = pathinfo($photo_file, PATHINFO_FILENAME);
			$photo_filename = str_slug($photo_filename, '-'); //Translator
			$photo_extension = $request->photo->extension();
			$photo_name = $photo_filename . '.' . time() . '.' . $photo_extension;
			
			$object_id = $request->input('object_id');
			$object_type = $request->input('object_type');
			
			$path = $request->photo->storeAs('images/wysiwyg/' . $object_type . '/' . $object_id  , $photo_name, 'public');
			$path = '/storage/' . $path;
		}
		return $path;
	}
	
	
	/**
	 * Переводит текст в url
	 * @param $text
	 *
	 * @return string
	 */
	private function _translit($text)
	{
		if(!$text) return '';
		return \strtolower(\preg_replace('~[\s\-]+~', '-', \trim(\preg_replace("~[^0-9\w\s\-\_]+~", "", $this->romanize($text)))));
	}
	/**
	 * translate local alphabet to ascii charset using mappings from lang file
	 * @param string $text input text
	 * @return string $text with local characters replaced
	 */
	private function romanize($text)
	{
		$search = \explode(' ', _fetch('_romanize_from_'));
		$replace = \explode(' ', _fetch('_romanize_to_'));
		return \str_replace($search, $replace, $text);
	}
}
