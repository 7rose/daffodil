window.onload = function(){
    initNum();
}

// add item to Cart or Buy
function addCart(id, type, num) 
{
    var goods_id = id;
    var type = type;

    var url = "/cart/add?" + type + "=" + goods_id;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(json){
            if(json.length != 0){
                if(Number(json.num) > 0) $("#user-bage").html(json.num);
                if(Number(json.num_cart) > 0) $("#menu-cart").html(json.num_cart);
                if(Number(json.num_buy) > 0) $("#menu-buy").html(json.num_buy);
            }else{
                console.log = 'ServerError';
            }
        }
    });
}

// get init num and set
function initNum() 
{
    var url = "/cart/num";
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(json){
            if(json.length != 0){
                if(Number(json.num) > 0) $("#user-bage").html(json.num);
                if(Number(json.num_cart) > 0) $("#menu-cart").html(json.num_cart);
                if(Number(json.num_buy) > 0) $("#menu-buy").html(json.num_buy);
            }else{
                console.log = 'ServerError';
            }
        }
    });
}

// generate input list
function fix(goods_id,num) 
{
    var goods_id = goods_id;
    var num = num;
    var url = "/goods/ajax/"+goods_id;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function(json){
            if(json.length != 0){
                $('#modal-title').html("货号: "+json.sn+",<label class=\"fix-label\">打印标签<input type=\"checkbox\" name=\"need_label\" id=\"need_label\" checked=\"checked\" value=\"1\"></label>"+"<div class=\"fix-img\"><img src=\"/"+json.img+"\" class=\"img-rounded\"></div>");
                $('#goods_sn').val(json.sn);
                $('#form-body').html(createForm(json,num,goods_id));
                $('#modal-footer').html("<a class=\"btn btn-default btn-sm\" data-dismiss=\"modal\">关闭</a><a class=\"btn btn-success btn-sm\" href=\"javascript:submitForm();\">提交</a>");
                $('#fix-model').modal('show');
            }else{
                console.log = 'ServerError';
            }
        }
    }); 
}

// create form
function createForm(json,num,goods_id)
{
    var form = "<input type=\"hidden\" name=\"num\" id=\"num\" value=\""+num+"\">";
    form += "<input type=\"hidden\" name=\"goods_id\" id=\"goods_id\" value=\""+goods_id+"\">";
    for (var i = 1; i <= num; i++) {
        form += "<div class=\"well\">";
        form += "<h6>"+i+" / "+num+"</h6>";
        form += "<div class=\"form-group\" ><label for=\"weight"+i+"\" class=\"control-label\">总重</label><input class=\"form-control\" step = \"0.001\" name=\"weight"+i+"\" type=\"number\" id=\"weight"+i+"\"></div>";
        if(json.gold != '' && json.gold != null) {
            form += "<div class=\"form-group\" ><label for=\"gold_weight"+i+"\" class=\"control-label\">金重</label><input class=\"form-control\" step = \"0.001\" name=\"gold_weight"+i+"\" type=\"number\" id=\"gold_weight"+i+"\"></div>";
        }
        if(json.stone != '' && json.stone != null) {
            form += "<div class=\"form-group\" ><label for=\"stone_weight"+i+"\" class=\"control-label\">宝石重</label><input class=\"form-control\" step = \"0.001\" name=\"stone_weight"+i+"\" type=\"number\" id=\"stone_weight"+i+"\"></div>";
        }
        form += "<div class=\"form-group\" ><label for=\"other"+i+"\" class=\"control-label\">其他</label><input class=\"form-control\" name=\"other"+i+"\" type=\"text\" id=\"other"+i+"\"></div>";
        if(useWechat()){
            form += "<div class=\"form-group\" ><label for=\"ca"+i+"\" class=\"control-label\">CA证书&nbsp<a href=\"javascript:scan("+i+")\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-qrcode\" aria-hidden=\"true\"></i></a></label><input class=\"form-control\" name=\"ca"+i+"\" type=\"text\" id=\"ca"+i+"\"></div>";
        }else{
            form += "<div class=\"form-group\" ><label for=\"ca"+i+"\" class=\"control-label\">CA证书&nbsp(可用微信扫描)</label><input class=\"form-control\" name=\"ca"+i+"\" type=\"text\" id=\"ca"+i+"\"></div>";
        }
        form += "</div>";
    }
    return form;
}

// submit form
function submitForm()
{
    var num = Number($('#num').val());
    var goods_sn = $('#goods_sn').val();
    var order_buy_pn = $('#order_buy_pn').val();
    var goods_id = $('#goods_id').val();

    var post = [];
    for (var i = 1; i <= num; i++) {

        var weight = $('#weight'+i).val();
        var gold_weight = $('#gold_weight'+i).val();
        var stone_weight = $('#stone_weight'+i).val();
        var other = $('#other'+i).val();
        var ca = $('#ca'+i).val();

        var one = new Object();
        one.order_buy_pn = order_buy_pn;
        one.goods_sn = goods_sn;
        one.weight = weight;
        one.gold_weight = gold_weight;
        one.stone_weight = stone_weight;
        one.other = other;
        one.ca = ca;
        if($('#need_label').is(':checked')) one.need_label = 1;
        post.push(one);
        //console.log(one);
    }
    post = JSON.stringify(post);
    //console.log(post);
    var url = "/offer/create";
    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            //console.log('posting..');
            $('#modal-title').html('结果');
            $('#form-body').html('处理中...');
            $('#modal-footer').html("<a class=\"btn btn-default btn-sm\" data-dismiss=\"modal\">关闭</a>");
        },
        success: function(json){
            //console.log(json.resault+json.msg);
            $('#form-body').html(json.msg);
            $('#fix-model').on('hidden.bs.modal', function () {
            //var url = "/order/buy/"+order_buy_pn;
            //location.href = url;
            window.location.reload();

            });
            //$('#info'+goods_id).html("<span class=\"label label-success\">已入库</span>"); 
        }
    });
}

// deliver goods
function deliver(goods_sn,order_pn,num) 
{
    var post = new Object();
    post.goods_sn = goods_sn;
    post = JSON.stringify(post);

    //console.log(post);

    var url = "/offer/deliver";

    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            //console.log('posting..');
        },
        success: function(json){
            //console.log(json);
            var table = "";
            for (var i = 0; i < json.length; i++) {
                if(json[i].shop_name){
                    table += "<tr><td id=\"sn"+json[i].sn+"\"><a class=\"btn btn-xs btn-warning\" href=\"javascript:choose("+order_pn+','+json[i].sn+','+num+")\">"+json[i].sn+"</a></td>";
                }else{
                    table += "<tr><td id=\"sn"+json[i].sn+"\"><a class=\"btn btn-xs btn-success\" href=\"javascript:choose("+order_pn+','+json[i].sn+','+num+")\">"+json[i].sn+"</a></td>";
                }
                if(json[i].shop_name){
                    table += "<td>"+json[i].shop_name+"</td>";
                }else{
                    table += "<td>库存</td>";
                }
                table += "<td id=\"cancel" +json[i].sn+ "\"></td>";
            }
            $('#offers-title').html(goods_sn);
            $('#offers-sn').html('');
            $('#offers-sub').html('');
            $('#offer-goods-sn').val(goods_sn);
            $('#offer-num').val(num);
            $('#sns').val('');
            $('#offers-num').html("0/"+num+": ");
            $('#offers-for-deliver').html(table);
            $('#deliver-model').modal('show');
        }
    });
}

// choose choice
function choose(order_pn,offer_sn,num)
{
    var sns = $('#sns').val();
    var new_sns = offer_sn;
    var sn_array = sns.split(",");
    if(sn_array.length < num || sns ==''){
        if(sns != ''){
            new_sns = sns+','+offer_sn;
        }
        console.log(sn_array.length);
        
        //console.log(new_sns);
        $('#sns').val(new_sns);
        createList(order_pn,offer_sn,num);
        // set
        var cancel = $('#cancel'+offer_sn);
        var cancel_btn = "<a class=\"btn btn-xs btn-danger\" href=\"javascript:cancel("+order_pn+','+offer_sn+','+num+")\">取消</a>";
        var sn = $('#sn'+offer_sn);

        cancel.html(cancel_btn);
        sn.html(offer_sn);
    }else{
        // console.log('out');
        
    }    
}

// cancel
function cancel(order_pn,offer_sn,num)
{
    var sns = $('#sns').val();
    var sn_array = sns.split(",");

    var new_sns = '';
    if(sn_array.length == 1 && offer_sn == sns){
        // only one: clear
    }else{
        sn_array.splice($.inArray(offer_sn,sn_array),1);
        new_sns = sn_array.toString();
    }
    $('#sns').val(new_sns);

    $('#cancel'+offer_sn).html('');
    var sn = $('#sn'+offer_sn);
    var sn_btn = "<a class=\"btn btn-xs btn-success\" href=\"javascript:choose("+order_pn+','+offer_sn+','+num+")\">"+offer_sn+"</a>";
    sn.html(sn_btn);

    createList(order_pn,offer_sn,num);
}

// create sn list
function createList(order_pn,offer_sn,num)
{
    var str = $('#sns').val();
    var sns = "";
    var num_now = 0;

    if(str !=''){
        var list = str.split(','); 
        for (var i = 0; i < list.length; i++) {
            sns += "<label class=\"label label-info\">"+list[i]+"</label> "
            //sns += "<a class=\"btn btn-xs btn-warning sn-label\" href=\"javascript:cancel("+order_pn+','+list[i]+','+num+")\">"+list[i]+"</a>";
        }
        num_now = list.length;
        // set submit buttun
        if(list.length == num){
            $('#offers-sub').html("<a class=\"btn btn-success btn-sm\" href=\"javascript:subDeliver();\">确定发货</a>");
        }else{
            $('#offers-sub').html("");
        }
    }else{
        $('#offers-sub').html("");
    }
    //console.log(sns);
    $('#offers-num').html(num_now+'/'+num+": ");
    $('#offers-sn').html(sns);
}

// sub deliver
function subDeliver()
{
    var str = $('#sns').val();
    var pn = $('#offer-pn').val();
    var goods_sn = $('#offer-goods-sn').val();
    var num = $('#offer-num').val();


    var post = new Object();
    post.str = str;
    post.pn = pn;
    post.goods_sn = goods_sn;
    post.num = num;
    post = JSON.stringify(post);

    var url = "/offer/deliver/set";

    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            //console.log('posting..');
        },
        success: function(json){
            //console.log(json);
            window.location.reload();
        }
    });
}

// sale offers
function sale(id)
{
    var url = "/offer/sale";
    var post = new Object();
    post.id = id;
    post = JSON.stringify(post);

    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            console.log('posting..');
        },
        success: function(json){
            console.log(json);
            var tu = "<div class=\"thumbnail\">";
            tu += "<img src=\"" +json.goods_img+ "\">";
            tu += "</div>";

            var details = json.fashion_name+'('+json.goods_name+')';
            if(json.goods_gold) details += "; "+json.goods_gold+'-'+json.gold_weight;
            if(json.goods_stone) details += "; "+json.goods_stone+'-'+json.stone_weight;
            if(json.other) details += "; "+json.other;

            var need_label = '';
            if(json.need_label == '' || json.need_label == null){
                need_label = " <span id=\"need_label_btn\"><a class=\"btn btn-warning btn-xs\" href=\"javascript:needLabel("+json.id+");\">标签重打</a></span>";
            }

            $('#sale-sub').html("<a class=\"btn btn-success btn-sm\" href=\"javascript:submitSale();\">登记销售</a>");
            $('#sale-img').html(tu);
            $('#sale-sn').html(json.sn+need_label);
            $('#sale-price').html('￥'+json.goods_price);
            $('#sale-details').html(details);
            $('#price').val(Number(json.goods_price));
            $('#sale-id').val(json.id);
            $('#sale-model').modal('show');
        }
    });
}

// sub
function submitSale()
{
    var id = $('#sale-id').val();
    var price = $('#price').val();

    if(id == '' || price == '') {
        var w = "<div class=\"alert alert-danger\">价格不得为空</div>";
        $('#sale-warn').html(w);
        return false;
    }

    var url = "/offer/sale/set";
    var post = new Object();
    post.id = id;
    post.sold_price = price;
    post = JSON.stringify(post);

    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            console.log('posting..');
        },
        success: function(json){
            //console.log(json);
            var w = '';

            if(json.resault == 'ok'){
                w += "<div class=\"alert alert-success\">"+json.msg+"</div>";
            }else{
                w += "<div class=\"alert alert-danger\">"+json.msg+"</div>";
            }
            $('#sale-warn').html(w);
            $('#sale-sub').html('');
            $('#sale-model').on('hidden.bs.modal', function () {
              window.location.reload();
            });

        }
    });
}

// set shop input
function setShop(shop_id)
{
    $('#shop').val(shop_id);
    seekKey();
}
// seek with key
function seekKey()
{
    var shop = $.trim($('#shop').val());
    var key = $.trim($('#key').val());
    var url = '/offer';

    if(key != '' && shop != '') {
        url += "?key="+key+"&shop="+shop;
    }else if(key != '' && shop ==''){
        url += "?key="+key;
    }else if(key == '' && shop !=''){
        url += "?shop="+shop;
    }
    
    location.href = url;
}

// use wechat
function useWechat(){  
    var agent = navigator.userAgent.toLowerCase();  
    if(agent.match(/MicroMessenger/i)=="micromessenger") {  
        return true;  
    } else {  
        return false;  
    }  
} 

// needLabel
function needLabel(id)
{
    var url = '/offer/label/add';
    var post = new Object();
    post.id = id;
    post = JSON.stringify(post);
    
    $.ajax({
        type: "POST",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: post,
        dataType: "json",
        beforeSend: function(){
            //console.log('posting..');
        },
        success: function(json){
            $('#need_label_btn').html("<span class=\"label label-success label-xs\">"+json.msg+"</span>");
        }

    });
}









