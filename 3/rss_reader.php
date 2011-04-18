<?php
/**
 * A simple RSS URL reader
 * 
 * Uses Google Feed API (http://code.google.com/apis/feed/v1/devguide.html) 
 * to parse RSS URL and display
 * the content stored in the RSS
 * 
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"> 
		<title>RSS Reader</title>
		<link href="css/style.1.css" type="text/css" rel="stylesheet"> 
	</head>
	<body>
		<div id="holderUrl">
			<!-- ERROR HOLDER  -->
			<div id="errorHolder">
			</div>
			<!-- RSS URL AND READ BUTTON  -->
			<div id="top">
				<input type="text" id="rssUrl" /><input type="button" id="readRss" value="Read" />
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- FEEDS HOLDER  -->
		<div id="holderRss">
			<!-- FEED ARTICLES  -->
			<div id="feeds">
			</div>
			<div style="clear: both;"></div>
		</div>
		<!-- LOAD GOOGLE API WITH API_KEY -->
		<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAcJULXlQZKRnCmFNDvhEJLRQ8dpi745S_OKzIu4KHh4AztPE6ThRVsQCU5CuX2qzSiDAUHtEII3qB_A"></script>
		<!-- LOAD JQUERY -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
		<!-- LOAD blockUI jQuery plugin -->
		<script type="text/javascript" src="js/jquery.blockUI.js"></script>
		<!-- LOAD RSS_PARSER SCRIPT -->
		<script type="text/javascript" src="js/rss_parser.js"></script>
	</body>
</html>