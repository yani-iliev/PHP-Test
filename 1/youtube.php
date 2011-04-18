#!/usr/bin/php -q
<?php 
/**
 * This application accepts a YouTube URL as input, and extracts the title, description, and the URL of the FLV stream
 * to an XML file or to stdout
 * 
 * I wasn't sure for the exact meaning of - The output should be in the form of an XML -
 * There are a couple possibilities:
 * 1. Output to a file.
 * 2. Output to stdout.
 * 3. Output to the stdout with proper header. - header( "Content-type: text/xml" );
 *
 * Since the application is a PHP CLI program it shouldn't be outputting anything to the browser.
 * Instead, it can output only to a file and stdout.
 * That is the reason why my application only supports the first two options.
 *
 * Youtube does NOT give the FLV location via its API. I had to look for another way to find the flv 
 * location. That is where the YouTube_VideoInfo class by Ifthikhan Nazeem becomes handy.
 *
 * The video title and description are extracted from the YouTube API by using SimpleXML to parse 
 * the data.
 * @version 0.1
 * @author Yanislav Iliev <yani.iliev@cspath.com>
 */

// This class is used to get the flv location of a youtube video
require_once( "VideoInfo.php" );

// Outputs usage information
function usage(){
	echo $_SERVER["argv"][0]." url [filename]";
	echo PHP_EOL;
	exit( 0 );
}

/**
 * Saves the passed video meta information and video flv location
 * in an xml with the following structure:
 * <videos>
 *	 <video>
 *		 <title>$title</title>
 *		 <description>$desc</description>
 *		 <flv>$flv</flv>
 *	 </video>
 * </videos>
 * @param string $title Title of YouTube video
 * @param string $desc Description of YouTube video
 * @param string $flv Location of Youtube video's flv
 * @param boolean|string $filename Filename to store the xml structure
 * when this is set to false, the xml is outputted to the stdout 
 */
function saveToXML( $title, $desc, $flv, $filename = FALSE ){
	
	// Create a new Dom document and set the format flag to true
	$doc = new DOMDocument();
	$doc->formatOutput = true;
	
	// Create videos element and append it to the dom
	$videos = $doc->createElement( "videos" );
	$doc->appendChild( $videos );
	
	// Create video element
	$video = $doc->createElement( "video" );
	
	// Create title element, insert the title text inside it, and 
	// append title element to video parent element
	$tlt = $doc->createElement( "title" );
	$tlt->appendChild( $doc->createTextNode( $title ) );
	$video->appendChild( $tlt );
	
	// Create description element, insert the description text inside it, and 
	// append description element to video parent element
	$description = $doc->createElement( "description" );
	$description->appendChild( $doc->createTextNode( $desc ) );
	$video->appendChild( $description );
	
	// Create flv element, insert the flv location inside it, and 
	// append flv element to video parent element
	$fl = $doc->createElement( "flv" );
	$fl->appendChild( $doc->createTextNode( $flv ) );
	$video->appendChild( $fl );
	
	// Append video element to videos parent element
	$videos->appendChild( $video );
	
	// If filename is set write the xml content to the file
	// otherwise output it to the stdout
	if( $filename === FALSE ) {
		echo $doc->saveXML();
		echo PHP_EOL;
	} else {
		// check to see if the specified file is writable
		// and that we can open it
		if( ! is_writable($filename) || ! $handle = fopen( $filename, 'a' ) ) { 
			echo "Cannot open ($filename). Create the file and try again."; 
			echo PHP_EOL;
			exit( 0 ); 
		}
		if( fwrite( $handle, $doc->saveXML() ) === FALSE ) { 
			echo "Cannot write to ($filename)"; 
			echo PHP_EOL;
			exit( 0 ); 
		}
		echo "Saved contents to xml file: ".$filename;
		echo PHP_EOL;
	}
}

// Check to see if the minimum arguments are provided
// if the number of arguments is not enough, display the usage
// information by calling usage() function
if($_SERVER["argc"] < 2){
	usage();
}


// extract the id of the YouTube video from the given URL
parse_str( parse_url( $_SERVER["argv"][1], PHP_URL_QUERY ) );

// check to see if the video is extracted properly
// otherwise the url was invalid and the script fails
// with an error message
if( ! isset( $v ) || empty( $v ) ){
	echo "Invalid Youtube URL. Example of a correct URL: http://www.youtube.com/watch?v=aemXgP-2xyg";
	echo PHP_EOL;
	exit(0);
}

// Location of YouTube API that allows us to retrieve information about the video
$feedURL = "http://gdata.youtube.com/feeds/api/videos/$v?v=2";

// The YouTube API is parsed with the help of SimpleXML
$sxml = simplexml_load_file( $feedURL );
$media = $sxml->children( 'http://search.yahoo.com/mrss/' );

// extract the title
$title = $sxml->title;

// extract the description
$description = $media->group->description;

// using the mighty YouTube_VideoInfo class to get 
// the location of the FLV video
try{
	$info = new YouTube_VideoInfo( $v );
	$flv = $info->request()->getFlvUrl();
} catch(Exception $e){
	// An exception was thrown. Show the message to the user
	// and exit the application.
	echo $e->getMessage();
	echo PHP_EOL;
	exit( 0 );
}

// Is there a filename provided?
if($_SERVER["argc"] == 3){
	// Yes, save the content to the file
	saveToXML( $title, $description, $flv, $_SERVER["argv"][2] );
} else {
	// No, output the content to stdout
	saveToXML( $title, $description, $flv );
}
