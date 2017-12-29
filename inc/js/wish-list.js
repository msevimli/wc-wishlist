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
            $(".wishListContainer").html('<div class="spinnerWishList hideIt"></div>');
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
                        var table = document.createElement("table");
                        table.className = "gridtable";
                        var thead = document.createElement("thead");
                        var tbody = document.createElement("tbody");
                        var headRow = document.createElement("tr");
                        ["", "Produkt :", "Pris :", ""].forEach(function (el) {
                            var th = document.createElement("th");
                            th.appendChild(document.createTextNode(el));
                            headRow.appendChild(th);
                        });
                        thead.appendChild(headRow);
                        table.appendChild(thead);
                        for (var i = 0; i < products.length; i++) {
                            var tr = document.createElement("tr");
                            tr.setAttribute("id", "wishLine-" + products[i]['id']);
                            var td = document.createElement("td");
                            td.innerHTML = products[i]['image'];
                            tr.appendChild(td);
                            var td = document.createElement("td");
                            var a = document.createElement('a');
                            var linkText = document.createTextNode(products[i]['name']);
                            a.appendChild(linkText);
                            a.title = products[i]['name'];
                            a.href = products[i]['url'];
                            td.prepend(a);
                            tr.appendChild(td);
                            var td = document.createElement("td");
                            td.innerHTML = products[i]['price'];
                            tr.appendChild(td);
                            var td = document.createElement("td");
                            d = document.createElement('div');
                            d.setAttribute('data', products[i]['id']);
                            $(d).addClass('rmv')
                                .html('<span class="removeWish"></span>')
                                .click(function () {
                                    removeWishProduct($(this).attr('data'))
                                })
                            td.prepend(d);
                            tr.appendChild(td);
                            tbody.appendChild(tr);
                            tbody.appendChild(tr);
                            table.appendChild(tbody);
                        }
                        $(".wishListContainer").html(table);
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
    })
})