jQuery(document).ready(function($){
    var det=0;
    $(".addWishListBtt").click(function () {
        var dataId = $(this).attr("data");
        $(this).fadeTo( "slow" , 0.5, function() {
            $(this).addClass("choosenWhish");
        });
        $(".bttImg").removeClass("bounce");
        $.ajax({
            url: ajaxWishList.ajax_url,
            type: 'post',
            data: {
                action: 'post_wc_wishList',
                id: parseInt(dataId),
                tunnel:"add"
            },
            success: function (response) {
                $(".wishButton").fadeTo("fast",1);
                $(".bttImg").addClass("animated bounce");
            }
        });
    });
    $(".removeWish").click(function () {
        var dataId = $(this).attr("data");
        $.ajax({
            url: ajaxWishList.ajax_url,
            type: 'post',
            data: {
                action: 'post_wc_wishList',
                id: parseInt(dataId),
                tunnel:"remove"
            },
            success: function (response) {
               $("#wishLine-"+dataId).remove();
            }
        });
    })
    $(".wishButton").click(function () {
        if(det==0) {
            $(".wishListContainer").addClass('wLTrickBackground');
            $(".wishListContainer").html('<div class="wLSpinnerContainer"><div class="spinnerWishList hideIt"></div></div>');
            $(".wishListContainerOut").fadeIn();
            $(".wishAllCover").fadeIn();
            det=1;
            $.ajax({
                url: ajaxWishList.ajax_url,
                type: 'post',
                data: {
                    action: 'post_wc_wishList',
                    id: "1",
                    tunnel:"getContent"
                },
                success: function (response) {
                    var products=JSON.parse(response);
                    if(products != null || products != undefined ) {
                        $(".wishListContainer").removeClass('wLTrickBackground');
                        var d = document.createElement('div');
                        d.setAttribute('data','');
                        $(d).addClass('wLContainerInlineCover')
                        $(".wishListContainer").html(d);
                        for(var i = 0; i < products.length; i++ ) {
                            var inDiv=document.createElement('div');
                            $(inDiv).addClass('wLContainerInline')
                            inDiv.setAttribute('id','wishLine-'+products[i]['id']);
                            $('.wLContainerInlineCover').prepend(inDiv);

                            var conImg=document.createElement('div');
                            $(conImg).addClass('wLInImg wLInConElm');
                            $(conImg).html('<a href="'+products[i]['url']+'">'+products[i]['image']+'</a>');

                            var conName=document.createElement('div');
                            $(conName).addClass('wLInName wLInConElm');
                            $(conName).html('<div><a href="'+products[i]['url']+'">'+products[i]['name']+'</a></div>');

                            var conPrice=document.createElement('div');
                            $(conPrice).addClass('wLInPrice wLInConElm');
                            $(conPrice).html('<div class="wLInPrCover">'+products[i]['price']+'</div>');

                            var conPro = document.createElement('div');
                            $(conPro).addClass('wLInPro wLInConElm');
                            d = document.createElement('div');
                            d.setAttribute('data', products[i]['id']);
                            $(d).addClass('wLRmvIcon')
                                .html('<span class="removeWish"></span>')
                                .click(function () {
                                    removeWishProduct($(this).attr('data'))
                                })
                            $(conPro).html(d);

                            $('#wishLine-'+products[i]['id']).prepend(conPro);
                            $('#wishLine-'+products[i]['id']).prepend(conPrice);
                            $('#wishLine-'+products[i]['id']).prepend(conName);
                            $('#wishLine-'+products[i]['id']).prepend(conImg);
                        }
                    }
                }
            });
        }
        else {
            $(".wishListContainerOut").fadeOut();
            det=0;
        }
    })
    function removeWishProduct(data) {
        $("#wishLine-"+data).fadeTo( "slow" , 0.5);
        $.ajax({
            url: ajaxWishList.ajax_url,
            type: 'post',
            data: {
                action: 'post_wc_wishList',
                id: parseInt(data),
                tunnel:"remove"
            },
            success: function (response) {
                $("#wishLine-"+data).hide(20,function () {
                    $("#wishLine-"+data).remove();
                    var count=JSON.parse(response);
                    $(".addWishListBtt[data='"+data+"']").removeClass("choosenWhish");
                    $(".addWishListBtt[data='"+data+"']").fadeTo( "slow" , 1);
                    if(count.length==0) {
                        disappear();
                    }
                })
            }
        });
    }
    function disappear() {
        $(".wishListContainerOut").fadeOut();
        $(".wishButton").fadeTo( "slow" , 0.5, function() {
            $(this).fadeOut();
            $(".bttImg").removeClass("animated bounce");
            $(".wishButton").removeClass("wishBeat");
            det=0;
        });
    }
    $(".wishAllCover").click(function () {
        $(".wishListContainerOut").fadeOut();
        $(this).fadeOut();
        det=0;
    });
    var addWishListFilter=function(dataIdFilter) {
        $(this).fadeTo( "slow" , 0.5, function() {
            $(this).addClass("choosenWhish");
        });
        $(".bttImg").removeClass("bounce");
        $.ajax({
            url: ajaxWishList.ajax_url,
            type: 'post',
            data: {
                action: 'post_wc_wishList',
                id: parseInt(dataIdFilter),
                tunnel:"add"
            },
            success: function (response) {
                $(".wishButton").fadeTo("fast",1);
                $(".bttImg").addClass("animated bounce");
            }
        });
    }
})
function filterFunction(e) {
    if(getParameterByNameWl('tunnel')) {
        var r = document.getElementsByClassName("wishButton");
        r[0].classList.remove("animated");
        r[0].classList.remove("bounce");
        var http = new XMLHttpRequest();
        var url = ajaxWishList.ajax_url;
        var params = "action=post_wc_wishList&id=" + parseInt(e.getAttribute("data")) + "&tunnel=add";
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function () {//Call a function when the state changes.
            if (http.readyState == 4 && http.status == 200) {
                e.classList.add("choosenWhish");
                var r = document.getElementsByClassName("wishButton");
                r[0].classList.add("animated");
                r[0].setAttribute("style", "display:block");
                r[0].classList.add("bounce");
            }
        }
        http.send(params);
    }
}
function getParameterByNameWl(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}