<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title><?php echo (C("website")); ?>微盘</title>
<link rel="stylesheet" href="/vpan/Public/Home/css/global.css">
<link rel="stylesheet" href="/vpan/Public/Home/css/charge.css">
<script id="G--xyscore-load" type="text/javascript" charset="utf-8" async="" src="/vpan/Public/Home/js/xyscore_main.js"></script>
</head>
<body>
<div class="wrap">
  <div class="index" style="min-height: 1114px;">
    <header class="list-head">
      <nav class="list-nav clearfix"> <a href="javascript:history.go(-1)" class="list-back"></a>
        <h3 class="list-title">账户充值</h3>
      </nav>
    </header>

    <ul class="account-info">
      <li>账户名称：<?php echo ($suer['nickname']); ?></li>
      <li>余额：<span><?php echo ($result['balance']); ?></span><i>元</i></li>
    </ul>
    <!-- 
    <?php if($style == '1'): ?>-->
         <form id="moneyCharge1" class="" method="post" action="Extend/kdpay/doPay.php?openid=<?php echo ($MyOpenid); ?>">
          <p class="c-line clearfix" >
            <label class="fl">充值</label>
            <em>元</em>
            <input type="text" class="c-input" id="pric" maxlength="6"  value="10" name="tfee1">
           </p>
          <!-- <input id="order_id" type="hidden"  name="order_id" value="<?php echo ($balc["bpno"]); ?>"> -->
          <input type="submit" id="buton" class="f-sub" value="立即充值">
          </form>
          <!-- 
     <?php else: ?>
         <form id="moneyCharge2" class="" method="post" action="Extend/weipay/jsapi.php">
         <p class="c-line clearfix" >
          <label class="fl">充值</label>
          <em>元</em>
          <input type="text" class="c-input" maxlength="6" value="<?php echo ($balc["bpprice"]); ?>" name="tfee">
         </p>
        <input id="order_id" type="hidden"  name="order_id" value="<?php echo ($balc["bpno"]); ?>">
        <input type="submit" class="f-sub" value="确定订单页面">
        </form><?php endif; ?>
      -->
  </div>
 
</div>
<div class="xiaoxi"><div id="msg" class="msg"></div></div>
<script src="/vpan/Public/Home/js/jquery-2.1.1.min.js"></script>
<script src="/vpan/Public/Home/js/script.js"></script>
<script type="text/javascript">
$('#buton').click(function(){
//    if ($('#pric').val()>=1) {
//        $('#moneyCharge1').submit();
//    }else{
//       msg("充值金额不能少于1元");
//       return false;
//    }
    if ($('#pric').val()>=10) {
        $('#moneyCharge1').submit();
    }else{
       msg("充值金额不能少于10元");
       return false;
    }
});

$(function(){  
  if ($('#order_id').val()!='') {
       $('#moneyCharge2').submit();
    };
});
</script>
<script type="text/javascript" charset="utf-8" src="/vpan/Public/Home/js/sea.js" async=""></script>
</body>
</html>