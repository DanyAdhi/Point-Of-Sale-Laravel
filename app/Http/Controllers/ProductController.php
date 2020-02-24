<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

use File;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('name', 'asc')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'code'          => 'required|string|min:2|max:10|unique:products',
            'name'          => 'required|string|min:2|max:50|unique:products',
            'description'   => 'nullable|string|max:150',
            'stok'          => 'required|integer',
            'price'         => 'required|integer',
            'category_id'   => 'required|exists:categories,id',
            'photo'         => 'nullable|image|mimes:jpg,png,jpeg',
        ])->validate();

        try {
            //default $photo = null
            $photo = null;
            //jika terdapat file (Foto / Gambar) yang dikirim
            if ($request->hasFile('photo')) {
                //maka menjalankan method saveFile()
                $photo = $this->saveFile($request->name, $request->file('photo'));
            }
            //Simpan data ke dalam table products
            $product = Product::create([
                'code'          => $request->code,
                'name'          => $request->name,
                'description'   => $request->description,
                'stok'          => $request->stok,
                'price'         => $request->price,
                'category_id'   => $request->category_id,
                'photo'         => $photo
            ]);
            
            //jika berhasil direct ke produk.index
            return redirect(route('products.index'))->with(['success' => '<strong>' . $product->name . '</strong> has been saved']);
        } catch (\Exception $e) {
            //jika gagal, kembali ke halaman sebelumnya kemudian tampilkan error
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

   
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::OrderBy('name', 'asc')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    
    public function update(Request $request, $id)
    {
        
        \Validator::make($request->all(), [
            'code'          => 'required|string|min:2|max:10|exists:products,code',
            'name'          => 'required|string|min:2|max:50',
            'description'   => 'nullable|string|max:150',
            'stok'          => 'required|integer',
            'price'         => 'required|integer',
            'category_id'   => 'required|exists:categories,id',
            'photo'         => 'nullable|image|mimes:jpg,png,jpeg',
        ])->validate();

        try {
            //default $photo = null
            $product    = Product::findOrFail($id);
            $photo      = $product->photo;
            //jika terdapat file (Foto / Gambar) yang dikirim
            if ($request->hasFile('photo')) {
                !empty($photo) ? File::delete(public_path('admin/img/products/'.$product->photo)) : null;
                //maka menjalankan method saveFile()
                $photo = $this->saveFile($request->name, $request->file('photo'));
            }
            //Simpan data ke dalam table products
            $product->update([
                'name'          => $request->name,
                'description'   => $request->description,
                'stok'          => $request->stok,
                'price'         => $request->price,
                'category_id'   => $request->category_id,
                'photo'         => $photo
            ]);
            
            //jika berhasil direct ke produk.index
            return redirect(route('products.index'))->with(['success' => '<strong>' . $product->name . '</strong> has been updated']);
        } catch (\Exception $e) {
            //jika gagal, kembali ke halaman sebelumnya kemudian tampilkan error
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

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
        if(!empty($product->photo)){
            File::delete(public_path('admin/img/products/'.$product->photo));
        }
        $product->delete();
        return redirect()->back()->with('success', '<strong>' . $product->name . '</strong> has been deleted!');
    }


    private function saveFile($name, $photo){
        //set nama file adalah gabungan antara nama produk dan time(). Ekstensi gambar tetap dipertahankan
        // $images = str_slug($name) . time() . '.' . $photo->getClientOriginalExtension();  //versi 5.8
        $images = \Str::slug($name . time()).'.'.$photo->getClientOriginalExtension(); //versi 6.0
        //set path untuk menyimpan gambar
        $path = public_path('admin/img/products');
        //cek jika uploads/product bukan direktori / folder
        if(!File::isDirectory($path)) {
            //maka folder tersebut dibuat
            File::makeDirectory($path, 0777, true, true);
        } 
        //simpan gambar yang diuplaod ke folrder uploads/produk
        Image::make($photo)->save($path . '/' . $images);
        //mengembalikan nama file yang ditampung divariable $images
        return $images;
    }



}
