<?php
	/**
	* Protection
	*
	* This string of code will prevent hacks from accessing the file directly.
	*/
	defined('ABSPATH') or die("Cannot access pages directly.");

	function PIE_ImageRotate($img, $rotation) 
	{
		$width = imagesx($img);
		$height = imagesy($img);
		$newimg = null;
					
		if ($rotation == 90 || $rotation == -270)
			$newimg = @imagecreatetruecolor($height, $width);
		else if ($rotation == 180 || $rotation == -180)
			$newimg = @imagecreatetruecolor($width, $height);
		else if ($rotation == 270 || $rotation == -90)
			$newimg = @imagecreatetruecolor($height, $width);
		else if ($rotation == 0 || $rotation == 360)
			return $img;
					
		if($newimg) 
		{
			for($i = 0;$i < $width ; $i++) 
			{
				for($j = 0;$j < $height ; $j++) 
				{
					$reference = imagecolorat($img,$i,$j);
					
					if ($rotation == 90 || $rotation == -270)
					{
						if(!@imagesetpixel($newimg, $j, ($width - 1) - $i, $reference))
							return false;
					}
					else if ($rotation == 180 || $rotation == -180)
					{
						if(!@imagesetpixel($newimg, ($width -1) - $i, ($height - 1) - $j, $reference))
							return false;	
					}
					else if ($rotation == 270 || $rotation == -90)
					{
						if(!@imagesetpixel($newimg, ($height - 1) - $j, $i, $reference))
							return false;
					}
				}
			} 
			
			return $newimg;
		}
		
		return false;
	}

	function PIE_Grayscale($img) 
	{
		$width = imagesx($img);
		$height = imagesy($img);

		for($i = 0;$i < $width ; $i++) 
		{
			for($j = 0;$j < $height ; $j++) 
			{
				$rgb = imagecolorat($img,$i,$j);
				
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;

				$gray = round(($r + $g + $b)/3, 0);
				$c = imagecolorallocate($img, $gray, $gray, $gray);
				
				@imagesetpixel($img, $i, $j, $c);
			}
		} 
	}
	
	function PIE_Brightness($img, $light) 
	{
		//-255 = min brightness, 0 = no change, +255 = max brightness
		if ($light != 0)
		{
			$width = imagesx($img);
			$height = imagesy($img);
	
			for($i = 0;$i < $width ; $i++) 
			{
				for($j = 0;$j < $height ; $j++) 
				{
					$rgb = imagecolorat($img,$i,$j);
					
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
	
					$r += $light;
					$g += $light;
					$b += $light;
					
					if ($r > 255)
						$r = 255;
					else if ($r < 0)
						$r = 0;
						
					if ($g > 255)
						$g = 255;
					else if ($g < 0)
						$g = 0;
						
					if ($b > 255)
						$b = 255;
					else if ($b < 0)
						$b = 0;
						
					$c = imagecolorallocate($img, $r, $g, $b);
					
					@imagesetpixel($img, $i, $j, $c);
				}
			}
		} 
	}
	
	//This function is the php version of gdImageContrast.
	function PIE_Contrast($img, $contrast)
	{
		if ($contrast != 0)
		{
			$width = imagesx($img);
			$height = imagesy($img);
			
			$contrast = (double)(100.0-$contrast)/100.0;
			$contrast *= $contrast;
		
			for ($y=0; $y < $height; $y++) 
			{
				for ($x=0; $x < $width; $x++) 
				{
					$rgb = imagecolorat($img,$x,$y);
					$r = ($rgb >> 16) & 0xFF;
					$g = ($rgb >> 8) & 0xFF;
					$b = $rgb & 0xFF;
					
					$rf = (double)$r/255.0;
					$rf = $rf-0.5;
					$rf = $rf*$contrast;
					$rf = $rf+0.5;
					$rf = $rf*255.0;
		
					$bf = (double)$b/255.0;
					$bf = $bf-0.5;
					$bf = $bf*$contrast;
					$bf = $bf+0.5;
					$bf = $bf*255.0;
		
					$gf = (double)$g/255.0;
					$gf = $gf-0.5;
					$gf = $gf*$contrast;
					$gf = $gf+0.5;
					$gf = $gf*255.0;
		
					$rf = ($rf > 255.0) ? 255.0 : (($rf < 0.0) ? 0.0 : $rf);
					$gf = ($gf > 255.0) ? 255.0 : (($gf < 0.0) ? 0.0 : $gf);
					$bf = ($bf > 255.0) ? 255.0 : (($bf < 0.0) ? 0.0 : $bf);
		
					$new_pxl = imagecolorallocate($img, (int)$rf, (int)$gf, (int)$bf);
					
					if (!$new_pxl || $new_pxl == -1)
						$new_pxl = imagecolorsforindex($img, imagecolorclosest($img, (int)$rf, (int)$gf, (int)$bf)); 
						
					@imagesetpixel($img, $x, $y, $new_pxl);
				}
			}
		}
	}
	
	function PIE_Echo($text)
	{
		echo $text;
	}
	
	function PIE_CutSentence($sentence,$maxLength)
	{      
		if (strlen($sentence) > $maxLength)
		{
			$sentence = substr($sentence, 0, $maxLength);
			$whiteSpacePos = strripos($sentence, " ");
			
			if ($whiteSpacePos === false) 
				$sentence = substr($sentence, 0, $maxLength);
			else if ($whiteSpacePos > 0) 
				$sentence = substr($sentence, 0, $whiteSpacePos);
		
			$sentence .= '...';
		}
		
		return $sentence;		
	}
	
	function PIE_DeleteOldImages($srcdir) 
	{
		if($curdir = opendir($srcdir)) 
		{
			while($file = readdir($curdir)) 
			{
				if($file != '.' && $file != '..') 
				{
					$srcfile = $srcdir.$file;
					$srcfileLower = strtolower($srcfile);
					
					if (stripos($srcfile, ".svn") === false)
					{
						if(is_file($srcfile)) 
						{
							$doDelete = true;

							if (substr_count($srcfile, "sample.jpg") > 0 || 
								substr_count($srcfile, "sample.png") > 0  || 
								(substr_count($srcfileLower, ".jpg") == 0 && substr_count($srcfileLower, ".jpeg") == 0 && substr_count($srcfileLower, ".gif") == 0 && substr_count($srcfileLower, ".png") == 0 && substr_count($srcfileLower, ".txt") == 0))
								$doDelete = false; 
				            	
							if ($doDelete && ($modification_time = filemtime($srcfile)))
							{
								$deleteTime = mktime(0, 0, 0, date("n"), date("j")-2, date("Y"));
								
								if ($modification_time < $deleteTime)
								{
									//Image is old and will be deleted. Or else the server space will be filled up with not needed images.
									//echo "<h2>DELETE $srcfile".date("F d Y H:i:s.", fileatime($srcfile))."</h2>";
									unlink($srcfile);
								}
							}
						}
					}
				}
			}
			closedir($curdir);
		}
	}
		
?>