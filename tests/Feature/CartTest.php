<?php

use App\Models\User;
use App\Models\Wine;
use App\Services\Cart;
use App\Models\Category;

test('a productcan be added to the cart', function()
{
    $cart = app(Cart::class);

    $category = Category::create([
        'name' => 'Category 1',
        'description' => 'Description',
        'image' => 'category-1.jpg',
    ]);

    $wine1 = Wine::create([
        'name' => 'Wine 1',
        'category_id' => $category->id,
        'price' => 15,
        'year' => 2010,
        'description' => 'Description',
        'stock' => 10,
        'image' => 'wine-1.jpg',
    ]);

    $wine2 = Wine::create([
        'name' => 'Wine 2',
        'category_id' => $category->id,
        'price' => 10,
        'year' => 2012,
        'description' => 'Description',
        'stock' => 10,
        'image' => 'wine-2.jpg',
    ]);

    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('shop.addToCart'),[
        'wine_id' => $wine1->id,
        'quantity' => 2,
    ]);

    expect($cart->isEmpty())->toBe(false)
        ->and($cart->getTotalQuantity())->toBe(2)
        ->and($cart->getTotalCost())->toBe(30.00)
        ->and($cart->getTotalQuantityForWine($wine1))->toBe(2)
        ->and($cart->getTotalCostForWine($wine1))->toBe(30.00);

    $this->post(route('shop.addToCart'),[
        'wine_id' => $wine2->id,
        'quantity' => 3,
    ]);

    expect($cart->getTotalQuantity())->toBe(5)
        ->and($cart->getTotalCost())->toBe(60.00)
        ->and($cart->getTotalQuantityForWine($wine2))->toBe(3)
        ->and($cart->getTotalCostForWine($wine2))->toBe(30.00);

})->group('feature-cart');
