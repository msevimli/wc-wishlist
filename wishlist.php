<?php
/**
 * Plugin Name: Woocommerce Wish List
 * Plugin URI: http://plife.se
 * Description: This plugin will add support for woocommerce to create wish list
 * Version: 1.0
 * Author: M.Sevimli
 * Author URI: http://plife.se
 * License: GPL2
 */
include_once("inc/wish-list-ajax.php");
include_once("inc/class-wish-list.php");

function reqFiles_wishList() {
    wp_register_style('wishList', plugins_url('inc/css/wish-list.css', __FILE__));
    wp_enqueue_style('wishList');
    wp_register_style('wishListAnimate', plugins_url('inc/css/animate.min.css', __FILE__));
    wp_enqueue_style('wishListAnimate');
}
add_action("wp_enqueue_scripts","reqFiles_wishList");
add_action("wp_head","wListBeforeLoop");
function wListBeforeLoop() {
    ?>
    <style>
        .addWishListBtt {
            background-color: <?php echo get_option('colorPickerBackground'); ?>;
            color: <?php echo get_option('colorPickerText'); ?>;
        }
    </style>
    <?php
}

function boot_wishList() {
    global $product;
    $productIds=json_decode($_SESSION["wish_list"],true);
    $className=isset($_SESSION["wish_list"]) && in_array($product->get_id(),$productIds) ? "choosenWhish" : "";
    $wLPosition= get_option('wListPosition');
    echo '<div title="'.get_option("wListTitle").'" class="addWishListBtt '.$className.' '.$wLPosition.'" data="'.$product->get_id().'"><span></span></div>';
}
add_action("woocommerce_before_shop_loop_item","boot_wishList");

function init_List() {
    ?>
        <style>
            .spinnerWishList {
                background-image: url(<?php echo plugins_url('inc/css/giphy.gif', __FILE__)  ?>);
                background-size: cover;
                width: 16%;
                height: 108px;
                position: relative;
                margin: 0 auto;
            }
        </style>
        <div class="wishButton">
            <img class="bttImg" src="<?php echo plugins_url('inc/css/wishlist-Button.png', __FILE__)  ?>">
        </div>
        <div class="wishListContainerOut">
            <div class="wishListContainer"></div>
            <div class="wishAllCover"></div>
        </div>
    <?php
}
add_action("wp_footer","init_List");