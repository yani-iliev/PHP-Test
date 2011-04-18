/**
 * This script handles form submission and displays errors
 * on the page by changing DOM
 * @version 0.1
 * @author Yani Iliev <yani.iliev@cspath.com>
 */
// When DOM is loaded, execute login.init()
$(function(){
	login.init();
});

var login = {
	submitButton: '#login',								// id of submit button element
	formHolder: '#holder',								// id of form holder element - used to block user activity
	username: '#username',								// id of username input element
	password: '#password',								// id of password input element
	remember: '#remember',								// id of remember me checkbox element
	errorHolder: '#login_error',					// id of the element that holds the errors
	
	// init function - think of it as a constructor
	init: function() {
		// calls assignEvents
		login.assignEvents();
	},
	// assigns click event handler for the submit button
	assignEvents: function() {
		if( $(login.submitButton).length > 0 ) {
			$(login.submitButton).click(function() {
				// upon click of the button
				// block the form
				login.blockForm();
				// clear errors if errors exist
				login.clearErrors();
				// do error checking and submit the form
				login.submitForm();
			})
		}
	},
	// blocks the user from interacting with the form
	// displays a small loading image
	blockForm: function() {
		if( $( login.formHolder ).length > 0 ) {
			var message = '<img src="images/loader.gif" width="43" height="11" alt="loading" />';
			$( login.formHolder ).block( { 
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
	// unblocks the form and lets the user to 
	// access the form functionality
	unBlockForm: function() {
		$( login.formHolder ).unblock();
	},
	
	// gathers user input
	// does error checking on the input
	// calles the api to log the user in
	submitForm: function() {
		// get the username
		var username = $( login.username ).val();
		// get the password
		var password = $( login.password ).val();
		// get the remember me checkbox
		var remember = $( login.remember ).is( ':checked' );
		// initialize an empty array that will hold the errors
		var errors = [];
		
		// check if username is inputted
		if( username.length == 0 ) {
			errors.push( "Username cannot be empty." );
		}
		// check if password is inputted
		if( password.length == 0 ) {
			errors.push( "Password cannot be empty." );
		}
		
		// if there are errors
		// notify the user and halt the execution
		if( errors.length > 0 ) {
			login.showErrors(errors);
		} else {
			// there are no errors
			// call the api and pass the user input
			$.ajax({
			  type: 'POST',
			  url: 'api.php',
			  data: { username: username, password: password, remember: remember, login: true },
			  success: function(data){
					// Check to see if the user is logged in
					if(data.error_flag == "1"){
						// there are errors, notify the user
						login.showErrors(data.errors);
					} else {
						// login successful! redirect the user to the member
						// page
						window.location.href = 'member.php';
					}
				},
			  dataType: 'json'
			});
		}
	},
	// appends error messages to the error holder
	showErrors: function(errors) {
		if( errors instanceof Array ){
			$.each( errors, function( i, v ) {
				$( login.errorHolder ).append( "<strong>Error: </strong>" + v + "<br />" );
			} );
		} else {
			$( login.errorHolder ).append( "<strong>Error: </strong>" + errors + "<br />" );
		}
		$( login.errorHolder ).show();
		login.unBlockForm();
	},
	// empties the error holder
	clearErrors: function() {
		$( login.errorHolder ).empty();
		$( login.errorHolder ).hide();
	}
};