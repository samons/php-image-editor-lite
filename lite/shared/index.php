<?php  	
	
	/**
	* Protection
	*
	* This string of code will prevent hacks from accessing the file directly.
	*/
	defined('ABSPATH') or die("Cannot access pages directly.");

	ini_set('default_charset', 'utf-8');
	header("Cache-Control: no-store"); 
	header('content-type: text/html; charset: utf-8');
	include plugin_dir_path(__FILE__).'includes/constants.php';
	include plugin_dir_path(__FILE__).'config.php';
	include plugin_dir_path(__FILE__).'includes/functions.php';
	include plugin_dir_path(__FILE__).'classes/phpimageeditor.php';
	global $objPHPImageEditor;
	$objPHPImageEditor = new PHPImageEditor();
?>
<?php if (!$objPHPImageEditor->isAjaxPost) { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>PHP Image Editor</title>
	    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery-1.7.1.min.js', __FILE__ ); ?>"></script>
	    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery.jcrop.js', __FILE__ ); ?>"></script>
        <script type="text/javascript" src="<?php print plugins_url('javascript/jquery.numeric.js', __FILE__ ); ?>"></script>
	    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery-ui-1.8.16.custom.min.js', __FILE__ ); ?>"></script>
        
        <script type="text/javascript" src="<?php print plugins_url('javascript/phpimageeditor.js', __FILE__ ); ?>"></script>
	    
	    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/style.css', __FILE__ ); ?>"/>
	    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/ui.resizable.css', __FILE__ ); ?>"/>
	    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/ui.slider.css', __FILE__ ); ?>"/>
	    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/jquery.jcrop.css', __FILE__ ); ?>"/>
	    
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
        <script type="text/javascript">
	        var ImageMaxWidth = <?php PIE_Echo(PIE_IMAGE_MAX_WIDTH); ?>;
	        var ImageMaxHeight = <?php PIE_Echo(PIE_IMAGE_MAX_HEIGHT); ?>;
	        var ImageWidth = <?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>;
	        var ImageHeight = <?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>;
	        var TextIsRequired = "<?php _e('is required', 'php-image-editor-lite'); ?>";
	        var TextMustBeNumeric = "<?php _e('must be numeric', 'php-image-editor-lite'); ?>";
	        var TextWidth = "<?php _e('Width', 'php-image-editor-lite'); ?>";
	        var TextHeight = "<?php _e('Height', 'php-image-editor-lite'); ?>";
	        var TextNotNegative = "<?php _e('must be a positive number', 'php-image-editor-lite'); ?>";
	        var TextNotInRange = "<?php _e('is not in valid range', 'php-image-editor-lite'); ?>";
	        var TextCantBeLargerThen = "<?php _e('can´t be larger then', 'php-image-editor-lite'); ?>";
	        var TextAnUnexpectedError = "<?php _e('An unexpected error has occured, please try again...', 'php-image-editor-lite'); ?>";
	        var Brightness = <?php PIE_Echo($objPHPImageEditor->inputBrightness); ?>;
	        var Contrast = <?php PIE_Echo($objPHPImageEditor->inputContrast); ?>;
	        var BrightnessMax = <?php PIE_Echo($objPHPImageEditor->brightnessMax); ?>;
	        var ContrastMax = <?php PIE_Echo($objPHPImageEditor->contrastMax); ?>;
            var FormAction = "<?php PIE_Echo($objPHPImageEditor->GetFormAction()); ?>";
            var FormId = "<?php PIE_Echo($objPHPImageEditor->formName); ?>";
            var ActionUpdate = "<?php PIE_Echo($objPHPImageEditor->actionUpdate); ?>";
            var ActionUndo = "<?php PIE_Echo($objPHPImageEditor->actionUndo); ?>";
            var ActionSaveAndClose = "<?php PIE_Echo($objPHPImageEditor->actionSaveAndClose); ?>";
            var ActionRotateLeft = "<?php PIE_Echo($objPHPImageEditor->actionRotateLeft); ?>";
            var ActionRotateRight = "<?php PIE_Echo($objPHPImageEditor->actionRotateRight); ?>";
            var ActionSaveAndClose = "<?php PIE_Echo($objPHPImageEditor->actionSaveAndClose); ?>";
            var MenuResize = "<?php PIE_Echo(PIE_MENU_RESIZE); ?>";
            var MenuRotate = "<?php PIE_Echo(PIE_MENU_ROTATE); ?>";
            var MenuCrop = "<?php PIE_Echo(PIE_MENU_CROP); ?>";
            var MenuEffects = "<?php PIE_Echo(PIE_MENU_EFFECTS); ?>";
            var AjaxPostTimeoutMs = <?php PIE_Echo(PIE_AJAX_POST_TIMEOUT_MS); ?>; 
		</script>
	</head>
	<body>
		<div id="phpImageEditor">
<?php } ?>

			<form id="<?php PIE_Echo($objPHPImageEditor->formName); ?>" name="<?php PIE_Echo($objPHPImageEditor->formName); ?>" method="post" action="<?php PIE_Echo($objPHPImageEditor->GetFormAction()); ?>">
				<?php if (!$objPHPImageEditor->ErrorHasOccurred()) { ?>
					 
					<div class="tabs">
					
						<div id="menu">
							<?php if (PIE_RESIZE_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPHPImageEditor->inputPanel == PIE_MENU_RESIZE ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_RESIZE); ?>">
									<h1><?php _e('Resize Image', 'php-image-editor-lite'); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_ROTATE_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPHPImageEditor->inputPanel == PIE_MENU_ROTATE ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_ROTATE); ?>">
									<h1><?php _e('Rotate Image', 'php-image-editor-lite'); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_CROP_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPHPImageEditor->inputPanel == PIE_MENU_CROP ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_CROP); ?>">
									<h1><?php _e('Crop Image', 'php-image-editor-lite'); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_EFFECTS_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPHPImageEditor->inputPanel == PIE_MENU_EFFECTS ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_EFFECTS); ?>">
									<h1><?php _e('Image Effects', 'php-image-editor-lite'); ?></h1>
								</div>
							<?php } ?>
						</div>
							
						<div id="actionContainer">
						
							<div id="panel_<?php PIE_Echo(PIE_MENU_RESIZE); ?>" class="panel">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>	
											<div class="field widthAndHeight">
												<div class="col-1">
													<label for="width"><?php _e('Width', 'php-image-editor-lite'); ?></label>
													<input class="input-number" type="text" name="width" id="width" value="<?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>"/>
													<input type="hidden" name="widthoriginal" id="widthoriginal" value="<?php PIE_Echo($objPHPImageEditor->GetWidth()); ?>"/>
												</div>
												<div class="col-2">
													<label for="height"><?php _e('Height', 'php-image-editor-lite'); ?></label>
													<input class="input-number" type="text" name="height" id="height" value="<?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>"/>
													<input type="hidden" name="heightoriginal" id="heightoriginal" value="<?php PIE_Echo($objPHPImageEditor->GetHeight()); ?>"/>
												</div>
											</div>
											<div class="field">
												<input class="checkbox" type="checkbox" name="<?php PIE_Echo($objPHPImageEditor->fieldNameKeepProportions); ?>" id="<?php PIE_Echo($objPHPImageEditor->fieldNameKeepProportions); ?>" <?php PIE_Echo($objPHPImageEditor->inputKeepProportions ? 'checked="checked"' : ''); ?>/>
												<input type="hidden" name="keepproportionsval" id="keepproportionsval" value="<?php PIE_Echo($objPHPImageEditor->inputKeepProportions ? '1' : '0'); ?>"/>
												<label for="<?php PIE_Echo($objPHPImageEditor->fieldNameKeepProportions); ?>" class="checkbox"><?php _e('Constrain Proportions', 'php-image-editor-lite'); ?></label>
											</div>
										</td>
										<td>
											<div class="help" id="resizehelp">
												<div class="help-header" id="resizehelpheader"><?php _e('Instructions', 'php-image-editor-lite'); ?></div>
												<div class="help-content" id="resizehelpcontent"><?php _e('Update Width and Height fields.<br/>Or drag and drop in the right or bottom side of the image.', 'php-image-editor-lite'); ?></div>
											</div>
										</td>
									</tr>
								</table>
							</div>
		
							<div id="panel_<?php PIE_Echo(PIE_MENU_ROTATE); ?>" class="panel">
								<div class="field">
									<input id="btnRotateLeft" type="button" value="<?php _e('Left 90 Degrees', 'php-image-editor-lite'); ?>"/>
									<input id="btnRotateRight" type="button" value="<?php _e('Right 90 Degrees', 'php-image-editor-lite'); ?>"/>
									<input type="hidden" name="rotate" id="rotate" value="-1"/>
								</div>
							</div>
		
							<div id="panel_<?php PIE_Echo(PIE_MENU_CROP); ?>" class="panel">
								<div class="field">
									<input class="input-number" type="hidden" name="croptop" id="croptop" value="0"/>
									<input class="input-number" type="hidden" name="cropleft" id="cropleft" value="0"/>
									<input class="input-number" type="hidden" name="cropright" id="cropright" value="0"/>
									<input class="input-number" type="hidden" name="cropbottom" id="cropbottom" value="0"/>
									<div class="help" id="crophelp">
										<div class="help-header" id="crophelpheader"><?php _e('Instructions', 'php-image-editor-lite'); ?></div>
										<div class="help-content" id="crophelpcontent"><?php _e('Drag and drop to create a crop area on the image.', 'php-image-editor-lite'); ?></div>
									</div>
								</div>
								<div class="field crop-settings">
									<div class="crop-top">
										<?php _e('Crop Width', 'php-image-editor-lite'); ?>: <span id="cropwidth">0</span>
										<?php _e('Crop Height', 'php-image-editor-lite'); ?>: <span id="cropheight">0</span>
									</div>
									<input id="cropkeepproportions" class="checkbox" type="checkbox" name="cropkeepproportions" <?php PIE_Echo($objPHPImageEditor->inputCropKeepProportions ? 'checked="checked"' : ''); ?>/>
									<label class="checkbox" for="cropkeepproportions"><?php _e('Constrain Crop Proportions', 'php-image-editor-lite'); ?></label>
									<input id="cropkeepproportionsval" type="hidden" name="cropkeepproportionsval" value="<?php PIE_Echo($objPHPImageEditor->inputCropKeepProportions ? '1' : '0'); ?>"/>									
									<input id="cropkeepproportionsratio" type="hidden" name="cropkeepproportionsratio" value="<?php PIE_Echo($objPHPImageEditor->inputCropKeepProportionsRatio); ?>"/>									
								</div>
							</div>
							<div id="panel_<?php PIE_Echo(PIE_MENU_EFFECTS); ?>" class="panel">
								<div class="field">
									<label for="brightness"><?php _e('Brightness', 'php-image-editor-lite'); ?></label>
									<div id="brightness_slider_track"></div>
								</div>
								<input type="hidden" name="brightness" id="brightness" value="<?php PIE_Echo($objPHPImageEditor->inputBrightness); ?>"/>
								<div class="field">
									<label for="contrast"><?php _e('Contrast', 'php-image-editor-lite'); ?></label>
									<div id="contrast_slider_track"></div>
								</div>
								<input type="hidden" name="contrast" id="contrast" value="<?php PIE_Echo($objPHPImageEditor->inputContrast); ?>"/>
								<div class="field">
									<input class="checkbox" type="checkbox" name="<?php PIE_Echo($objPHPImageEditor->actionGrayscale); ?>" id="<?php PIE_Echo($objPHPImageEditor->actionGrayscale); ?>" <?php PIE_Echo($objPHPImageEditor->inputGrayscale ? 'checked="checked"' : ''); ?>/>
									<label for="<?php PIE_Echo($objPHPImageEditor->actionGrayscale); ?>" class="checkbox"><?php _e('Grayscale', 'php-image-editor-lite'); ?></label>
									<input type="hidden" name="grayscaleval" id="grayscaleval" value="<?php PIE_Echo($objPHPImageEditor->inputGrayscale ? '1' : '0'); ?>"/>
								</div>
							</div>

							<div id="loading" style="display: none;"><?php _e('Loading', 'php-image-editor-lite'); ?>...<div id="loading_bar" style="width: 0px;"></div></div>
		
						</div>
						
						<div class="main-actions">
							<input type="button" id="btnupdate" name="btnupdate" value="<?php _e('Update', 'php-image-editor-lite'); ?>"/>
							<input type="button" id="btnsave" name="btnsave" value="<?php _e('Save and Close', 'php-image-editor-lite'); ?>"/>
							<input type="button" <?php PIE_Echo($objPHPImageEditor->actions == "" ? 'disabled="disabled"' : ''); ?> id="btnundo" name="btnundo" value="<?php _e('Undo', 'php-image-editor-lite'); ?>"/>
						</div>
		
					</div>
					<input type="hidden" name="actiontype" id="actiontype" value="<?php PIE_Echo($objPHPImageEditor->actionUpdate); ?>"/>
					<input type="hidden" name="panel" id="panel" value="<?php PIE_Echo($objPHPImageEditor->inputPanel); ?>"/>
					<input type="hidden" name="language" id="language" value="<?php PIE_Echo($objPHPImageEditor->inputLanguage); ?>"/>
					<textarea name="actions" id="actions"><?php $objPHPImageEditor->GetActions(); ?></textarea>
					<input type="hidden" name="widthlast" id="widthlast" value="<?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>"/>
					<input type="hidden" name="heightlast" id="heightlast" value="<?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>"/>
					<input type="hidden" name="widthlastbeforeresize" id="widthlastbeforeresize" value="<?php PIE_Echo($objPHPImageEditor->GetWidthKeepProportions()); ?>"/>
					<input type="hidden" name="heightlastbeforeresize" id="heightlastbeforeresize" value="<?php PIE_Echo($objPHPImageEditor->GetHeightKeepProportions()); ?>"/>
					<input type="hidden" name="userid" id="userid" value="<?php PIE_Echo($objPHPImageEditor->userId); ?>"/>
					<input type="hidden" name="contrastlast" id="contrastlast" value="<?php PIE_Echo($objPHPImageEditor->inputContrast); ?>"/>
					<input type="hidden" name="brightnesslast" id="brightnesslast" value="<?php PIE_Echo($objPHPImageEditor->inputBrightness); ?>"/>
					<input type="hidden" name="isajaxpost" id="isajaxpost" value="false"/>
				<?php } ?>
			</form>
			<?php $objPHPImageEditor->GetErrorMessages(); ?>
			<div id="divJsErrors" class="error" style="display: none;">
				<ul id="ulJsErrors" style="display: none;"><li></li></ul>
			</div>
			<div><img src="<?php print plugins_url('lite/shared/images/empty.gif', __FILE__ ); ?>" alt=""/></div>
			<?php if (!$objPHPImageEditor->ErrorHasOccurred()) { ?>
				<div id="editimage">
					<img id="image" style="position: absolute; left: 0px; top: 0px; width: <?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>px;" alt="" src="<?php PIE_Echo(PIE_PLUGINS_URL_FROM_INDEX.'/editimagesworkwith/'.str_replace(PIE_IMAGE_WORK_WITH_PATH, '', $objPHPImageEditor->srcWorkWith)); ?>?timestamp=<?php PIE_Echo(time()); ?>"/>
					<div id="imageResizerKeepProportions" style="diplay: <?php PIE_Echo(($objPHPImageEditor->inputKeepProportions && $objPHPImageEditor->inputPanel == PIE_MENU_RESIZE) ? 'block' : 'none'); ?>; width: <?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>px;"></div>
					<div id="imageResizerNoProportions" style="diplay: <?php PIE_Echo((!$objPHPImageEditor->inputKeepProportions && $objPHPImageEditor->inputPanel == PIE_MENU_RESIZE) ? 'block' : 'none'); ?>; width: <?php PIE_Echo($objPHPImageEditor->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPHPImageEditor->GetHeightFinal()); ?>px;"></div>
				</div>	
			<?php } ?>

<?php if (!$objPHPImageEditor->isAjaxPost) { ?>
		</div>
	</body>
	</html>
<?php } ?>

<?php $objPHPImageEditor->CleanUp(); ?>
<?php die; ?>