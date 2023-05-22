<?php

    $hostname = "localhost";
    $database = "Shopee";
    $db_login = "root";
    $db_pass = "";
    
    $dlink = mysqli_connect($hostname, $db_login, $db_pass, $database) or die("Could not connect");

    $products_cart = isset($_COOKIE['products_cart']) ? unserialize($_COOKIE['products_cart']) : [];
    $carted_prod = $_REQUEST['prod_id'] ?? null;

    $product_query = "SELECT * FROM products";
    $product_search = mysqli_query($dlink, $product_query);
    $product_list = mysqli_fetch_all($product_search);
    // loops through all the products in database
    foreach ($product_list as $key => $product) {
        $product_id = $product[0];
        $product_category = $product[1];
        $product_name = $product[2];
        $product_description = $product[3];
        $product_image = $product[5];
        $product_quantity = $product[6];
        // product_price has two parts, lastprice and ourprice(the difference between the
        // two is unknown to me, update comments if needed.)
        // in case ourprice is missing, use price of lastprice instead
        $product_price = $product[8] > 0 ? $product[8] : $product[7];
        if ($carted_prod == $product_id) {
            /**
             * Checks if product id is in products_cart and gets the key for the array containing said product
             * loops through the products in the cart stored in the cookies
             */
            $in_cart = false;
            
            foreach ($products_cart as $key2 => $product2) {
                if ($product_id == $product2[0]) {
                    $in_cart = true;
                    $cart_id = $key2;
                }
            }

            /**
             * adds newly carted product to products_cart, with quantity 1
             * 
             * if product is already in products_cart, increments quantity by 1
             */
            
            if ($in_cart === false) {
                $products_cart[] = [$product_id, $product_category, $product_name, $product_description, $product_image, $product_quantity, $product_price, 0];
            } else {
            // [7] here is the $carted_quantity index of the array, $products_cart
                $carted_quantity = $products_cart[$cart_id][7];
                $products_cart[$cart_id] = [$product_id, $product_category, $product_name, $product_description, $product_image, $product_quantity, $product_price, $carted_quantity + 1];
            }
            setcookie("products_cart", serialize($products_cart), time() + 86400, '/');
        }
    }

    /**
     * deleting items from product cart
     */
    $uncarted_prod = $_REQUEST["del_prod"] ?? 0;
    if ($uncarted_prod > 0) {
        foreach ($products_cart as $key => $product) {
            if ($uncarted_prod == $product[0]) {
                unset($products_cart[$key]);
                setcookie("products_cart", serialize($products_cart), time() + 86400, '/');
            }
        }
    }

    /**
     * ! Does not work
     * update product quantity from cart page
     */
    $updated_prod = $_REQUEST['update_prod'] ?? 0;
    $updated_quantity = $_REQUEST['product_quantity'] ?? 0;
    if ($updated_prod > 0) {
        foreach ($products_cart as $key => &$product) {
            $product_id = $product[0];
            if ($product_id == $updated_prod) {
                $product[7] = $updated_quantity;
                setcookie("products_cart", serialize($products_cart), time() + 86400, '/');
            }
        }
    }

    // TODO: finish add to purchase database functionality
    $purchase_products = $_REQUEST["cart_product"] ?? null;
    $user_id = $_COOKIE['user_id'] ?? null;
    if (isset($purchase_products) && isset($user_id)) {
        foreach ($purchase_products as $key => $purchase_product) {
            $product_id = $purchase_product;

            foreach ($products_cart as $key => $cart_product) {
                $cart_id = $cart_product[0];
                $cart_quantity = $cart_product[7];
                $total_price = $cart_quantity * $cart_product[6];
                if ($product_id == $cart_id) {
                    $purchase_query = "INSERT INTO purchase VALUES($user_id, $product_id, $cart_quantity, $total_price, NOW(), 'pending')";
                    $purchase_update = mysqli_query($dlink, $purchase_query);
                    $update_products_query = "UPDATE products SET quantity=quantity-$cart_quantity WHERE prodid=$product_id";
                    $update_products = mysqli_query($dlink, $update_products_query);
                    unset($products_cart[$key]);
                    setcookie("products_cart", serialize($products_cart), time() + 86400, '/');
                }
            }
        }
    }

?>