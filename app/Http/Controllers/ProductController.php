<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Exceptions\ProductNotBelongsToUser;
use Auth;

use App\Http\Resources\Product\ProductResource;

use App\Http\Resources\Product\ProductCollection;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return ProductCollection::collection(Product::paginate(20)) ;
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
        //
        // return $request->all();
        $product = new Product;
        $product->name = $request->name;
        $product->detail = $request->description;
        $product->price=$request->price;
        $product->stock=$request->stock;
        $product->discount=$request->discount;

        $product->save();

        return response([
            'data'=>new ProductResource($product)
        ],201);
        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product  $product
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
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->ProductCheckUser($product);
        $request['detail']=$request->description;
        unset($request->description);
        $product->update($request->all());

        return response([
            'data'=>new ProductResource($product)
        ],Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $this->ProductCheckUser($product);
        $product->delete();

        return response(null ,Response::HTTP_NO_CONTENT);        
    }
    
    public function ProductCheckUser($product)
    {
        if (Auth::id()!== $product->user_id) {
            throw new ProductNotBelongsToUser;
            
        }
        
    }
}
