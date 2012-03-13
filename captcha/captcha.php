<?php
        $getPostVar = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS);
        if(empty($getPostVar) || !isset($getPostVar)){
            exit("Don't panic and don't mess around the system. Invalid parameter.");
        }
        $prepend= "captext_";
        $textFileNameSize = -9;
        $myFile = '../captchacodes/'.$prepend.substr($getPostVar,$textFileNameSize).".txt";
        $key ="";
        $captchaImage = imagecreatefrompng( "images/captcha.png" );
        if(file_exists($myFile)){
            $key = trim(file_get_contents($myFile));
            $textColor = imagecolorallocate( $captchaImage, 255, 255, 153 );
            $imageInfo = getimagesize( "images/captcha.png" );
            $linesToDraw = 30;
            for( $i = 0; $i < $linesToDraw; $i++ )  {
                $xStart = mt_rand( 0, $imageInfo[ 0 ] );
                $xEnd = mt_rand( 0, $imageInfo[ 0 ] );
                $lineColor = imagecolorallocate( $captchaImage, $i, (204+$i), (101+$i) );
                imageline( $captchaImage, $xStart, 0, $xEnd, $imageInfo[1], $lineColor );
            }
            imagettftext( $captchaImage, 20, 0, 35, 35, $textColor, "fonts/VeraBd.ttf", $key );
        }
        header ( "Content-type: image/png" );
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Fri, 16 Mar 2012 05:00:00 GMT");
        header("Pragma: no-cache");
        imagepng( $captchaImage );
?>
