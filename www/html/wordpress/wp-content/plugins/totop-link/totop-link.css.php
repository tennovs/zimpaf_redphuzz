<?php header('Content-type: text/css');
$vars = unserialize(base64_decode($_GET['vars']));
$width = (!empty($vars['width'])) ? 'width:'.$vars['width'].'px;' : '';
$height = (!empty($vars['height'])) ? 'height:'.$vars['height'].'px;' : '';
$txtcolour1 = (!empty($vars['text-style'][0])) ? 'color:'.$vars['text-style'][0].';' : '';
$txtcolour2 = (!empty($vars['text-style'][1])) ? 'color:'.$vars['text-style'][1].';' : '';
?>

#toTop {position:fixed; bottom:auto; right:auto; left:auto;top:auto;cursor:pointer;display:none;-moz-opacity:.50; filter:alpha(opacity=50); opacity:.50;z-index:99999;<?php echo $width.$height.$txtcolour1; ?>}
#toTop:hover {-moz-opacity:1; filter:alpha(opacity=100); opacity:1;<?php echo $txtcolour2; ?>}
#toTop img {display:block;}
#toTop span {display:none;}

#toTop.totop-text {background:none;-moz-opacity:1; filter:alpha(opacity=100); opacity:1;}
#toTop.totop-text span {display:inline;}

#toTop.totop-br {bottom:40px;right:10px;}
#toTop.totop-bl {bottom:40px;left:10px;}
#toTop.totop-bm {bottom:40px;left:49%;}
#toTop.totop-tr {top:40px;right:10px;}
#toTop.totop-tl {top:40px;left:10px;}
#toTop.totop-tm {top:40px;left:49%;}
#toTop.totop-ml {top:49%;left:10px;}
#toTop.totop-mr {top:49%;right:10px;}

<?php if (!empty($vars['pos'])) {
$pos_cust = '';
foreach ($vars['pos'] as $key => $val) {
	if ($val != null) { $pos_cust .= $key.':'.$val.'px;'; }
} ?>
#toTop.totop-custom {<?php echo $pos_cust; ?>}
<?php } ?>

<?php if (!empty($vars['rwd_max_width'])) { ?>
@media screen and (max-width: <?php echo $vars['rwd_max_width']; ?>) {
  #toTop { display: none !important; }
}
<?php } ?>