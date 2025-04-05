<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;

class ProductController extends Controller
{
    function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => ['required'],
                'description' => [],
                'price' => ['required', 'numeric', 'min:0'],
                'images' => ['required', 'array'],
                'images.*' => [File::image()->max(5 * 1024)]
            ]);

            $product = Product::create($data);
            if ($product) {
                if ($request->has('images')) {
                    foreach ($request->file('images') as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = uniqid().'.'.$extension;

                        $file->move('storage/product', $filename);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'name' => $filename
                        ]);
                    }
                }

                return redirect()->route('catalog-detail', ['id' => $product->id]);
            }

            return back()->withInput();
        }

        return view('product.form');
    }

    function edit(string $id, Request $request)
    {
        $product = Product::where('id', $id)->first();

        Gate::authorize('edit_product', $product);

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => ['required'],
                'description' => [],
                'price' => ['required', 'numeric', 'min:0'],
            ]);

            if ($product->update($data)) {
                if ($request->has('images')) {
                    foreach ($request->file('images') as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = uniqid().'.'.$extension;

                        $file->move('storage/product', $filename);

                        ProductImage::create([
                            'product_id' => $product->id,
                            'name' => $filename
                        ]);
                    }
                }

                return redirect()->route('catalog-detail', ['id' => $product->id]);
            }

            return back()->withInput();
        }

        return view('product.form', [
            'product' => $product
        ]);
    }
}
