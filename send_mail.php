<?php
require_once "./includes/validate.php";
require_once "./includes/functions.php";
$errorFlag=0;
$successFlag=0;

if($_SERVER["REQUEST_METHOD"] == "POST") //check if form was submitted
	{
	  	$name = strip_tags(trim($_POST["name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);
		
		  // Check that data was sent to the mailer.
        if ( empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }

				
		$to      = 'vaidyatavish24@gmail.com';
		$subject = "New contact from $name";
		$message = wordwrap($message, 70, "\r\n");
		$message = "From : ".$name. "\nEmail: ".$email."\n\n".$message;
		$headers = 	'Reply-To: '.$email. "\r\n" .
					'X-Mailer: PHP/' . phpversion();
		$ret = mail($to, $subject, $message, $headers);
		if($ret == true)
		{
			$successFlag=1;
			
			http_response_code(200);
			echo "Thank You! Your message has been sent.";
		}
		else{
			http_response_code(500);
            echo "Oops! Something went wrong and we couldn't send your message.";
		}		
	}
else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }
?>