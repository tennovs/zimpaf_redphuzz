<?php
function rezgo_return_file($filePath = '', $additionalVars = array()) 
{
	reset($GLOBALS);
	foreach($GLOBALS as $key => $val) {
		if(($key != strstr($key,"HTTP_")) && ($key != strstr($key, "_")) && ($key != 'GLOBALS')) {
			global ${$key};
		} 
	}

	extract($additionalVars);

	if (is_file(dirname(__FILE__) . DIRECTORY_SEPARATOR . $filePath)) {
		ob_start();
		include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $filePath);
		$contents = ob_get_contents();
		ob_end_clean();
	} else {
		$this->error('"'.$filePath.'" file not found');
	}
	return $contents;
}

function rezgo_include_file($filePath = '', $additionalVars = array())
{
	extract($additionalVars);
	include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $filePath);
}

function rezgo_include_settings_file($filePath = '')
{
	include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings' .DIRECTORY_SEPARATOR . $filePath);
}

function rezgo_render_settings_view($viewFile = '', $vars)
{
	extract($vars);
	include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'settings/views' .DIRECTORY_SEPARATOR . $viewFile);
}

function rezgo_embed_settings_image($imageName)
{
    return plugins_url('/settings/images/' . $imageName, __FILE__);
}

function rezgo_base64_svg () {
    $base64_icon = ' data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI0LjMuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiCgkgdmlld0JveD0iMCAwIDEyNi42NiAxMzkuMzYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDEyNi42NiAxMzkuMzY7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbDojRjJGMkYzO30KCS5zdDF7ZmlsbDojRjFGMkYyO30KPC9zdHlsZT4KPGcgaWQ9IkxheWVyXzIiPgo8L2c+CjxnIGlkPSJMYXllcl8xIj4KCTxnPgoJCTxnPgoJCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTcuNjYsMzYuMzFjOS4xOSwwLDE2LjY5LTcuNSwxNi42OS0xNi42OWMwLTkuMTktNy41LTE2LjY5LTE2LjY5LTE2LjY5Yy05LjE5LDAtMTYuNjksNy41LTE2LjY5LDE2LjY5CgkJCQlDMC45NywyOC44MSw4LjQ3LDM2LjMxLDE3LjY2LDM2LjMxTDE3LjY2LDM2LjMxeiIvPgoJCQk8cGF0aCBjbGFzcz0ic3QxIiBkPSJNNjMuMzMsMTM5LjM2Yy0yNi45NSwwLTUxLjIyLTE0Ljk3LTYzLjMzLTM5LjA3bDE2Ljc5LTguNDRjOC45LDE3LjcxLDI2LjczLDI4LjcyLDQ2LjU0LDI4LjcyCgkJCQlzMzcuNjQtMTEsNDYuNTQtMjguNzJsMTYuNzksOC40NEMxMTQuNTUsMTI0LjM5LDkwLjI5LDEzOS4zNiw2My4zMywxMzkuMzZ6Ii8+CgkJCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik05Ni4yMiwwQzcyLjQ3LDAsNTMuMTQsMTguNTUsNTMuMTQsNDIuMzF2NTUuNjdoMGMxMS4yMSwwLDIwLjMtOS4wNywyMC4zNi0yMC4yN3YtMzUuNAoJCQkJYzAtMTIuNTMsMTAuMTktMjMuNTcsMjIuNzItMjMuNTdoOC4xMmM5LjY1LTAuNzgsMTcuMjUtOC44NCwxNy4yNi0xOC43VjBIOTYuMjJ6Ii8+CgkJPC9nPgoJPC9nPgo8L2c+Cjwvc3ZnPgo=';
    return $base64_icon;
}

function rezgo_curl_get_page($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT,30);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$file = curl_exec($ch);

	curl_close($ch);

	$result = simplexml_load_string($file);

	return $result;
}

