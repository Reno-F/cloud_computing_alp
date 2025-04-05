<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ShoppingCarts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckoutMail;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        $userId = Auth::id();

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity;
        } else {
            $cart[$id] = [
                'product_id' => $id,
                'quantity' => $validated['quantity'],
                'name' => $product->name,
                'price' => $product->price,
            ];
        }
        session()->put('cart', $cart);

        $shoppingCart = ShoppingCarts::updateOrCreate(
            ['user_id' => $userId, 'product_id' => $id],
            ['quantity' => \DB::raw('quantity + ' . $validated['quantity'])]
        );

        return redirect()->route('catalog')->with('success', 'Product added to cart!');
    }

    public function decrement(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $userId = Auth::id();

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] -= 1;
                ShoppingCarts::where(['user_id' => $userId, 'product_id' => $id])->decrement('quantity', 1);
            } else {
                unset($cart[$id]);
                ShoppingCarts::where(['user_id' => $userId, 'product_id' => $id])->delete();
            }
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        return redirect()->back()->with('error', 'Product not found in cart!');
    }

    public function increment(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $userId = Auth::id();

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
            ShoppingCarts::where(['user_id' => $userId, 'product_id' => $id])->increment('quantity', 1);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        return redirect()->back()->with('error', 'Product not found in cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        $userId = Auth::id();

        if (isset($cart[$id])) {
            unset($cart[$id]);
            ShoppingCarts::where(['user_id' => $userId, 'product_id' => $id])->delete();
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product removed from cart!');
        }

        return redirect()->back()->with('error', 'Product not found in cart!');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (!$cart) {
            return redirect()->route('catalog')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        Mail::to('renovansetio02@gmail.com')->send(new CheckoutMail($cart, $total));

        return view('checkout.checkout', compact('cart'));
    }
}
