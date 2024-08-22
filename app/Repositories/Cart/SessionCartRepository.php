<?php

namespace App\Repositories\Cart;

use App\Models\Wine;
use App\Traits\WithCurrencyFormatter;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class SessionCartRepository implements CartRepositoryInterface
{
    use WithCurrencyFormatter;

    const SESSION = 'cart';

    public function __construct()
    {
        if(! Session::has(self::SESSION))
        {
            Session::put(self::SESSION,collect());
        }
    }

    public function add(Wine $wine, int $quantity): void
    {
        $cart = $this->getCart();

        if($cart->has($wine->id))
        {
            $cart->get($wine->id)['quantity'] += $quantity;
        }else{
            $cart->put($wine->id,[
                'product' => $wine,
                'quantity' => $quantity,
            ]);
        }

        $this->updateCart($cart);
    }

    public function increment (Wine $wine): void
    {
       $cart = $this->getCart();

       if($cart->has($wine->id))
       {
            if(data_get($cart->get($wine->id), 'quantity') >= $wine->stock)
            {
                throw new Exception('No hay suficiente stock para incrementar la cantidad de' . $wine->name);
            }

            $wineInCart = $cart->get($wine->id);
            $wineInCart['quantity']++;
            $cart->put($wine->id, $wineInCart);
            $this->updateCart($cart);
       }
    }

    public function decrement(int $wineId): void
    {
        $cart = $this->getCart();

        if($cart->has($wineId))
        {
            $wineInCart = $cart->get($wineId);
            $wineInCart['quantity']--;
            $cart->put($wineId, $wineInCart);

            if(data_get($cart->get($wineId), 'quantity') <= 0)
            {
                $cart->forget($wineId);
            }

            $this->updateCart($cart);
        }
    }

    public function remove(int $wineId): void
    {
        $cart = $this->getCart();

        $cart->forget($wineId);

        $this->updateCart($cart);
    }

    public function getTotalQuantityForWine(Wine $wine): int
    {
        $cart = $this->getCart();

        if($cart->has($wine->id))
        {
            return data_get($cart->get($wine->id), 'quantity');
        }

        return 0;
    }

    public function getTotalCostForWine(Wine $wine, bool $formatted): float|string
    {
        $cart = $this->getCart();

        $total = 0;

        if($cart->has($wine->id))
        {
            $total = data_get($cart->get($wine->id), 'quantity') * $wine->price;
        }

        return $formatted ? $this->formatCurrency($total) : $total;
    }

    public function getTotalQuantity(): int
    {
        $cart = $this->getCart();

        return $cart->sum('quantity');
    }

    public function getTotalCost(bool $formatted): float|string
    {
        $cart = $this->getCart();

        $total = $cart->sum(function ($item)
        {
            return data_get($item, 'quantity') * data_get($item, 'product.price');
        });

        return $formatted ? $this->formatCurrency($total) : $total;
    }

    public function hasProduct(Wine $wine): bool
    {
        $cart = $this->getCart();

        return $cart->has($wine->id);
    }

    public function getCart(): Collection
    {
        return Session::get(self::SESSION, collect());
    }

    public function isEmpty(): bool
    {
        return $this->getTotalQuantity() === 0;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION);
    }

    private function updateCart(Collection $cart): void
    {
        Session::put(self::SESSION, $cart);
    }

}
