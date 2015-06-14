<?php
require_once "./includes/validate.php";
require_once "./includes/functions.php";
$errorFlag=0;
$successFlag=0;
$errors = array();

if(isset($_POST['submit']) && $_POST['submit']=="Submit") //check if form was submitted
	{

		//check user's username
		if(isset($_POST['username']) && $_POST['username']!="")
		{
        //check user input for valid characters
			if(checkUsername($_POST['username']))
			{
				$username = $_POST['username'];
			}

			else
			{
				$errors['username'] = "Alphabets only !"; //set error message
				$username = $_POST['username'];
				$errorFlag=1;
			}

		}
		else
		{
            $errors['username'] = "We would like to know your name !";
            $errorFlag=1;
		}
		//username check ends here

	

		//fullname check(HERE it is for subject of the email
		if(isset($_POST['fullname']) && $_POST['fullname']!="" )
		{
		//check user input for valid characters
			if(checkFullname($_POST['fullname']))
			{
				$input['fullname'] = $_POST['fullname'];
				$fullname = $_POST['fullname'];
			}

			else
			{
				$errors['fullname'] = "Only Alphanumerics and !,@,&,., allowed !"; //set error message
				$fullname = $_POST['fullname'];
				$errorFlag=1;
			}
		}
		else
		{
			$errors['fullname'] = "Please mention a subject !";
            $errorFlag=1;
		}
		//full name check ends here

		//email id check
		if(isset($_POST['email']) && $_POST['email']!="" )
		{
		//check user input for valid characters
			if(strlen($_POST['email']) > 100 )
			{
				$errors['email'] = "Email Id too long !";
				$email = $_POST['email'];
			}
			else if(checkEmailId($_POST['email']))
			{
				
					$input['email'] = $_POST['email'];
					$email = $_POST['email'];
				
			}

			else
			{
				$errors['email'] = "Please enter a valid Email Id !"; //set error message
				$email = $_POST['email'];
				$errorFlag=1;
			}
		}
		else
		{
			$errors['email'] = "Please enter you email. We will get back to you !";
            $errorFlag=1;
		}
		//email id check ends here
		
		
		//message should not be empty
		if(isset($_POST['message']) && $_POST['message']!="" && strlen($_POST['message']) > 2 && strlen($_POST['message']) < 6000 )
		{
			$message = $_POST['message'];
				
			
		}
		else{
			$message = $_POST['message'];
			$errors['message'] = "<br/>Forgot to write the message? ";
			$errorFlag=1;
		
		}

		
		//captcha check
		if(isset($_POST['captcha_text']) && $_POST['captcha_text']!="")
		{
			if(checkCaptcha($_POST['captcha_text']))
			{
				//do nothing i.e. user is filling the form
			}
			else
			{
				$errors['captcha'] = "Codes does not match. Enter again !";
				$errorFlag=1;
			}
		}
		else
		{
			$errors['captcha'] = "Please enter the above code !";
			$errorFlag=1;
		}

		
		if($errorFlag==0 && -1) //no errors, send an email
		{			
			$to      = 'vaidyatavish24@gmail.com';
			$subject = $fullname;
			$message = wordwrap($message, 70, "\r\n");
			$message = "From : ".$fullname. "\nEmail: ".$email."\n\n".$message;
			$headers = 	'Reply-To: '.$email. "\r\n" .
						'X-Mailer: PHP/' . phpversion();
			$ret = mail($to, $subject, $message, $headers);
			if($ret == true)
			{
				echo "SUCCESS";
				$successFlag=1;
				session_destroy();
				redirect_to("contactus.php?msgSent=1");
			}
			else{
				echo "OOPS";
				$errorFlag=1;
			}
			
			/*
			$query = "insert into users (user_id, username, password, fullname, gender, roll_no, email, college_id, phone_no, reg_time)
				values (NULL, '" . $input['username'] . "','" .sha1($input['password']). "','" .$input['fullname']. "'," . $input['gender']. ",'".$input['roll_no']. "','" .$input['email']. "','" .$input['college']. "'," .$input['phone_no']. ",NULL)";

			$dump = "insert into dump_users (dump_id, username, email, password)
					values (NULL, '". $input['username'] ."','" .$input['email']."','" .$input['password'] . "')";
			
			$result = addUser($query);
			$dump_query = addUser($dump);
			
			if($result)
			{
				
				$successFlag=1;
				session_destroy();
				mysql_close();
				redirect_to("login.php");
			}
			*/

		}
	}
?>