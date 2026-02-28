<!DOCTYPE html>
<?php require('rezgo/include/page_header.php');?>
    <body>

        <script>
			let url = window.location.protocol + '//' + window.location.hostname + '/' + '<?php echo $_REQUEST['wp_slug']?>';
			console.log(url);
        	window.top.postMessage('3DS-authentication-complete', url);
        </script>

    </body>
</html>