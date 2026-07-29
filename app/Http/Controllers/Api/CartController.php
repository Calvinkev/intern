<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Food;
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

        $cart->load('items.food');

        return response()->json($cart);
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
            return response()->json([
                'message' => 'You can only add items from one restaurant at a time. Please clear your cart first.',
            ], 400);
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

        $cart->load('items.food');

        return response()->json($cart);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $cart = $cartItem->cart->load('items.food');

        return response()->json($cart);
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();
        $cart->load('items.food');

        return response()->json($cart);
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
                'restaurant_id' => null,
            ]);
        }

        return response()->json(['message' => 'Cart cleared successfully']);
    }
}
