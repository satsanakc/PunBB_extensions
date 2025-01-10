<?php 
if (!defined('SAT_SHOP_PAY')) die();

$mrh_login = $forum_config['o_sat_shop_mrh_login'];
$mrh_pass = $forum_config['o_sat_shop_testmode'] == '1' ? $forum_config['o_sat_shop_test_pass1'] : $forum_config['o_sat_shop_mrh_pass1'];
$encoding = "utf-8";
$shp_item = $sat_goods['id'];
$inv_desc = $sat_goods['name'];
$out_summ = $sat_goods['price'];
$email = $forum_user['email'];
$shp_user = $forum_user['id'];
$crc  = md5("$mrh_login:$out_summ::$mrh_pass:Shp_item=$shp_item:Shp_user=$shp_user");

  print
   "<form action='https://auth.robokassa.ru/Merchant/Index.aspx' method=POST>".
   "<input type=hidden name=MerchantLogin value=$mrh_login>".
   "<input type=hidden name=OutSum value=$out_summ>".
   "<input type=hidden name=InvId value=$inv_id>".
   "<input type=hidden name=Description value='$inv_desc'>".
   "<input type=hidden name=SignatureValue value=$crc>".
   "<input type=hidden name=Shp_item value='$shp_item'>".
   "<input type=hidden name=IncCurrLabel value=$in_curr>".
   "<input type=hidden name=Culture value=$culture>".
   "<input type=hidden name=Email value=$Email>".
   "<input type=hidden name=ExpirationDate value=$ExpirationDate>".
   "<input type=hidden name=OutSumCurrency value=$OutSumCurrency>".
   "<input type=submit value='РћРїР»Р°С‚РёС‚СЊ'>".
   "</form>";