<?php

namespace App\Http\Controllers;


use App\Services\Cart;
use Illuminate\View\View;
use App\Traits\Traits\CartActions;
use App\Repositories\Shop\ShopRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class ShopController extends Controller
{
    use  CartActions;

    public function __construct(private readonly ShopRepositoryInterface $repository, private readonly Cart $cart)
    {
        ray($this->cart->getCart());
    }

    public function index(): View
    {
        ray()->showQueries();
        $wines = $this->repository->paginate();

        return view('shop.index', compact('wines'));
    }

    public function addToCart(): RedirectResponse
    {
        $this->addProductToCart();

        return redirect()->route(route:'shop.index');
    }

    public function increment(): RedirectResponse
    {
        $this->incrementProductQuantity();

        return redirect()->route('shop.index');
    }

    public function decrement(): RedirectResponse
    {
        $this->decrementProductQuantity();

        return redirect()->route('shop.index');
    }

    public function remove(): RedirectResponse
    {
        $this->removeProduct();

        return redirect()->route('shop.index');
    }
}
