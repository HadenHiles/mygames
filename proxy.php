<?php

define('USER_IP', $_SERVER['REMOTE_ADDR']);
define('USER_IP_LONG', sprintf("%u", ip2long(USER_IP)));

if(!function_exists("curl_init")){
	die("cURL extension was not found on this server.");
}	
/* START OF 

require("config.php"); */

?><?php

// all possible options will be stored
$config = array();

// for scripts spread out to multiple servers - on error let's redirect back to main site along with error message
//$config['error_redirect'] = "http://www.proxynova.com/test.php?error={error_type}&message={error_msg}";

// for extra privacy and speed - remove all javascript from pages
$config['remove_script'] = false;

// enable cookie functionality?
$config['enable_cookies'] = true;

/*
	0 - urls are not unique - no encryption used apart from base64_encode(url) - very fast
	1 - all urls are unique to that session
	2 - urls are unique to the ip address that generated that url
*/
$config['unique_urls'] = 0;

// replace the title of every page with this - or false to leave the title alone
$config['replace_title'] = 'Google';

// custom user agent - set it to: $_SERVER['HTTP_USER_AGENT'] to use users own agent
$config['user_agent'] = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';

// what ip address to use when making a request?
//$config['ip_addr'] = false;//'';

// "blocked_ip" error will be thrown if any of the ips on this array tries using our proxy
$config['blocked_ips'] = array(
	'67.184.200.251',
	'123.123.123.123'
);

/*
// "blocked_domain" error if user tries accessing any of these domains
$config['blocked_domains'] = array(
	'youtube.com',
	'facebook.com',
	'xvideos.com',
	'redtube.com'
);
*/

// UNDER CONSTRUCTION!!!! means nothing at the moment
$config['enable_logging'] = false;

?><?php 
	
/* END OF 

require("config.php"); */
	
/* START OF 

require("common.php"); */

?><?php

function base64_url_encode($input){
	// = at the end is just padding to make the length of the str divisible by 4
	return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
}

function base64_url_decode($input){
	return base64_decode(str_pad(strtr($input, '-_', '+/'), strlen($input) % 4, '=', STR_PAD_RIGHT));
}

function data_rot($data, $pass, $reverse = false){
	
	$data_len = strlen($data);
	$pass_len = strlen($pass);
	
	if($pass_len == 0) trigger_error("fnc:data_rot password must not be empty!", E_USER_ERROR);
	
	$result = str_repeat(' ', $data_len);

	for($i=0; $i<$data_len; $i++){
		$asc = ord($data[$i])+(ord($pass[$i%$pass_len]) * ($reverse ? -1 : 1));
		$result[$i] = chr($asc);
	}
	
	return $result;
}

function encrypt_url($url){
	
	global $config;
	
	if($config['unique_urls'] === 2){
		$url = data_rot($url, USER_IP_LONG);
	}
	
	return base64_url_encode($url);
}

function decrypt_url($url){
	
	global $config;
	
	$url = base64_url_decode($url);
	
	if($config['unique_urls'] === 2){
		$url = data_rot($url, USER_IP_LONG, true);
	}
	
	return $url;
}

function add_http($url){
	if(!preg_match('#^https?://#i', $url)){
		$url = 'http://' . $url;
	}
	
	return $url;
}

function time_ms(){
	return round(microtime(true) * 1000);
}

function proxify_url($url){
	$url = htmlspecialchars_decode($url);
	$url = rel2abs($url, URL); // URL is the base
	// Added rest parameter just in case some script adds to our proxified url.
	return SCRIPT_BASE.'?q='.encrypt_url($url).'&rest=';
}

function contains($needle, $haystack){

	if(is_array($needle)){
		
		foreach($needle as $n){
			if(contains($n, $haystack)){
				return true;
			}
		}
		
		return false;
	
	} else if(is_array($haystack)){
		
		foreach($haystack as $h){
			if(contains($needle, $h)){
				return true;
			}
		}
		
		return false;
	}
	
	return strpos($haystack, $needle) !== false;
}


function str_contains($str, $arr){
	if(!is_array($arr)) return false;
	
	foreach($arr as $item){
		if(strpos($str, $item) !== false) return true;
	}
	
	return false;
}


// default cookie format: COOKIE_PREFIX+domain__cname=cvalue;
function decode_http_cookie(){

	// 2 fucking days spent figuring this out... suhosin.cookie.max_name_length
	$http_cookie = $_SERVER['HTTP_COOKIE'];
	$cookie_pairs = array();
	
	if(preg_match_all('@'.COOKIE_PREFIX.'(.+?)__(.+?)=([^;]+)@', $http_cookie, $matches, PREG_SET_ORDER)){
	
		foreach($matches as $match){
		
			$domain = $match[1];
			$domain = str_replace("_", ".", $domain);
			
			$name = $match[2];
			$value = $match[3];
			
			// does that cookie belong to that domain
			if(strpos(URL_HOST, $domain) !== false){
				$cookie_pairs[] = "{$name}={$value}";
			}
		}
	}
	
	return "Cookie: ".implode("; ", $cookie_pairs);
}

function replace_placeholders($str, $callback = null){

	global $tpl_vars;

	preg_match_all('@{(.+?)}@s', $str, $matches, PREG_SET_ORDER);
	
	foreach($matches as $match){
	
		$var_val = $tpl_vars[$match[1]];
		
		if(function_exists($callback)){
			$var_val = @call_user_func($callback, $var_val);
		}
		
		$str = str_replace($match[0], $var_val, $str);
	}
	
	return $str;
}

/*
function log(){
	// log it!
	$date = date("Y-m-d H:i:s T");

	$filename = date("m.d.y").'.log';

	$format = "{ip} {date} {url} {status}";

	$file = fopen('./logs/'.$filename, 'a');
	fwrite($file, $_SERVER['REMOTE_ADDR'].' ['.$date.'] "'.$tpl_vars['url']."\"\n");
	fclose($file);
}*/

function rel2abs($rel, $base)
{
	if (strpos($rel, "//") === 0) {
		return "http:" . $rel;
	}

	/* return if  already absolute URL */
	if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
	/* queries and  anchors */
	if ($rel[0] == '#' || $rel[0] == '?') return $base . $rel;
	/* parse base URL  and convert to local variables:
	$scheme, $host,  $path */
	extract(parse_url($base));
	/* remove  non-directory element from path */
	$path = preg_replace('#/[^/]*$#', '', $path);
	/* destroy path if  relative url points to root */
	if ($rel[0] == '/') $path = '';
	/* dirty absolute  URL */
	$abs = "$host$path/$rel";
	/* replace '//' or  '/./' or '/foo/../' with '/' */
	$re = array(
		'#(/\.?/)#',
		'#/(?!\.\.)[^/]+/\.\./#'
	);
	for ($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {
	}

	/* absolute URL is  ready! */
	return $scheme . '://' . $abs;
}


?><?php 
	
/* END OF 

require("common.php"); */
	
/* START OF 
require("http.php"); */

?><?php

class Http {

	// response info from request
	private $status;
	private $content_type;
	private $headers = array();
	
	// output data immediately after receiving it
	private $text = false;
	
	// curl stuff
	private $options;
	private $error;
	
	// will remain empty if we decide to output response immediately after reading
	private $data;
	
	/*
	http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2
	*/
	// list of headers we care about to forward back to user
	
	private $forward = array('content-type', 'content-length', 'accept-ranges', 
		'content-range', 'content-disposition', 'location');
		
	private $mime_types = array(
		'text/html' => 'html',
		'text/plain' => 'html',
		'text/css' => 'css',
		'text/javascript' => 'js',
		'application/x-javascript' => 'js',
		'application/javascript' => 'js'
	);

	private function send_no_cache()
	{
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	
	private function headers_write($ch, $headers){
	
		$len = strlen($headers);
		$parts = explode(": ", $headers, 2);

		// must be an actual name:value header
		if(count($parts) == 2){
			
			$name = strtolower($parts[0]);
			$value = rtrim($parts[1]);
			
			// is it one of the headers we care about to forward back to user?
			if(in_array($name, $this->forward)){
			
				// proxify url that the url is trying to redirect to
				if($name == 'location'){
					$value = proxify_url($value);
				}
				
				$this->headers[$name] = $value;
			}
			
			// should we save output to string for parsing later or stream immediately?
			if($name == 'content-type'){

				// text/html; charset=utf-8
				$sc_pos = strpos($value, ";");
				$type = substr($value, 0, $sc_pos ? $sc_pos : 1024);
				
				if(isset($this->mime_types[$type])){
					$this->text = true;
				}
				
				$this->content_type = $type;
			
			} else if($name == 'set-cookie'){
			
				$this->forward_cookie($value);
			}
			
		} else if($len > 2){
		
			// must be status
			$this->status = $headers;
			
			// send immediately
			header($this->status);
			
			// no cache
			$this->send_no_cache();
			
		} else {

			// end of headers
			foreach($this->headers as $name => $value){
				header("{$name}: {$value}");
			}
			
			if(!isset($this->mime_types[$this->content_type]) && !isset($this->headers['content-disposition'])){
			
			
				//header('Content-Disposition: filename="playaa.mp4"');
			
			}
		}
		
		return $len;
	}
	
	// convert Set-Cookie: header value into proxy cookie
	private function forward_cookie($header){
		$nv_pairs = explode(";", $header);
		
		// cookie attributes we care about
		$name = '';
		$value = '';
		$expires = '';
		$domain = '';
		
		foreach($nv_pairs as $index => $pair){
			$pair = ltrim($pair);
			$parts = explode("=", $pair, 2);
			
			// first pair will always be cookie_name=value
			if($index == 0){
				$name = $parts[0];
				$value = $parts[1];
			} else if($parts[0] == 'expires'){
				$expires = $parts[1];
			} else if($parts[0] == 'domain'){
				$domain = $parts[1][0] == '.' ? substr($parts[1], 1) : $parts[1];
			}
		}

		$expires = empty($expires) ? 0 : strtotime($expires);
		$domain = empty($domain) ? URL_HOST : $domain;
		
		$cookie_name = COOKIE_PREFIX.str_replace(".", "_", $domain).'__'.$name;
		
		//var_dump("Set-Cookie before: ".$header);
		//var_dump("Set-Cookie after: ".$cookie_name."=".$value);
		
		setcookie($cookie_name, $value, time() + 60*60*60);
	}
	
	private function body_write($ch, $str)
	{
		$len = strlen($str);
	
		if($this->text){
			$this->data .= $str;
		} else {
			echo $str;
		}
		
		return $len;
	}
	
	function __construct()
	{
		$this->options = array(
			CURLOPT_CONNECTTIMEOUT 	=> 8,
			CURLOPT_TIMEOUT 		=> 0,
			
			// don't return anything - we have other functions for that
			CURLOPT_RETURNTRANSFER	=> false,
			CURLOPT_HEADER			=> false,
			
			// don't bother with ssl
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_SSL_VERIFYHOST	=> false,
			
			// let curl take care of redirects
			CURLOPT_FOLLOWLOCATION	=> false,
			CURLOPT_MAXREDIRS		=> 5,
			CURLOPT_AUTOREFERER		=> false
		);
		
		$this->options[CURLOPT_HEADERFUNCTION] = array($this, 'headers_write');
		$this->options[CURLOPT_WRITEFUNCTION] = array($this, 'body_write');
		
		
		global $config;
		
		if(isset($config['ip_addr'])){
			$this->options[CURLOPT_INTERFACE] = $config['ip_addr'];
		}
		
		if(isset($config['user_agent'])){
			$this->options[CURLOPT_USERAGENT] = $config['user_agent'];
		}
		
		// let's emulate the browser
		$headers = array(
				'Accept-Language: en-US,en;q=0.5',
				'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        );
		
		$this->set_headers($headers);
	}
	
	function execute($url){
		$this->options[CURLOPT_URL] = $url;

		$ch = curl_init();
		curl_setopt_array($ch, $this->options);
		$re = curl_exec($ch);

		if(!$re){
			$this->error = sprintf('(%d) %s', curl_errno($ch), curl_error($ch));
		}
		
		return $re;
	}
	
	// will return either html, css, or js
	function get_simple_content_type(){
		$ct = $this->content_type;
		return isset($this->mime_types[$ct]) ? $this->mime_types[$ct] : false;
	}
	
	function set_headers($headers){
		$this->options[CURLOPT_HTTPHEADER] = $headers;
	}
	
	function error(){
		return $this->error;
	}
	
	function get_output(){
		return $this->data ? $this->data : false;
	}
	
	function set_post($post){
		if(is_array($post)){
			$post = http_build_query($post);
		}
		
		$this->options[CURLOPT_POST] = true;
		$this->options[CURLOPT_POSTFIELDS] = $post;
	}
}

?><?php 
	
/* END OF 
require("http.php"); */
	
/* START OF 
require("parse.php"); */

?><?php

// otherwise preg_replace_callback couldn't handle very large strings -- increase the default by 100
ini_set('pcre.backtrack_limit', 1000000 * 100);

function css_url($matches){
	$url = trim($matches[1]);
	
	if(stripos($url, 'data:') === 0){
		return $matches[0];
	}
	
	return 'url(\''.proxify_url($url).'\')';
}

function proxify_css($str){
	$str = preg_replace_callback('@url\s*\((?:\'|"|)(.*?)(?:\'|"|)\)@im', 'css_url', $str);
	
	return $str;
}

function html_href($matches){
	return 'href="'.proxify_url($matches[1]).'"';
}

function html_src($matches){

	if(stripos(trim($matches['url']), 'data:') === 0){
		return $matches[0];
	}
//	print_r($matches);
//	return $matches[0];

	return 'src='.$matches['quote'].proxify_url($matches['url']).$matches['quote'];
}

function html_action($matches){

	$action = proxify_url($matches[1]);
	return str_replace($matches[1], $action, $matches[0]);
	
	return 'action="'.proxify_url($matches[1]).'"';
}


function proxify_html($str){
	
	$str = proxify_css($str);

	// html
	$str = preg_replace_callback('@href=["|\'](.+?)["|\']@im', 'html_href', $str);
	$str = preg_replace_callback('@src=["|\'](?P<url>.+?)(?P<quote>["|\'])@i', 'html_src', $str);
	$str = preg_replace_callback('@<form[^>]*action=["|\'](.+?)["|\'][^>]*>@i', 'html_action', $str);
	
	$str = preg_replace_callback('@<meta\s*http-equiv="refresh"\s*content="[^;]*;\s*url=(.*?)"@i', 
	function($matches){
	
		return str_replace($matches[1], proxify_url($matches[1]), $matches[0]);
		
	}, $str);
	
	return $str;
}


// video player to be used
define('PLAYER_URL', '//www.php-proxy.com/assets/flowplayer-latest.swf');

function vid_player($url, $width, $height){

	$video_url = proxify_url($url); // proxify!
	$video_url = rawurlencode($video_url); // encode before embedding it into player's parameters
	
	$html = '<object id="flowplayer" width="'.$width.'" height="'.$height.'" data="'.PLAYER_URL.'" type="application/x-shockwave-flash">
 	 
       	<param name="allowfullscreen" value="true" />
		<param name="wmode" value="transparent" />
        <param name="flashvars" value=\'config={"clip":"'.$video_url.'", "plugins": {"controls": {"autoHide" : false} }}\' />
		
    </object>';
	
	return $html;
}

function get_youtube_links($html){

	function vn($a, $b){
		$c = $a[0];
		$a[0] = $a[$b % strlen($a)];
		$a[$b] = $c;
		return $a;
	}

	function sig_decipher($sig){
		$a = strrev($sig);
		
		//$a = substr($a, 2);
		$a = vn($a, 16);
		$a = vn($a, 35);
		
		return $a;
	}

	if(preg_match('@url_encoded_fmt_stream_map["\']:\s*["\']([^"\'\s]*)@', $html, $matches)){
		$parts = explode(",", $matches[1]);
		
		foreach($parts as $p){
			$query = str_replace('\u0026', '&', $p);
			parse_str($query, $arr);
			
			$url = $arr['url'];
			
			if(isset($arr['s'])){
				$s = sig_decipher($arr['s']);
				
				$url = $url.'&signature='.$s;
			}
			
			$result[$arr['itag']] = $url;
		}
		
		return $result;
	}
}

function get_dm($html){
	if(preg_match("@video_url%22%3A%22(.*?)%22%2C%22@is", $html, $matches))
	{
		$url = rawurldecode($matches[1]);
		$url = rawurldecode($url);
		return $url;
	}
	
	return false;
}

function get_redtube_vid($html){

	if(preg_match('@mp4_url=(.*?)&@', $html, $matches)){
		return rawurldecode($matches[1]);
	}
	return false;
}

function get_xhamster_vid($html){

	$file = false;

	if(preg_match("@mp4File=(.*?)\"@s", $html, $matches) == 1){
		$file = $matches[1];
		$file = rawurldecode($file);
	} else if(
	preg_match("@srv=([^&]+)@s", $html, $matches) == 1 && 
	preg_match("@file=([^&]+)@s", $html, $matches2) == 1){
		$srv = rawurldecode($matches[1]);
		$file = rawurldecode($matches2[1]);
		
		$file = "{$srv}/key={$file}";
	}
	
	return $file;
}

function get_xvideos($output){

	if(preg_match('@flv_url=([^&]+)@', $output, $matches)){
		return rawurldecode($matches[1]);
	}
	
	return false;
}

function replace_title($input, $replace = ''){
	return preg_replace('@<title>.*?<\/title>@s', '<title>'.$replace.'</title>', $input);
}

function remove_script($input){
	$result = preg_replace("@<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>@is", '', $input);
	$result = preg_replace("@<\s*script\s*>(.*?)<\s*/\s*script\s*>@is", '', $result);
	
	return $result;
}

// will only be called on html, js, css, and other text-like pages
function parse($url, $output, $type){

	// let's measure the time it takes to proxify and regex replace some file
	$start = time_ms();

	if(contains("xvideos.com/video", $url)){

		$flv_url = get_xvideos($output);
		
		$output = preg_replace('@<div id="player.*?<\\/div>@s', 
		'<div id="player">'.vid_player($flv_url, 588, 476).'</div>', $output, 1);
	}
	
	if(contains("xvideos.com", $url)){
	
		$output = preg_replace("@<script>thumbcastDisplayRandomThumb\\('(.*?)'\\)@s", "$1", $output);
	}
	
	if(contains('dailymotion.com/video/', $url)){
	
		$url = get_dm($output);
	
		$output = preg_replace(
				'#\<div\sclass\=\"dmpi_video_playerv4(.*?)>.*?\<\/div\>#s',
				'<div class="dmpi_video_playerv4${1}>'.vid_player($url, 620, 348).'</div>',
			$output, 1);
	}


	
	if(contains('youtube.com', $url)){
	
		$output = preg_replace('@masthead-positioner">@', 
		'masthead-positioner" style="position:static;">', $output, 1); 
	
		$output = preg_replace('#<img[^>]*data-thumb=#s','<img alt="Thumbnail" src=', $output);	
	}
	
	
	if(contains("youtube.com/watch", $url)){
	
		$links = get_youtube_links($output);
		$itags = array(5, 34, 35); // this is all we can support atm
		
		foreach($itags as $tag){
		
			if(isset($links[$tag])) {
				$vid_url = $links[$tag];
				
				$output = preg_replace('#<div id="player-api"([^>]*)>.*<div class="clear"#s', 
		'<div id="player-api"$1>'.vid_player($vid_url, 640, 390).'</div><div class="clear"', $output, 1);
		
				 break;
			}
		}
	}
	
	// remove all iframe
	$output = preg_replace('@<iframe.*?>.*?<\/iframe>@s', '', $output);
	
	if(contains("redtube.com", $url)){
	
		$vid = get_redtube_vid($output);
		
		if($vid){
			$output = preg_replace('@<div id="redtube_flv_player"(.*?)>.*?<noscript>.*?<\/noscript>.*?<\/div>@s', 
			'<div id="redtube_flv_player"$1>'.vid_player($vid, 610, 490).'</div>', $output);
		}
	}
	
	if(contains("xhamster.com", $url)){
	
		$vid = get_xhamster_vid($output);
		
		if($vid){
			$output = preg_replace('@<div id=\'player\'(.*?)<\/object>.*?</div>@s', 
			'<div id="player">'.vid_player($vid, 638, 505).'</div>', $output);
		}
	}

	

	global $config;

	if($type == 'html'){
	
		if($config['remove_script']){
			$output = remove_script($output);
		}
		
		$output = proxify_html($output);
	}

	if($config['replace_title']){
		$output = replace_title($output, $config['replace_title']);
	}
	
	if($type == 'css'){
		$output = proxify_css($output);
	}

	$time = time_ms() - $start;
	$output .= '<!-- parsed in '.$time.' milliseconds using proxy! -->';
	
	return $output;
}



?><?php 
	
/* END OF 
require("parse.php"); */
	
/* START OF 

require("static_tpl.php"); */

?><?php

$static_tpl = array();


$static_tpl['home'] = <<<'EOD'

<!DOCTYPE html>
<html>
<head>

<title>PHP-Proxy</title>

<meta name="generator" content="php-proxy.com">
<meta name="version" content="<?=$version;?>">

<style type="text/css">
html body {
	font-family: Arial,Helvetica,sans-serif;
	font-size: 12px;
}

#container {
	width:500px;
	margin:0 auto;
	margin-top:150px;
}

#error {
	color:red;
	font-weight:bold;
}

#frm {
	padding:10px 15px;
	background-color:#FFC8C8;
	
	border:1px solid #818181;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}

#footer {
	text-align:center;
	font-size:10px;
	margin-top:35px;
	clear:both;
}
</style>

</head>

<body>


<div id="container">

	<div style="text-align:center;">
		<h1 style="color:blue;">PHP-Proxy</h1>
	</div>
	
	<div id="error">
		<p><?=$error_msg;?></p>
	</div>
	
	<div id="frm">
	
		<form action="<?=$script_base;?>" method="post" style="margin-bottom:0;">
			<input name="url" type="text" style="width:400px;" autocomplete="off" placeholder="http://" />
			<input type="submit" value="Go" />
		</form>
		
		<script type="text/javascript">
			document.getElementsByName("url")[0].focus();
		</script>
	
	</div>
	
</div>

<div id="footer">
	Powered by <a href="//www.php-proxy.com/" target="_blank">PHP-Proxy</a>
</div>


</body>
</html>

EOD;















$static_tpl['url_form'] = <<<'EOD'


<style type="text/css">

html body {
	margin-top: 50px !important;
}

#top_form {
	position: fixed;
	top:0;
	left:0;
	width: 100%;
	
	margin:0;
	
	z-index: 2100000000;
	-moz-user-select: none; 
	-khtml-user-select: none; 
	-webkit-user-select: none; 
	-o-user-select: none; 
	
	border-bottom:1px solid #151515;
	
    background:#FFC8C8;
	
	height:45px;
	line-height:45px;
}

</style>

<script src="//www.php-proxy.com/assets/url_form.js"></script>

<div id="top_form">

	<div style="width:800px; margin:0 auto;">
	
		<form method="post" action="<?=$script_base;?>" target="_top" style="margin:0; padding:0;">
			<input type="button" value="Home" onclick="window.location.href='<?=$script_base;?>'">
			<input type="text" style="width:550px;" name="url" value="<?=$url;?>" autocomplete="off">
			<input type="hidden" name="form" value="1">
			<input type="submit" value="Go">
		</form>
		
	</div>
	
</div>

EOD;

?>
<?php 
	
/* END OF 

require("static_tpl.php"); */


// just in case
@include("override.php");

// suhosin has a limit of 512 max chars in $_GET
parse_str($_SERVER['QUERY_STRING'], $_GET);

/* constants to be used everywhere */
define('VERSION', '0.9');
define('SCRIPT_BASE', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

// these below will be defined later

//define('URL', '');
//define('URL_HOST', '');

define('COOKIE_PREFIX', 'dc_');

/* variables sent to each view template */
$tpl_vars = array(
	'script_base' => SCRIPT_BASE,
	'version' => VERSION
);

function throw_error($type, $message){

	global $tpl_vars, $config;
	
	$tpl_vars['error_type'] = $type;
	$tpl_vars['error_msg'] = $message;
	
	// handle this error somewhere else maybe?
	if(!empty($config['error_redirect'])){
		$url = $config['error_redirect'];
		$url = replace_placeholders($url, 'rawurlencode');
		
		header("HTTP/1.1 302 Found");
		header("Location: {$url}");
		exit;
	}
	
	echo tpl_include('home');
	exit;
}


function tpl_include($name){

	global $tpl_vars, $static_tpl;
	
	// declare variables in this scope to be used in our templates
	extract($tpl_vars);
	
	// this is where the views will be stored
	$file_path = './templates/'.$name.'.tpl.php';
	
	ob_start();
	
	if(!file_exists($file_path)){
		$code = $static_tpl[$name];
		
		if(empty($code)){
			die("Could not load such template");
		}
		
		// stolen from codeigniter...
		echo eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', $code)));
	} else {
		include($file_path);
	}
	
	$contents = ob_get_contents();
	@ob_end_clean();
	
	return $contents;
}

// url is being sent from a form raw - let's encode it and send it back to this script
if(isset($_POST['url'])){

	$url = $_POST['url'];
	$url = add_http($url);
	
	$q = encrypt_url($url);
	
	header("HTTP/1.1 302 Found");
	header('Location: '.SCRIPT_BASE.'?q='.$q);
	exit;
	
} else if(isset($_GET['q'])){

	// url sent encoded - let's decode it and make it work
	$url = decrypt_url($_GET['q']);
	
	$restOfUrl = $_GET['rest'];
	$url = $url . $restOfUrl;
	
	define('URL', $url);
	define('URL_HOST', parse_url($url, PHP_URL_HOST));
	
	$tpl_vars['url'] = $url;
	
} else {
	
	echo tpl_include('home');
	exit;
}

// banned ip trying to use our script?
if(@in_array(USER_IP, $config['blocked_ips'])){
	throw_error("blocked_ip", "Requests from this IP address have been blocked for some reason");
}

// are we trying to access a site that's been blocked?
if(str_contains(URL_HOST, $config['blocked_domains'])){
	throw_error("blocked_domain", "This domain has been blocked");
}



$possible_headers = array(
	'HTTP_ACCEPT' => 'Accept',
	'HTTP_ACCEPT_CHARSET' => 'Accept-Charset',
	'HTTP_ACCEPT_LANGUAGE' => 'Accept-Language'
);

$send_headers = array();

foreach($possible_headers as $key => $value){
	if(isset($_SERVER[$key])){
		$send_headers[] = $value.': '.$_SERVER[$key];
	}
}

// pass on any cookies sent to our proxy script from previous sessions
if($config['enable_cookies']){
	$send_headers[] = decode_http_cookie();
}

$http = new Http();

// send additional headers from our user to our http object to be sent to each url we visit
$http->set_headers($send_headers);

// do we wish to send some post data?
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$http->set_post($_POST);
}

// ready to execute!!!
$http->execute(URL);

// get output if available
$output = $http->get_output();

// if has output - we need to parse it
if($output && function_exists('parse')){

	// what kind of page was returned
	$content_type = $http->get_simple_content_type();

	$output = parse($url, $output, $content_type);

} else if($http->error()){

	throw_error("curl_error", $http->error());
}


if($content_type == 'html'){

	$url_form = tpl_include('url_form');

	// does the html page contain <body> tag
	$output = preg_replace('@<body.*?>@is', '$0'.PHP_EOL.$url_form, $output, 1, $count);

	// <body> tag was not found, just put the form at the top of the page
	if($count == 0){
		$output = $url_form.$output;
	}
}

echo $output;

?>