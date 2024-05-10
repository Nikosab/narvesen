<?php

function addToCart($productId, &$cart, $products) {
    if (isset($products[$productId])) {
        $cart[] = $products[$productId];
        echo "Product {$products[$productId]->product} added to cart." . PHP_EOL;
    } else {
        echo "Product with ID $productId not found." . PHP_EOL;
    }
}

function viewCart($cart) {
    if (empty($cart)) {
        echo "Your cart is empty." . PHP_EOL;
    } else {
        echo "Your cart contains:" . PHP_EOL;
        foreach ($cart as $item) {
            echo "- {$item->product}" . PHP_EOL;
        }
    }
}

function buyCart(&$cart) {
    if (empty($cart)) {
        echo "Your cart is empty. Nothing to buy." . PHP_EOL;
    } else {
        $total = 0.0;

        echo "You have purchased the following items:" . PHP_EOL;
        foreach ($cart as $item) {
            $priceParts = explode(" ", $item->price);
            $price = (float)str_replace(',', '.', $priceParts[0]);
            if (count($priceParts) > 1) {
                $price += (float)str_replace(',', '.', $priceParts[1]) / 100;
            }
            $total += $price;
            echo "- {$item->product}, Price: {$item->price}" . PHP_EOL;
        }

        $formattedTotal = number_format($total, 2, ',', ' ');
        echo "Total: $formattedTotal eiro" . PHP_EOL;

        $cart = [];
    }
}

$filename = "narvesen/products.json";
$json_data = file_get_contents($filename);
$products = json_decode($json_data);

if ($products) {
    $cart = [];
    $exit = false;

    while ($exit !== true) {
        echo "1. View products" . PHP_EOL;
        echo "2. Add product to cart" . PHP_EOL;
        echo "3. View cart" . PHP_EOL;
        echo "4. Buy cart" . PHP_EOL;
        echo "5. Exit" . PHP_EOL;

        $choice = readline("Enter your choice: ");

        switch ($choice) {
            case '1':
                foreach ($products as $key => $product) {
                    echo "$key {$product->product}, Price: {$product->price}" . PHP_EOL;
                }
                break;
            case '2':
                $productId = readline("Enter product ID to add to cart: ");
                addToCart($productId, $cart, $products);
                break;
            case '3':
                viewCart($cart);
                break;
            case '4':
                buyCart($cart);
                break;
            case '5':
                $exit = true;
                echo "Goodbye!" . PHP_EOL;
                break;
            default:
                echo "Invalid choice. Please try again." . PHP_EOL;
                break;
        }
    }
} else {
    echo "Failed to load products." . PHP_EOL;
}
