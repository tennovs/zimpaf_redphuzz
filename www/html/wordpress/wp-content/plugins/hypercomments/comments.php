<div id="HyperComments_Box">
	<?php echo hc_show_script();?>	
	<div id="hc_full_comments">
		<?php $comments = hc_get_comments_post();?>		
		<?php for($i=0;$i<count($comments);$i++):?>	
		<div style='position:relative;padding:5px;font-size:12px;'>
			<div style='position:absolute; width:36px'><?php echo get_avatar($comments[$i]->user_id)?></div>
			<div style='margin-left:50px;'>
				<div style='float:left;margin-right:5px;color:#3B5998;font-size: 11px;font-family: tahoma,verdana,arial,sans-serif;font-weight: bold;'><?php echo htmlentities($comments[$i]->comment_author, ENT_QUOTES, 'UTF-8');?></div>
				<div style='color: gray;font-size:10px;'><?php echo $comments[$i]->comment_date;?></div>
				<div style='padding:5px;'><?php echo htmlentities($comments[$i]->comment_content, ENT_QUOTES, 'UTF-8');?></div>
			</div>
		</div>
		<?php endfor;?>
	</div>
<!-- <p><?php echo 'HC version: '.HC_VERSION;?></p> -->
</div>
<script type="text/javascript">
	document.getElementById('hc_full_comments').innerHTML = '';
</script>