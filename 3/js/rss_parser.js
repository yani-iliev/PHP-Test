/**
 * A simple RSS URL reader
 * 
 * Uses Google Feed API (http://code.google.com/apis/feed/v1/devguide.html) 
 * to parse RSS URL and display the content stored in the RSS
 * 
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */

// This lets jQuery to be used with
// other libraries
jQuery.noConflict();

// load google feed api
// http://code.google.com/apis/feed/v1/devguide.html
google.load("feeds", "1");

// this function is executed when the dom is loaded
function OnLoad() {
	rss.init();
}

var rss = {
	submitButton: '#readRss',							// id of submit button element
	feeds: '#feeds',											// id of the element that holds the parsed feeds
	formHolder: '#holderUrl',							// id of the element to block
	feedurl: '#rssUrl',										// id of the element where the user inputs the rss feed url
	errorHolder: '#errorHolder',					// id of the element that displays errors
	holderRss: '#holderRss',
	
	// init function - think of it as a constructor
	init: function() {
		// calls assignEvents
		rss.assignEvents();
	},
	// assigns click event handler for the submit button
	assignEvents: function() {
		if( jQuery( rss.submitButton ).length > 0 ) {
			jQuery( rss.submitButton ).click( function() {
				var feed = jQuery( rss.feedurl ).val();
				if( feed.length == 0 ) {
					rss.displayError("Please supply an RSS URL.");
				} else {
					// upon click of the button
					// block the form
					rss.blockForm();
					// clear errors that maybe left
					rss.clearError();
					// clear previous parsed feeds
					rss.clearFeeds();
					// parse the current feed
					rss.parseFeed( feed );
				}
				// unblock the form
				rss.unBlockForm();
			})
		}
	},
	// shows errors to the user
	displayError: function(m){
		jQuery( rss.errorHolder ).append( "<strong>Error: </strong>" + m + "<br />" );
		jQuery( rss.errorHolder ).show();
	},
	// clear and hides the error holder
	clearError: function() {
		jQuery( rss.errorHolder ).empty();
		jQuery( rss.errorHolder ).hide();
	},
	// clears the content of feeds holder
	clearFeeds: function() {
		jQuery( rss.feeds ).empty();
	},
	// parses rss feed and appends
	// feed's content to the feed holder
	parseFeed: function( fd ) {
		// display the feed holder
		jQuery( rss.holderRss ).show();
		
		// read the feed using google feed api
		// http://code.google.com/apis/feed/v1/devguide.html
		var feed = new google.feeds.Feed( fd );
		
		// parse the feed
		feed.load( function( result ) {
      if( ! result.error ) {
				// No errors while parsing the feed.
				// Go over each feed item
        for (var i = 0; i < result.feed.entries.length; i++) {
					// get the feed item
          var entry = result.feed.entries[i];
					// get item's title
					var title = entry.title;
					// get item's description|short text|summary
					var content = entry.content;
					// get item's author
					var author = entry.author;
					// check to see if the author is set
					if( author.length > 0 ){
						author = "by " + author;
					}
					// img URL holder
					var img = 0;
					// img WIDTH holder
					var imgW = 0;
					// img HEIGHT holder
					var imgH = 0;
					// check to see if media is defined
					if( entry.mediaGroups !== undefined && entry.mediaGroups.length > 0 ) {
						// media is defined so get the image
						// url and image size
						img = entry.mediaGroups[0];
						if( img.contents !== undefined && img.contents.length > 0 ) {
							img = img.contents[0];
							imgW = img.width;
							imgH = img.height;
							img = img.url;
						} else {
							img = 0;
						}
					}
					// create the feed's holder
					var html =	'<div id="feed' + i + '" class="feed">';
					// append title
					html		+=		'<h3>' + title + '</h3>';
					// append author if it is set
					if( author != 0 ){
						html +=			'<h6>' + author + '</h6>';
					}
					// append image if it is set
					if( img != 0 ){
						w = 850 - imgW;
						html		+=		'<p><img src="' + img + '" width="' + imgW + '" height="' + imgH + '" /><span style="position: absolute; margin-left: 10px; width: ' + w +'px">' + content + '</span></p>';
					} else {
						html		+=		'<p>' + content + '</p>';
					}
					// end the feed holder
					html		+=	'</div>';
					// append the feed item to the feeds holder
					jQuery( rss.feeds ).append(html);
					// display the feed item with slideDown animation
					jQuery( "#feed" + i ).slideDown('slow');
        }
      } else {
				// there is an error while parsing the feed.
				// display the error to the user
				rss.displayError(result.error.message);
				// hide the feeds holder
				jQuery( rss.holderRss ).hide();
				// clear any junk data that was parsed from the feed URL
				jQuery( rss.feeds ).empty();
			}
    });
	},
	// blocks the user from interacting with the form
	// displays a small loading image
	blockForm: function() {
		if( jQuery( rss.formHolder ).length > 0 ) {
			var message = '<img src="images/loader.gif" width="43" height="11" alt="loading" />';
			jQuery( rss.formHolder ).block( { 
				message: message,
				css: {
					'-moz-box-shadow': '0px 0px 5px #000',
				  '-webkit-box-shadow': '0px 0px 5px #000',
				  'box-shadow': '0px 0px 5px #000',
					border: 'none',
					backgroundColor: '#F1F1F1',
					'border-radius': '10px',
					'-webkit-border-radius': '5px',
					'-moz-border-radius': '5px',
					'cursor': 'default',
				}
			} );
		}
	},
	// unblocks the element and lets the user to 
	// access the its functionality
	unBlockForm: function() {
		jQuery( rss.formHolder ).unblock();
	}
	
};

// When DOM is loaded, execute OnLoad
google.setOnLoadCallback(OnLoad);
