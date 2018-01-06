<?php
/**
 * Plugin Name: Woocommerce Wish List
 * Plugin URI: http://plife.se
 * Description: This plugin will add support for woocommerce to create wish list
 * Version: 1.1
 * Author: M.Sevimli
 * Author URI: http://plife.se
 * License: GPL2
 */
include_once("inc/wish-list-ajax.php");
include_once("inc/class-wish-list.php");

function reqFiles_wishList() {
    wp_register_style('wishList', plugins_url('inc/css/wish-list.css', __FILE__));
    wp_enqueue_style('wishList');
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
    $productIds=isset($_SESSION["wish_list"]) ? json_decode($_SESSION["wish_list"],true) : "[]";
    $className=isset($_SESSION["wish_list"]) && in_array($product->get_id(),$productIds) ? "choosenWhish" : "";
    $wLPosition= is_product_category('badrum') || is_product_tag('badrum') || is_product_category('badevaerelse') || is_product_tag('badevaerelse')  ? 'wList-bottom-right' :  get_option('wListPosition');
    echo '<div title="'.get_option("wListTitle").'" class="addWishListBtt '.$className.' '.$wLPosition.'" data="'.$product->get_id().'"><span></span></div>';
}
add_action("woocommerce_before_shop_loop_item","boot_wishList");
function boot_wLSingle() {
    global $product;
    $productIdsS=isset($_SESSION["wish_list"]) ? json_decode($_SESSION["wish_list"],true) : "[]";
    $classNameS=isset($_SESSION["wish_list"]) && in_array($product->get_id(),$productIdsS) ? "choosenWhish" : "";
    echo '<div title="'.get_option("wListTitle").'" class="addWishListBtt wishButtonSingle '.$classNameS.'" data="'.$product->get_id().'"><span></span></div>';
}
add_action("woocommerce_after_add_to_cart_button","boot_wLSingle");

function init_List() {
    ?>
        <style>
            .spinnerWishList {
                background-image: url(<?php echo plugins_url('inc/css/giphy.gif', __FILE__)  ?>);
                background-size: cover;
                width: 23%;
                height: 122px;
                position: relative;
                margin: 0 auto;
            }
        </style>
        <?php
            if(is_product()) {
                ?>
                    <style>
                        .wishButtonSingle {
                            margin-top: 21px;
                            position: relative;
                            width: 32px;
                            margin-bottom: -20px;
                        }
                    </style>
                <?php
            }
        ?>
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