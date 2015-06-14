<?php

if(isset($_GET['call']))
{
	$option = $_GET['call'];
	
	switch($option)
	{
		case 1:
		if(isset($_GET['str']))
			{
				$name = $_GET['str'];
				$var = checkUserExists($name);
				echo $var;
			}
						
			break;
		
		default:
			break;
	}
}

function checkUsername($obj) //also to check the team name
{

    $name_isvalid=preg_match("^[a-zA-Z0-9_]{2,40}$^",$obj);
    if($name_isvalid)
    {
        return true;
    }
    else
    {
        return false;
    }
}



function checkFullname($obj) //also to check md5 hash of the event names from get field of "register for event" 
{
	preg_replace("/\t\n\r\s/", " ", $obj);

	$fullname_isvalid = preg_match("^[a-zA-Z0-9,.!@& ]{3,90}$^",$obj);
	if($fullname_isvalid)
	{
		return true;
	}
	else
	{
		return false;
	}
}



function checkEmailId($obj)
{
    $email_isvalid = preg_match("^[a-zA-Z0-9_\.]+@[a-zA-Z0-9-]+\.[a-z]{2,4}$^", $obj);
    if($email_isvalid)
    {
        return true;
    }
    else
    {
        return false;
    }
}


function checkCaptcha($obj)     //check captcha string
{	
	$captcha_isvalid = preg_match("^[a-zA-Z0-9]{5,10}$^", $obj);
	if($captcha_isvalid)
	{
		if($obj == $_SESSION['security'])
			return true;
	}
	else
	{
		return false;
	}
}

function captcha()   //to generate captcha
{
	
	// Set views layout to empty
        

        // Set the image width and height
        $width = 258;
        $height = 48; 

		$code = substr(sha1(uniqid()),rand(1,15),5);
		$code2 = substr(md5($code),rand(1,28),rand(3,9));
		
        // Create the image resource
        $image = ImageCreate($width, $height);  

        // Set the font
        $font = imageloadfont("images/anonymous.gdf");
        
        // define colors used in image
        $color[1] = $white = ImageColorAllocate($image, 223, 41, 100);
        
        $color[2] = $green = ImageColorAllocate($image, 112, 191, 12);
		$color[4] = $grey = ImageColorAllocate($image, 150, 150, 150);
        $color[3] = $black = ImageColorAllocate($image, 45, 34, 66);
	    $color[0] = $blue = ImageColorAllocate($image, 51, 102, 153);
        $line_color = ImageColorAllocate($image, 0, 64, 255);
		$string = ImageColorAllocate($image, 255, 240, 245);
        //Make the background blue
        ImageFill($image, 0, 0, $color[rand(0,4)]); 
        
        //Generate random(ish) position for image text
        $text_x = mt_rand(-3, 30);
        $text_y = mt_rand(-9, 10);
        
        //Add randomly generated string in white to the image
        $security = "$code".rand(1,2);
		
		$_SESSION['security'] = $security;
        ImageString($image, $font, $text_x, $text_y, $security, $string); 

        //Throw in some lines to make it a little bit harder for any bots to break
        $s = ($width*$height)/500;
		
        for($i=0; $i < $s; $i++) {
            imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $line_color);
        } // end for

        // borders for captcha; syntax is (image, Xstart, Ystart, Xend, Yend, color)
        
        imageline($image, 0,        0,          $width,     0,          $blue); //topl-topr
        imageline($image, 0,        $height-1,  $width,     $height-1,  $blue); //btml-btmr
        imageline($image, 0,        0,          0,          $height,    $blue); //topl-btml
        imageline($image, $width-1, 0,          $width-1,   $height,    $blue); //topr-btmr
        
       $sessionid = session_id();
	   $image_name = "captcha".$sessionid.".png";
	   imagepng($image,$image_name);
}

//--login page functions--//
function checkUserRecord($username,$password)      //to check user supplied login credentials
{
  $username = mysql_prep($username);
  $password = mysql_prep($password);
  
  $result = getUsernameRecord($username);
  
  if(!empty($result))//if user exists
	{
		if(sha1($password)==$result['password']) //match the hashed passwords
		{
			return $result;
		}
		else
		{
			return -1; //incorrect password, user exits
		}
		
	}
	else if(!$result)
	{
		return false;
	}

}

function checkEventHash($obj) //to check md5 hash of the event names from get field of "register for event" 
{
	preg_replace("/\t\n\r\s/", "", $obj);

	$hash_isvalid = preg_match("^[a-zA-Z0-9]{5,70}$^",$obj);
	if($hash_isvalid)
	{
		return true;
	}
	else
	{
		return false;
	}
}
