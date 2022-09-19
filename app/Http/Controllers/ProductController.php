<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Response;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index','show']);
    }

    public function index()
    {
        try{
           return new ProductResource(Product::paginate(10,['id','title','image','author','genre','publisher','published']));
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try{
        if($request->hasFile('image')){    
            $path = $request->file('image')->store('public/books');
        }
        
        $product = Product::create([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'isbn' => $request->isbn,
            'publisher' => $request->publisher,
            'published' => $request->published,
            'image' => $path,
            'description' => $request->description,
        ]);
            
        return new ProductResource($product);
        } catch (\Exception $e){
            return response()->json(
                ['message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if($product){
            return new ProductResource($product);
        } else {
            return response()->json([
                'message' => 'Book not found'
            ],Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return new ProductResource($product);        
    }

    public function productUpdate(ProductRequest $request, $id)
    {
        try{
        $product = Product::find($id);
        
        if($request->hasFile('image')){
            $path = $request->file('image')->store('public/books');
            $product->image = $path;
        }
        
        $product->title = $request->title;
        $product->author = $request->author;
        $product->genre = $request->genre;
        $product->isbn = $request->isbn;
        $product->published = $request->published;
        $product->publisher = $request->publisher;
        $product->description = $request->description;
        
        $product->save();

        return new ProductResource($product);        
        } catch (\Exception $e){
            return response()->json(
                ['message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
