<?php


defined('ABSPATH') || exit;

?>

<div class="custom-cart-container">

    <h1 class="cart-title"><?php _e('Your Cart', 'woocommerce'); ?></h1>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

        <table class="shop_table cart" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
                    <th class="product-price"><?php _e('Price', 'woocommerce'); ?></th>
                    <th class="product-quantity"><?php _e('Quantity', 'woocommerce'); ?></th>
                    <th class="product-subtotal"><?php _e('Subtotal', 'woocommerce'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        ?>
                        <tr>
                            <td class="product-name">
                                <?php
                                $product_permalink = $_product->is_visible() ? $_product->get_permalink($cart_item) : '';
                                echo $product_permalink ? sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()) : $_product->get_name();

                                echo wc_get_formatted_cart_item_data($cart_item);
                                ?>
                            </td>

                            <td class="product-price">
                                <?php echo WC()->cart->get_product_price($_product); ?>
                            </td>

                            <td class="product-quantity">
                                <?php
                                if ($_product->is_sold_individually()) {
                                    echo '1';
                                } else {
                                    echo woocommerce_quantity_input(array(
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value' => $_product->get_max_purchase_quantity(),
                                        'min_value' => '0',
                                    ), $_product, false);
                                }
                                ?>
                            </td>

                            <td class="product-subtotal">
                                <?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>


            </tbody>
        </table>
    </form>



    <div class="cart-collaterals">
        <?php woocommerce_cross_sell_display(); ?>
    </div>

</div>

<?php do_action('woocommerce_after_cart'); ?>