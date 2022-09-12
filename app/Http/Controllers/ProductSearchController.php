<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Response;

class ProductSearchController extends Controller
{
    public function search(Request $request){
    try{ 
        if($request->filled('filter')){   
            $products = Product::Search()->where($request->filter,$request->search)->get();
        } else {
            $products = Product::Search($request->search)->get();
        }

        return new ProductResource($products);
    } catch(\Exception $e) {
        return response()->json([
            'message' => $e->getMessage()
        ],Response::HTTP_INTERNAL_SERVER_ERROR);
    }    
    }

    public function allBooks(){
        try{
            return new ProductResource(Product::all());
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }    
    }
}
