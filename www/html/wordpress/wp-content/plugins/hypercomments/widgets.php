<?php
/**
 * Return comments widget
 */
function hc_get_comment_widget($hc_wid, $hc_lang, $hc_url, $hc_auth, $hc_append, $hc_word_limit, $hc_realtime, $hc_comments_level, $hc_css)
{
	if(!$hc_append || strlen($hc_append) == 0)$hc_append = '#hypercomments_widget';
	if(!$hc_word_limit || strlen($hc_word_limit) == 0)$hc_word_limit = 10;
	if(!$hc_comments_level || strlen($hc_comments_level) == 0)$hc_comments_level = 4;
	if(strlen($hc_realtime) == 0)$hc_realtime = 'true';

	if(strlen($hc_lang) == 0) {
		$hc_lang = '"'.hc_get_language().'"';
		update_option('hc_locale', hc_get_language());
	} else if($hc_lang == 'default') {
		$hc_lang = '(navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase()';
	} else {
		$hc_lang = '"'.$hc_lang.'"';
	}

	ob_start();
?>
	<div id="hypercomments_widget"></div>
    <script type="text/javascript">                                      
    var _hcwp = _hcwp || [];
    var _hc_real = {widget_id : <?php echo $hc_wid?>, widget : "Stream",
     append: '<?php echo $hc_append?>', css: '<?php echo $hc_css?>',
     words_limit: <?php echo $hc_word_limit?>, realtime: <?php echo ($hc_realtime == 1) ? 'true' : 'false';?>, comments_level: <?php echo $hc_comments_level?>,
     platform: "wordpress", xid: '<?php echo $hc_url?>' <?php echo $hc_auth?>};
    _hcwp.push(_hc_real);              
    (function() { 
    if("HC_LOAD_INIT" in window)return;
    HC_LOAD_INIT = true;
    var lang = <?php echo $hc_lang; ?>;
    var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
    hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget<?php if(HC_DEV){echo 'test';} ?>/hc/<?php echo $hc_wid?>/"+lang+"/widget.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); 
    })();
    </script>
    <?php if(!in_array(get_option('hc_plan'), array(3,4,6))):?>
    <a href="http://hypercomments.com" class="hc-link" title="comments widget">comments powered by HyperComments</a>
	<?php endif;?>
<?php
	$output = ob_get_clean();
    return $output;
}

/**
 * Return new div comments widget
 */
function hc_new_append_widget($hc_append)
{
	ob_start();
	if($hc_append == '#hypercomments_widget' || $hc_append == '')
		$hc_append = '#hypercomments_widget_newappend';
?>
	<div id="hypercomments_widget_newappend"></div>
	<script type="text/javascript">
	_hc_real['append'] = '<?php echo $hc_append;?>';
	</script>
<?php
	$output = ob_get_clean();
    return $output;
}

/**
 * Return counter widget
 */
function hc_get_counter_widget($hc_wid, $hc_selector, $hc_lang, $hc_counter_label)
{	
	if(strlen($hc_lang) == 0) {
		$hc_lang = '"'.hc_get_language().'"';
		update_option('hc_locale', hc_get_language());
	} else if($hc_lang == 'default') {
		$hc_lang = '(navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase()';
	} else {
		$hc_lang = '"'.$hc_lang.'"';
	}
	ob_start();
?>
	<script type="text/javascript">
	var _hcwp = _hcwp || [];
	var _hcobj = {widget_id : <?php echo $hc_wid;?>, widget : "Bloggerstream",selector: '<?php echo $hc_selector;?>',platform:"wordpress", <?php echo $hc_counter_label;?>};
	_hcwp.push(_hcobj);
	(function() {
	if("HC_LOAD_INIT" in window)return;
    HC_LOAD_INIT = true;
    var lang = <?php echo $hc_lang; ?>;
    var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
    hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget<?php if(HC_DEV){echo 'test';} ?>/hc/<?php echo $hc_wid?>/"+lang+"/widget.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); 
    })();
    </script>
<?php
	$output = ob_get_clean();
    return $output;
}

/**
 * Return admin widget
 */
function hc_get_adm_widget($hc_wid, $hc_lang)
{
	ob_start();
?>
	<div id="hc_adm_widget"></div>
	<script type="text/javascript">
	    var _hcp = {};
	    _hcp.append = "#hc_adm_widget";
	    _hcp.height = jQueryHC(window).height() - 120;
	    <?php if(HC_DEV): ?>
	    _hcp.url = document.location.protocol + "//testadmin.hypercomments.com";
	    <?php endif;?>
	    (function() { 
	        var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
	        hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://widget.hypercomments.com/apps/js/hc0.js";
	        var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); 
	    })();
	</script>
<?php
	$output = ob_get_clean();
    return $output;
}

?>