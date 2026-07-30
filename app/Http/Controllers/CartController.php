<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $food = Food::findOrFail($request->food_id);
        $cart = Auth::user()->cart;

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'restaurant_id' => $food->restaurant_id,
            ]);
        } elseif ($cart->restaurant_id !== $food->restaurant_id) {
            return redirect()->back()->with('error', 'You can only add items from one restaurant at a time. Please clear your cart first.');
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('food_id', $food->id)
                           ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $request->quantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'food_id' => $food->id,
                'quantity' => $request->quantity,
                'unit_price' => $food->getDiscountedPrice(),
            ]);
        }

        $cart->calculateTotals();

        return redirect()->back()->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $this->authorize('update', $cartItem);

        $cartItem->update(['quantity' => $request->quantity]);
        $cartItem->cart->calculateTotals();

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $this->authorize('delete', $cartItem);

        $cart = $cartItem->cart;
        $cartItem->delete();
        $cart->calculateTotals();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $cart = Auth::user()->cart;
        if ($cart) {
            $cart->items()->delete();
            $cart->update([
                'subtotal' => 0,
                'delivery_fee' => 0,
                'tax' => 0,
                'total' => 0,
            ]);
        }

        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
