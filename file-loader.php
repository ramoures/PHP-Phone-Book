<?php
try {
	define('ROOT_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
	require_once ROOT_PATH.'config.php';
	$basedir = ROOT_PATH.'media';
	$file = rtrim($basedir,'/').'/'.str_replace('..', '', isset($_GET['file']) && !is_array($_GET['file'])?htmlentities(addslashes(trim($_GET['file'])), ENT_QUOTES, 'UTF-8'):'');
	if (!$basedir || !is_file($file) || !file_exists($file)) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Document Not Found!', true, 404);
		die('404 Document Not Found!');
	}
	$mime = mime_content_type($file);
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$allowTypes = array_values(ALLOW_FILES_TYPE);
	$allowMimes = array_keys(ALLOW_FILES_TYPE);
	if(!in_array($ext,$allowTypes) && !in_array($mime,$allowMimes)){
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		die('500 Internal Server Error!');
	}
	header('Content-Type: ' . $mime);
	if (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === false)
		header('Content-Length: ' . filesize($file));
	$last_modified = gmdate('D, d M Y H:i:s', filemtime($file));
	$etag = '"' . sha1("ui*ok2JsAQi".$last_modified."sKi98^0cs3Fn".SECRET_KEY) . '"';
	header("Last-Modified: $last_modified GMT" );
	header('ETag: ' . $etag);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 100000000) . ' GMT');
	$client_etag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) : false;
	if(!isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
		$_SERVER['HTTP_IF_MODIFIED_SINCE'] = false;
	$client_last_modified = trim($_SERVER['HTTP_IF_MODIFIED_SINCE']);
	$client_modified_timestamp = $client_last_modified ? strtotime($client_last_modified) : 0;
	$modified_timestamp = strtotime($last_modified);
	if(($client_last_modified && $client_etag)?(($client_modified_timestamp >= $modified_timestamp) && ($client_etag == $etag)):(($client_modified_timestamp >= $modified_timestamp) || ($client_etag == $etag))){
		header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified!', true, 304);
		die('304 Not Modified!');
	}
	print file_get_contents($file);
	die;
} catch (\Throwable $th) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	die('500 Internal Server Error!');
}
?>