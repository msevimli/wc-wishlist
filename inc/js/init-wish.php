<?php header("Content-type: application/javascript");  session_start(); ?>
jQuery(document).ready(function($){
    var ss='<?php echo isset($_SESSION["wish_list"]) ? $_SESSION["wish_list"] : "none"; ?>';
    $(".addWishListBtt").removeClass("choosenWhish");
    if( ss != "none" ) {
        var _products= JSON.parse(ss);
        if(_products.length > 0 ) {
            $(".wishButton").fadeIn();
        } else {
            $(".wishButton").attr("style","");
        }
        for(var i=0; i < _products.length; i++) {
            $(".addWishListBtt[data='"+_products[i]+"']").addClass("choosenWhish");
        }
    }
});