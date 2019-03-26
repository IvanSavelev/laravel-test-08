<?php

namespace App\Http\Controllers\admin;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
	    $products = Product::paginate(2);
	    return view('admin.catalog.product_list', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
		public function add(Request $request)
		{
			$max = Product::max('product_id');
			return view('admin.catalog.product_form', ['object_id' => $max+1, 'object_type' => 'product']);
		}

  /**
   * Save product
   *
   * @return \Illuminate\Http\Response
   */
  public function save(Request $request)
  {
    $input1 = $request->all();
    $input2 = $request->input();
    return redirect()->route('products.list');
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

      $path = $request->photo->storeAs('images/' . $object_type . '/' . $object_id  , $photo_name, 'public');
      $path = '/storage/' . $path;
      $sort_order_max = DB::table('product_image')->max('sort_order');
      $id = DB::table('product_image')->insertGetId([
        'product_id' => 1,
        'image' => $path,
        'sort_order' => $sort_order_max + 1,
      ]);
    }

    return $path;
  }

  /**
   *
   */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
		public function store(StoreProductRequest $request)
		{
			Product::create($request->all());
			return redirect()->route('products.index')->with(['message' => 'Products added successfully']);
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
			$product = Product::findOrFail($id);
			return view('products.edit', compact('product'));
		}
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
		public function update(StoreProductRequest $request, $id)
		{
			$product = Product::findOrFail($id);
			$product->update($request->all());
			return redirect()->route('products.index')->with(['message' => 'Product updated successfully']);
		}
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
		public function destroy($id)
		{
			$product = Product::findOrFail($id);
			$product->delete();
			return redirect()->route('authors.index')->with(['message' => 'Product deleted successfully']);
		}
	
	public function massDestroy(Request $request)
	{
		$ids = $request->input('ids');
		if($ids) {
			$products = explode(',', $request->input('ids'));
			foreach ($products as $product_id) {
				$product = Product::findOrFail($product_id);
				$product->delete();
			}
			$message = 'Авторы успешно удалены';
		} else {
			$message = 'Нет выбранных авторов';
		}
		
		return redirect()->route('authors.index')->with(['message' => $message]);
	}
	
	
}
