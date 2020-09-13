function showSuccess(message) {
	// no parameters removes all classes
	$('#message').removeClass();
	$('#message').addClass('alert text-center alert-success');
	$('#message').html("<strong>" + message + "</strong>");
	$('#message').addClass('animated fadeInDown');
}

function showError(message) {
	$('#message').removeClass();
	$('#message').addClass('alert text-center alert-danger');
	$('#message').html("<strong>" + message + "</strong>");
	$('#message').addClass('animated shake');
}

function showWarning(message) {
	$('#message').removeClass();
	$('#message').addClass('alert text-center alert-warning');
	$('#message').html("<strong>" + message + "</strong>");
	$('#message').addClass('animated fadeInDown');
}

function performResult(JSONData) {
	var isJSON = true;

	try {
		var jsonObject = jQuery.parseJSON(JSONData);
	} catch (err) {
		console.log(JSONData);
		var msg = "Invalid data recieved.";
		showError(msg);
		return;
	}
	
	if( 'error' in jsonObject) {
		var msg = jsonObject['error'];
		showError(msg);
	}
	else if ('warning' in jsonObject) {
		var msg = jsonObject['warning'];
		showWarning(msg);
	}
	else if ('success' in jsonObject) {
		var msg = jsonObject['success'];
		showSuccess(msg);
		setTimeout(function(){
			jsonObject = null;
			document.location = "./dashboard";
		}, 1000);
	}
	else if('step_success' in jsonObject) {
		var msg = jsonObject['step_success'];
		showSuccess(msg);
		console.log(jsonObject['OTP']);

		setTimeout(function(){
			$('#OTP').removeClass('hidden');
			$('#OTP').addClass("animated fadeIn");
		}, 700);

		// fadeout OTP field after 1 minute
		setTimeout(function(){
			$('#OTP').removeClass('fadeIn');
			$('#OTP').addClass("animated fadeOut");
		}, 60000);

		// hide OTP field after 1 minute 1 second
		setTimeout(function(){
			$('#OTP').removeClass("animated fadeIn fadeOut");
			$('#OTP').addClass('hidden');
		}, 61000);
	}
	else {
		console.log(JSONData);
		var msg = "An unknown error occured";
		showError(msg);
	}

	jsonObject = null;
}

function hideMessage() {

	// hide message only if it has 'alert' class
	if($('#message').hasClass("alert")) {
		// fadeout the message
		$('#message').addClass("animated fadeOutUp");

		// wait for animation to complete, then hide the class
		setTimeout(function(){
			$('#message').removeClass();
			$('#message').addClass("hidden");
		}, 800);
	}
}

function AjaxLogin(post_data) {
	$.ajax({
		type : "POST",
		timeout: 5000,
		url : "./login.php",
		data: post_data,
		cache: false,
		datatype: "json",
		success:function (html) {
			// handle things
			performResult(html);
			// enable signing in
			$('#sign_in').prop('disabled', false);
			$('#sign_in').html("Sign in");
		},
		beforeSend : function() {
			// disable signing in
			$('#sign_in').prop('disabled', true);
			$('#sign_in').html("Signing in...");
		},
		error: function(jqXHR,textStatus,errorThrown ){
			if(textStatus === 'timeout')
				alert("Request timed out.<br><hr><div class=\"well well-sm\"><h5>textStatus : " + textStatus + "<br>errorThrown : " + errorThrown + "</h5></div>");
			else if(textStatus === 'error')
				alert("An error occured.<br><hr><div class=\"well well-sm\"><h5>textStatus : " + textStatus + "<br>errorThrown : " + errorThrown + "</h5></div>");
			else if(textStatus === 'abort')
				alert("Process was aborted.<br><hr><div class=\"well well-sm\"><h5>textStatus : " + textStatus + "<br>errorThrown : " + errorThrown + "</h5></div>");
			else if(textStatus === 'parsererror')
				alert("Data could not be parsed.<br><hr><div class=\"well well-sm\"><h5>textStatus : " + textStatus + "<br>errorThrown : " + errorThrown + "</h5></div>");
			else
				alert("An unknown error occured while performing the request.");

			status = false;
		}
	}); // end of AJAX
}