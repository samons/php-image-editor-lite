<?php
	/**
	* Protection
	*
	* This string of code will prevent hacks from accessing the file directly.
	*/
	defined('ABSPATH') or die("Cannot access pages directly.");

	class PHPImageEditor 
	{
		var $srcEdit = "";
		var $srcOriginal = "";
		var $srcPng = "";
		var $srcWorkWith = "";
		var $resourceWorkWith = false;
		var $mimeType = "";
		var $actionSaveAndClose = "save";
		var $actionRotateLeft = "rotate:90";
		var $actionRotateRight = "rotate:-90";
		var $actionGrayscale = "grayscale";
		var $actionContrast = "contrast";
		var $actionBrightness = "brightness";
		var $actionUndo = "undo";
		var $actionUpdate = "update";
		var $actionRotateIsSelected = false;
		var $actionRotate = "";
		var $actionSeparatorSign = "#";
		var $fieldNameKeepProportions = "keepproportions";
		var $errorMessages = array();
		var $formName = "phpimageeditor";
		var $inputWidth = -1;
		var $inputHeight = -1;
		var $inputCropLeft = 0;
		var $inputCropRight = 0;
		var $inputCropTop = 0;
		var $inputCropBottom = 0;
		var $inputKeepProportions = true;
		var $inputCropKeepProportions = false;
		var $inputCropKeepProportionsRatio = 1;
		var $inputPanel = PIE_START_PANEL;
		var $inputLanguage = PIE_DEFAULT_LANGUAGE;
		var $inputContrast = 0;
		var $inputBrightness = 0;
		var $inputContrastLast = 0;
		var $inputBrightnessLast = 0;
		var $inputGrayscale = false;
		var	$httpImageKey = "imagesrc";
		var $actions = "";
		var $isPostBack = false;
		var $isAjaxPost = false;
		var $finalWidth = -1;
		var $finalHeight = -1;
		var $widthKeepProportions = -1;
		var $heightKeepProportions = -1;
		var $userId = "";
		var $fonts = array();
		var $doSave = false;
		
		var $contrastMax = 100;
		var $brightnessMax = 255;
		
		function PHPImageEditor()
		{
			if (version_compare(phpversion(), PIE_PHP_VERSION_MINIMUM, "<"))
			{
				$this->errorMessages[] = phpversion()." ".__('is too old php version. Minimum is:', 'phpimageeditorlite')." ".PIE_PHP_VERSION_MINIMUM;
				return;
			}
			
			$this->isPostBack = isset($_POST["actiontype"]);
			
			$srcEdit = "";
			
			if ($_GET[$this->httpImageKey] != NULL)
			{
				$srcEdit = strip_tags($_GET[$this->httpImageKey]);
			}
				
			if ($srcEdit == "")
			{
				$this->errorMessages[] = __('No image has been provided.', 'phpimageeditorlite');
				return;
			}
			
			$this->srcEdit = urldecode($srcEdit);
			
			if (isset($_POST["userid"]))
				$this->userId = $_POST["userid"];
			else
				$this->userId = "_".time()."_".str_replace(".", "_", $_SERVER['REMOTE_ADDR']);
			
			$this->SetSrcOriginal();
			$this->SetSrcPng();
			$this->SetSrcWorkWith();
			
			if (!file_exists($this->srcEdit))
			{
				$this->errorMessages[] = __('The image doesn´t exist.', 'phpimageeditorlite');
				return;
			}
			
			$info = getimagesize($this->srcEdit);
			
			if (!$info)
			{
				$this->errorMessages[] = __('Image must be of type jpeg, png or gif.', 'phpimageeditorlite');
				return;
			}
			
			$this->mimeType = image_type_to_mime_type($info[2]);	
			
			if ($this->mimeType == image_type_to_mime_type(IMAGETYPE_JPEG) || $this->mimeType == image_type_to_mime_type(IMAGETYPE_GIF) || $this->mimeType == image_type_to_mime_type(IMAGETYPE_PNG))
			{	
				if (!$this->isPostBack)
					$this->SaveOriginal();
				
				$this->resourceWorkWith = $this->CreateImage($this->srcOriginal);
				$this->SavePng();
				copy($this->srcPng, $this->srcWorkWith);
				
				$this->resourceWorkWith = $this->CreateImage($this->srcPng);
			}
			else
			{
				$this->errorMessages[] = __('Image must be of type jpeg, png or gif.', 'phpimageeditorlite');
				return;
			}
			
			$this->finalWidth = $this->GetWidth();
			$this->finalHeight = $this->GetHeight();
			$this->widthKeepProportions = $this->GetWidth();
			$this->heightKeepProportions = $this->GetHeight();
			
			if ($this->isPostBack)
			{
				$this->actionRotateIsSelected = (strip_tags($_POST["rotate"]) != "-1");
				$this->actionRotate = strip_tags($_POST["rotate"]);
				$this->actions = strip_tags($_POST["actions"]);
				$this->isAjaxPost = (strip_tags($_POST["isajaxpost"]) == "true");
				$this->inputWidth = (int)strip_tags($_POST["width"]);
				$this->inputHeight = (int)strip_tags($_POST["height"]);
				$this->inputCropLeft = (int)strip_tags($_POST["cropleft"]); 
				$this->inputCropRight = (int)strip_tags($_POST["cropright"]); 
				$this->inputCropTop = (int)strip_tags($_POST["croptop"]); 
				$this->inputCropBottom = (int)strip_tags($_POST["cropbottom"]); 
				$this->inputPanel = (int)strip_tags($_POST["panel"]); 
				$this->inputLanguage = strip_tags($_POST["language"]); 
				$this->inputKeepProportions = (strip_tags($_POST["keepproportionsval"]) == "1"); 
				$this->inputCropKeepProportions = (strip_tags($_POST["cropkeepproportionsval"]) == "1");
				$this->inputCropKeepProportionsRatio = (float)strip_tags($_POST["cropkeepproportionsratio"]);
				$this->inputGrayscale = (strip_tags($_POST["grayscaleval"]) == "1"); 
				$this->inputBrightness = (int)strip_tags($_POST["brightness"]); 
				$this->inputContrast = (int)strip_tags($_POST["contrast"]); 
				$this->inputBrightnessLast = (int)strip_tags($_POST["brightnesslast"]); 
				$this->inputContrastLast = (int)strip_tags($_POST["contrastlast"]);

				$this->Action(strip_tags($_POST["actiontype"]));
			}
		}
		
		function SetSrcOriginal()
		{
			$arr = explode("/", $this->srcEdit);
			$this->srcOriginal = PIE_IMAGE_ORIGINAL_PATH.$this->AddUserIdToImageSrc($arr[count($arr)-1]);
		}
		
		function SetSrcWorkWith()
		{
			$arr = explode("/", $this->srcEdit);
			$srcWorkWith = PIE_IMAGE_WORK_WITH_PATH.$this->AddUserIdToImageSrc($arr[count($arr)-1]);
			$srcWorkWith = substr($srcWorkWith, 0, strripos($srcWorkWith, ".")).".png";
			$this->srcWorkWith = $srcWorkWith;
		}
		
		function SetSrcPng()
		{
			$arr = explode("/", $this->srcEdit);
			$srcPng = PIE_IMAGE_PNG_PATH.$this->AddUserIdToImageSrc($arr[count($arr)-1]);
			$srcPng = substr($srcPng, 0, strripos($srcPng, ".")).".png";
			$this->srcPng = $srcPng;
		}
		
		function SaveOriginal()
		{
			copy($this->srcEdit, $this->srcOriginal);
			
			//Resize to fit in max width/height.
			$imageTmp = $this->CreateImage($this->srcOriginal);
			$finalWidth = $this->GetWidthFromImage($imageTmp);
			$finalHeight = $this->GetHeightFromImage($imageTmp);
			
			$doSave = false;
			
			if ($finalWidth > PIE_IMAGE_MAX_WIDTH)
			{
				$widthProp = PIE_IMAGE_MAX_WIDTH/$finalWidth;
				$finalWidth = PIE_IMAGE_MAX_WIDTH;
				$finalHeight = round($finalHeight*$widthProp);
				$doSave = true;
			}
			
			if ($finalHeight > PIE_IMAGE_MAX_HEIGHT)
			{
				$heightProp = PIE_IMAGE_MAX_HEIGHT/$finalHeight;
				$finalHeight = PIE_IMAGE_MAX_HEIGHT;
				$finalWidth = round($finalWidth*$heightProp);
				$doSave = true;
			}
			
			if ($doSave)
			{	
				$imageTmp = $this->ActionResize($finalWidth, $finalHeight, $imageTmp);
				$this->SaveImage($imageTmp, $this->srcOriginal);
			}
		}
		
		function SavePng()
		{
			$this->SaveImage($this->resourceWorkWith, $this->srcPng, image_type_to_mime_type(IMAGETYPE_PNG));
		}
		
		function ErrorHasOccurred()
		{
			return (count($this->errorMessages) > 0);
		}
		
		function GetWidthFinal()
		{
			return $this->finalWidth;
		}
		
		function GetHeightFinal()
		{
			return $this->finalHeight;
		}
		
		function GetWidth()
		{
			return $this->GetWidthFromImage($this->resourceWorkWith);
		}
		
		function GetWidthLast()
		{
			if ($this->isPostBack)
				return (int)$_POST["widthlast"];
		
			return $this->GetWidth();
		}
		
		function GetHeight()
		{
			return $this->GetHeightFromImage($this->resourceWorkWith);
		}
		
		function GetHeightLast()
		{
			if ($this->isPostBack)
				return (int)$_POST["heightlast"];
		
			return $this->GetWidth();
		}
		
		function GetWidthFromImage($image)
		{
			return imagesx($image);
		}
		
		function GetHeightFromImage($image)
		{
			return imagesy($image);
		}
		
		function Action($actionType)
		{
			$this->doSave = false;
			
			if ($actionType == $this->actionUndo)
			{
				$this->ActionUndo();
				$this->doSave = true;
			}
			
			if ($actionType == $this->actionUpdate || $actionType == $this->actionSaveAndClose)
			{
				if ($this->inputWidth != $this->GetWidthLast() || $this->inputHeight != $this->GetHeightLast())
					$this->actions .= $this->GetActionSeparator()."resize:".$this->inputWidth.",".$this->inputHeight;
					
				if ($this->inputCropLeft != 0 || $this->inputCropRight != 0 || $this->inputCropTop != 0 || $this->inputCropBottom != 0)
					$this->actions .= $this->GetActionSeparator()."crop:".$this->inputCropLeft.",".$this->inputCropRight.",".$this->inputCropTop.",".$this->inputCropBottom;
					
				$this->doSave = true;
			}
			
			if ($actionType == $this->actionUpdate && $this->inputGrayscale)
			{
				if (strpos($this->actions, $this->actionGrayscale) === false)
				{
					$this->actions .= $this->GetActionSeparator().$this->actionGrayscale.":0";
					$this->doSave = true;
				}
			}
			else if ($actionType == $this->actionUpdate && !$this->inputGrayscale)
			{
				if (!(strpos($this->actions, $this->actionGrayscale) === false))
				{
					$this->actions = str_replace($this->actionGrayscale.":0".$this->GetActionSeparator(), "", $this->actions);
					$this->actions = str_replace($this->GetActionSeparator().$this->actionGrayscale.":0", "", $this->actions);
					$this->actions = str_replace($this->actionGrayscale.":0", "", $this->actions);
					$this->doSave = true;
				}
			}
			
			if ($this->inputContrast != $this->inputContrastLast)
			{
				$this->actions .= $this->GetActionSeparator().$this->actionContrast.":".$this->inputContrast;
				$this->doSave = true;
			}
			
			if ($this->inputBrightness != $this->inputBrightnessLast)
			{
				$this->actions .= $this->GetActionSeparator().$this->actionBrightness.":".$this->inputBrightness;
				$this->doSave = true;
			}
			
			if ($this->actionRotateIsSelected)
			{
				if ($this->actionRotate == $this->actionRotateLeft)
				{
					$this->actions .= $this->GetActionSeparator().$this->actionRotateLeft;
					$this->doSave = true;
				}
				else if ($this->actionRotate == $this->actionRotateRight)
				{
					$this->actions .= $this->GetActionSeparator().$this->actionRotateRight;
					$this->doSave = true;
				}
			}
			
			$finalContrast = 0;
			$finalBrightness = 0;
			$finalContrastFound = false;
			$finalBrightnessFound = false;
			$finalGrayscale = false;
			
			if ($this->doSave && $this->actions != "")
			{
				$allActions = explode($this->actionSeparatorSign, $this->actions);
				
				$finalRotate = 0;
				$finalCropLeft = 0;
				$finalCropRight = 0;
				$finalCropTop = 0;
				$finalCropBottom = 0;
				
				$doSwitch = false;
				
				foreach ($allActions as $loopAction)
				{
					$actionDetail = explode(":", $loopAction);
					$actionValues = explode(",", $actionDetail[1]);
					
					if ($actionDetail[0] == "resize")
					{
						$this->finalWidth = (int)$actionValues[0];
						$this->finalHeight = (int)$actionValues[1];
					}
					else if ($actionDetail[0] == "crop")
					{
						$actionValueLeft = (int)$actionValues[0];
						$actionValueRight = (int)$actionValues[1];
						$actionValueTop = (int)$actionValues[2];
						$actionValueBottom = (int)$actionValues[3];
						
						$widthProp = 1;
						$heightProp = 1;
						
						if ($doSwitch)
						{
							$widthProp = (($this->GetHeight()-($finalCropTop + $finalCropBottom)) / $this->finalWidth);
							$heightProp = (($this->GetWidth()-($finalCropLeft + $finalCropRight)) / $this->finalHeight);
						}
						else 
						{
							$widthProp = (($this->GetWidth()-($finalCropLeft + $finalCropRight)) / $this->finalWidth);
							$heightProp = (($this->GetHeight()-($finalCropTop + $finalCropBottom)) / $this->finalHeight);
						}
						
						$cropLeft = $actionValueLeft * $widthProp;
						$cropRight = $actionValueRight * $widthProp;
						$cropTop = $actionValueTop * $heightProp;
						$cropBottom = $actionValueBottom * $heightProp;
						
						$cropValues = array();
						$cropValues[] = $cropRight;
						$cropValues[] = $cropBottom;
						$cropValues[] = $cropLeft;
						$cropValues[] = $cropTop;
						
						if ($finalRotate != 0)
							$cropValues = $this->RotateArray(($finalRotate/-90), $cropValues);
						
						$finalCropRight += $cropValues[0];
						$finalCropBottom += $cropValues[1];
						$finalCropLeft += $cropValues[2];
						$finalCropTop += $cropValues[3];
	
						$this->finalWidth -= ($actionValueLeft + $actionValueRight);
						$this->finalHeight -= ($actionValueTop + $actionValueBottom);
					}
					else if ($actionDetail[0] == $this->actionGrayscale && $this->inputGrayscale)
					{
						$finalGrayscale = true;
					}
					else if ($actionDetail[0] == "contrast")
					{
						$finalContrastFound = true;
						$finalContrast = $actionValues[0];
					}
					else if ($actionDetail[0] == "brightness")
					{
						$finalBrightnessFound = true;
						$finalBrightness = $actionValues[0];
					}
					else if ($actionDetail[0] == "rotate")
					{
						$finalRotate += (int)$actionValues[0];
						$finalWidthTmp = $this->finalWidth;
						$this->finalWidth = $this->finalHeight;
						$this->finalHeight = $finalWidthTmp;						
					}
						
					if ($finalRotate == -360 || $finalRotate == 360)
						$finalRotate = 0;
						
					$doSwitch = ($finalRotate != 0 && ($finalRotate == 90 || $finalRotate == 270 || $finalRotate == -90 || $finalRotate == -270));
				}
				
				//1. All effects.
				if ($finalGrayscale)
					$this->ActionGrayscale();
							
				if ($finalBrightnessFound)
					$this->ActionBrightness($finalBrightness);
					
				if ($finalContrastFound)
					$this->ActionContrast($finalContrast*-1);
					
				//2. Do cropping.
				$finalCropLeft = round($finalCropLeft);
				$finalCropRight = round($finalCropRight);
				$finalCropTop = round($finalCropTop);
				$finalCropBottom = round($finalCropBottom);
				if ($finalCropLeft != 0 || $finalCropRight != 0 || $finalCropTop != 0 || $finalCropBottom != 0)
					$this->ActionCrop($finalCropLeft, $finalCropRight, $finalCropTop, $finalCropBottom);	
				
				//3. Rotate
				if ($finalRotate != 0)
					$this->ActionRotate($finalRotate);
				
				//Calculate keep proportions values.
				if (round($this->finalWidth/$this->finalHeight,1) == round($this->GetWidth()/$this->GetHeight(),1))
				{
					//It seems to have the same proportions as the original. Use the original proportions value.
					$this->widthKeepProportions = $this->GetWidth();
					$this->heightKeepProportions = $this->GetHeight();
				}
				else 
				{
					//The proportions has been changed. Use the new width and height instead.
					$this->widthKeepProportions = $this->finalWidth;
					$this->heightKeepProportions = $this->finalHeight;
				}
					
				//4. Resize
				if ($this->finalWidth > 0 && $this->finalHeight > 0)
					$this->resourceWorkWith = $this->ActionResize($this->finalWidth, $this->finalHeight, $this->resourceWorkWith);

				$this->SaveImage($this->resourceWorkWith, $this->srcWorkWith, image_type_to_mime_type(IMAGETYPE_PNG));
			}
	
			$this->inputBrightness = $finalBrightness;
			$this->inputContrast = $finalContrast;
			$this->inputGrayscale = $finalGrayscale;
			
			if ($actionType == $this->actionSaveAndClose)
			{
				$this->SaveImage($this->resourceWorkWith, $this->srcEdit, $this->mimeType);

				$uploads = wp_upload_dir();
				$upload_base = str_replace(site_url().'/', '', $uploads['baseurl']);
				
				if ($post_meta = phpimageeditorlite_attachment_post_meta(str_replace($upload_base.'/', '', $this->srcEdit)))
				{
					//Generate thumbnails.
					require_once(ABSPATH.'/wp-admin/includes/image.php');
					
					//Only update width and height when the original image is replaced.
					$post_meta->metadata['width'] = $this->GetWidth();
					$post_meta->metadata['height'] = $this->GetHeight();

					$image_sizes = array();
			 	
			 		// make thumbnails and other intermediate sizes
					global $_wp_additional_image_sizes;
					
					foreach (get_intermediate_image_sizes() as $s ) {
						$image_sizes[$s] = array('width' => '', 'height' => '', 'crop' => FALSE);
						if (isset($_wp_additional_image_sizes[$s]['width']))
							$image_sizes[$s]['width'] = intval($_wp_additional_image_sizes[$s]['width']); // For theme-added sizes
						else
							$image_sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
						if (isset($_wp_additional_image_sizes[$s]['height']))
							$image_sizes[$s]['height'] = intval($_wp_additional_image_sizes[$s]['height']); // For theme-added sizes
						else
							$image_sizes[$s]['height'] = get_option("{$s}_size_h"); // For default sizes set in options
						if (isset($_wp_additional_image_sizes[$s]['crop']))
							$image_sizes[$s]['crop'] = intval($_wp_additional_image_sizes[$s]['crop']); // For theme-added sizes
						else
							$image_sizes[$s]['crop'] = get_option("{$s}_crop"); // For default sizes set in options
					}
			
					$image_sizes = apply_filters('intermediate_image_sizes_advanced', $image_sizes);
					
					foreach ($image_sizes as $size_name => $size_attributes) {

						$thumb = image_resize($this->srcEdit, $size_attributes['width'], $size_attributes['height'], $size_attributes['crop']);
						
						if (is_wp_error($thumb)) {
							//Thumb is probably larger the original and couldn't be generated.
							//print $key.' '.$thumb->get_error_message().'<br/>';
							unset($post_meta->metadata['sizes'][$size_name]);
						}
						else {	
							$arr = explode("/", $thumb);
							$filename = $arr[count($arr)-1];
							$tmp = substr($filename, 0, strripos($filename, "."));
							$tmp = substr($tmp, strripos($tmp, "-")+1);
							$thumb_sizes = explode('x', $tmp);
							
							$post_meta->metadata['sizes'][$size_name]['width'] = $thumb_sizes[0];
							$post_meta->metadata['sizes'][$size_name]['height'] = $thumb_sizes[1];
							$post_meta->metadata['sizes'][$size_name]['file'] = $filename;
						}
					}

					update_post_meta($post_meta->post_id, '_wp_attachment_metadata', $post_meta->metadata);	
				}
				else {
					$this->errorMessages[] = __('Couldn´t find image information. Maybe it´s been deleted during image editing?', 'phpimageeditorlite');
					return;
				}

				unlink($this->srcOriginal);
				unlink($this->srcPng);
				unlink($this->srcWorkWith);
				
				PIE_DeleteOldImages(PIE_IMAGE_ORIGINAL_PATH);
				PIE_DeleteOldImages(PIE_IMAGE_PNG_PATH);
				PIE_DeleteOldImages(PIE_IMAGE_WORK_WITH_PATH);
				
				PIE_Echo('<script language="javascript" type="text/javascript">');
				PIE_Echo('jQuery(document).ready(function($) {');
				PIE_Echo("if (window.parent.parent.location != window.parent.location) {");
				PIE_Echo("$('#TB_window', window.parent.parent.document).width(670);");
				PIE_Echo("$('#TB_window', window.parent.parent.document).height(659);");
				PIE_Echo("$('#TB_window', window.parent.parent.document).css('margin-left', '-335px');");
				PIE_Echo("$('#TB_iframeContent', window.parent.parent.document).width(670);");
				PIE_Echo("$('#TB_iframeContent', window.parent.parent.document).height(629);");
				PIE_Echo('}');
				PIE_Echo('parent.tb_remove();');
				PIE_Echo('parent.location.reload();');
				PIE_Echo('});');
				PIE_Echo('</script>');
			}
		}
		
		function ActionResize($width, $height, $image)
		{
			$newImage = @imagecreatetruecolor($width, $height);
			imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $this->GetWidthFromImage($image), $this->GetHeightFromImage($image));
			return $newImage;
		}
		
		function ActionCrop($cropLeft, $cropRight, $cropTop, $cropBottom)
		{
			$cropWidth = $this->GetWidth() - $cropLeft - $cropRight;
			$cropHeight = $this->GetHeight() - $cropTop - $cropBottom;
			
			$newImageCropped = @imagecreatetruecolor($cropWidth, $cropHeight);
			imagecopy($newImageCropped, $this->resourceWorkWith, 0, 0, $cropLeft, $cropTop, $cropWidth, $cropHeight); 
		
			$this->resourceWorkWith = $newImageCropped;
		}
		
		function ActionUndo()
		{
			$separatorPos = strrpos($this->actions, $this->actionSeparatorSign);
			if (!($separatorPos === false)) 
			{
				$this->actions = substr($this->actions, 0, $separatorPos);
			}
			else
			{
				$this->actions = "";
			}
		}
		
		function CreateImage($srcEdit)
		{
			$info = getimagesize($srcEdit);
	
			if (!$info)
				return NULL;
					
			$mimeType = image_type_to_mime_type($info[2]);	
			
			if ($mimeType == image_type_to_mime_type(IMAGETYPE_JPEG))
			{	
				return imagecreatefromjpeg($srcEdit);
			}
			else if ($mimeType == image_type_to_mime_type(IMAGETYPE_GIF))
			{	
				return imagecreatefromgif($srcEdit);
			}
			else if ($mimeType == image_type_to_mime_type(IMAGETYPE_PNG))
			{
				return imagecreatefrompng($srcEdit);
			}
			
			return NULL;
		}
		
		function ActionRotate($Degrees)
		{
			if (function_exists('imagerotate'))
				$this->resourceWorkWith = imagerotate($this->resourceWorkWith, $Degrees, 0);
			else
				$this->resourceWorkWith = PIE_ImageRotate($this->resourceWorkWith, $Degrees);
		} 
		
		function ActionGrayscale()
		{
			if (function_exists('imagefilter'))
				imagefilter($this->resourceWorkWith, IMG_FILTER_GRAYSCALE);
			else			
				PIE_Grayscale($this->resourceWorkWith);
		}
	
		function ActionContrast($contrast)
		{
			//-100 = max contrast, 0 = no change, +100 = min contrast
			if (function_exists('imagefilter'))
				imagefilter($this->resourceWorkWith, IMG_FILTER_CONTRAST, $contrast);
			else
				PIE_Contrast($this->resourceWorkWith, $contrast);
		}
		
		function ActionBrightness($light)
		{
			//-255 = min brightness, 0 = no change, +255 = max brightness
			if (function_exists('imagefilter'))
				imagefilter($this->resourceWorkWith, IMG_FILTER_BRIGHTNESS, $light);
			else
				PIE_Brightness($this->resourceWorkWith, $light);
		}
		
		function GetErrorMessages()
		{
			if (count($this->errorMessages))
			{		
				PIE_Echo('<div class="error">');
				PIE_Echo('<ul>');
				
				foreach ($this->errorMessages as $errorMessage)
					PIE_Echo ('<li>'.$errorMessage.'</li>');
				
				PIE_Echo("</ul>");
				PIE_Echo('</div>');
			}
		}
		
		function GetActions()
		{
			PIE_Echo($this->actions);
		}
		
		function GetActionSeparator()
		{
			if ($this->actions != "")
				return $this->actionSeparatorSign;
				
			return "";
		}
		
		function SaveImage($image, $toSrc, $mimeType = -1)
		{
			if ($mimeType == -1)
				$mimeType = $this->mimeType;
			
			if ($mimeType == image_type_to_mime_type(IMAGETYPE_JPEG))
			{	
				imagejpeg($image, $toSrc);		
			}
			else if ($mimeType == image_type_to_mime_type(IMAGETYPE_GIF))
			{	
				imagegif($image, $toSrc);		
			}
			else if ($mimeType == image_type_to_mime_type(IMAGETYPE_PNG))
			{
				imagepng($image, $toSrc);		
			}
		}
		
		function CleanUp()
		{
			if ($this->resourceWorkWith)
				imagedestroy($this->resourceWorkWith);	
		}
		
		function RotateArray($numberOfSteps, $arr)
		{
			$finalArray = array();
			
			//-3 to 3
			$finalArray[] = $arr[$this->NumberOfStepsCalculator($numberOfSteps + 0)];
			$finalArray[] = $arr[$this->NumberOfStepsCalculator($numberOfSteps + 1)];
			$finalArray[] = $arr[$this->NumberOfStepsCalculator($numberOfSteps + 2)];
			$finalArray[] = $arr[$this->NumberOfStepsCalculator($numberOfSteps + 3)];
			
			return $finalArray;
		}
		
		function NumberOfStepsCalculator($sum)
		{
			$maxIndex = 3;
			if ($sum > $maxIndex)
				return ($sum-$maxIndex)-1; 
			else if ($sum < 0)
			{
				return ($sum+$maxIndex)+1; 
			}
				
			return $sum;
		}
		
		function AddUserIdToImageSrc($imageSrc)
		{
			return str_replace(".", $this->userId.".", $imageSrc);
		}
		
		function GetFormAction()
		{
			return site_url()."/?pie-lite=1&".$this->httpImageKey."=".$this->srcEdit;
		}
		
		function GetWidthKeepProportions()
		{
			return $this->widthKeepProportions;
		}
		
		function GetHeightKeepProportions()
		{
			return $this->heightKeepProportions;
		}
	}
?>