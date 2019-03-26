<?php

namespace App\Http\Controllers\admin;

use App\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $authors = Author::paginate(2);
	    return view('admin.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
		public function create()
		{
			return view('authors.create');
		}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
		public function store(StoreAuthorRequest $request)
		{
			Author::create($request->all());
			return redirect()->route('authors.index')->with(['message' => 'Author added successfully']);
		}
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
		public function edit($id)
		{
			$author = Author::findOrFail($id);
			return view('authors.edit', compact('author'));
		}
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
		public function update(StoreAuthorRequest $request, $id)
		{
			$author = Author::findOrFail($id);
			$author->update($request->all());
			return redirect()->route('authors.index')->with(['message' => 'Author updated successfully']);
		}
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
		public function destroy($id)
		{
			$author = Author::findOrFail($id);
			$author->delete();
			return redirect()->route('authors.index')->with(['message' => 'Author deleted successfully']);
		}
	
	public function massDestroy(Request $request)
	{
		$ids = $request->input('ids');
		if($ids) {
			$authors = explode(',', $request->input('ids'));
			foreach ($authors as $author_id) {
				$author = Author::findOrFail($author_id);
				$author->delete();
			}
			$message = 'Авторы успешно удалены';
		} else {
			$message = 'Нет выбранных авторов';
		}
		
		return redirect()->route('authors.index')->with(['message' => $message]);
	}
	
	
}
