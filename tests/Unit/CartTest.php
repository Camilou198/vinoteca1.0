<?php

use App\Services\Cart;
use App\Models\Category;

test('a product can be added to the cart', function () {

    $cart = app(Cart::class);

    $category = Category::create([
        'name' => 'Category 1',
        'description' => 'Description',
        'image' => 'category-1.jpg',
    ]);

    $wine = Wine::create([
        'name' => 'Wine 1',
        'description' => 'Description',
        'image' => 'category-1.jpg',
        ]);
    });
