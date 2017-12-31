<?php
session_start();
function requireFilesWishList() {
    wp_enqueue_script( 'ajax-wish-list',plugins_url('js/wish-list.js', __FILE__), array('jquery'),1.1,true);
    wp_localize_script( 'ajax-wish-list', 'ajaxWishList', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
    wp_register_script('wish', plugins_url('js/init-wish.php', __FILE__), array('jquery'),1.0,true);
    wp_enqueue_script('wish');
}
add_action( 'wp_enqueue_scripts', 'requireFilesWishList' );
add_action( 'wp_ajax_nopriv_post_wc_wishList','post_wc_wishList' );
add_action( 'wp_ajax_post_wc_wishList', 'post_wc_wishList' );
if (function_exists("post_wc_wishList")) {
    exit;
} else {
    function post_wc_wishList()
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            if ( isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST["tunnel"]) ) {
                echo wishListFabric($_POST["id"],$_POST["tunnel"]);
                exit;
            }
        } else {
            exit;
        }
    }
}
function wishListFabric( $id, $tunnel ) {
    switch ($tunnel) {
        case "add" :
            if (isset($_SESSION["wish_list"])) {
                $ids = json_decode($_SESSION["wish_list"],true);
                if ( ! in_array($id, $ids) ) {
                    $ids[] = (int)$id;
                    $_SESSION["wish_list"] = json_encode($ids);
                    return $_SESSION["wish_list"];
                    exit;
                }
            } else {
                session_start();
                $_SESSION["wish_list"] = json_encode(array($id));
                return $_SESSION["wish_list"];
                exit;
            }
            break;
        case "remove" :
            $ids = json_decode($_SESSION["wish_list"],true);
            if (in_array($id, $ids)) {
                $index = array_search($id, $ids);
                unset($ids[(int)$index]);
                Sort($ids);
                $_SESSION["wish_list"] = json_encode($ids);
                return $_SESSION["wish_list"];
                exit;
            }
            break;
        case "getContent" :
            echo getContent();
            exit;
            break;
    }
    exit;
}
function getContent() {
    if (isset($_SESSION["wish_list"])) {
        $wish_ids = json_decode($_SESSION["wish_list"], true);
        $i=0;
        foreach ($wish_ids as $id) {
            $_product = wc_get_product($id);
            $product[$i]['id']=$id;
            $product[$i]['url']=$_product->get_permalink();
            $product[$i]['name']=$_product->get_name();
            $product[$i]['image']=$_product->get_image(array(100,100));
            $product[$i]['price']=$_product->get_price_html();
            $i++;
        }
        return json_encode($product);
        exit;
    }
}