<?php  
    
	
	    /*
	    Copyright 2008, 2009, 2010, 2011 Patrik Hultgren
	    
	    YOUR PROJECT MUST ALSO BE OPEN SOURCE IN ORDER TO USE THIS VERSION OF PHP IMAGE EDITOR.
	    BUT YOU CAN USE PHP IMAGE EDITOR JOOMLA PRO IF YOUR CODE NOT IS OPEN SOURCE.
	    
	    This file is part of PHP Image Editor Joomla.
	
	    PHP Image Editor Joomla is free software: you can redistribute it and/or modify
	    it under the terms of the GNU General Public License as published by
	    the Free Software Foundation, either version 3 of the License, or
	    (at your option) any later version.
	
	    PHP Image Editor Joomla is distributed in the hope that it will be useful,
	    but WITHOUT ANY WARRANTY; without even the implied warranty of
	    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	    GNU General Public License for more details.
	
	    You should have received a copy of the GNU General Public License
	    along with PHP Image Editor Joomla. If not, see <http://www.gnu.org/licenses/>.
	    */

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
	global $objPIE;
	$objPIE = new PHPImageEditor();
?>
<?php if (!$objPIE->isAjaxPost) { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
		  <title>PHP Image Editor</title>

      <meta name="viewport" content="width=device-width, intial-scale=1.0" />
      <meta charset="utf-8"/>

    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/ui.resizable.css', __FILE__ ); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/ui.slider.css', __FILE__ ); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/jquery.jcrop.css', __FILE__ ); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php print plugins_url('css/style.css', __FILE__ ); ?>"/>

    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery-1.10.2.min.js', __FILE__ ); ?>"></script>
    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery-ui-1.10.3.custom.min.js', __FILE__ ); ?>"></script>
    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery.ui.touch-punch.min.js', __FILE__ ); ?>"></script>
    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery.jcrop.js', __FILE__ ); ?>"></script>
    <script type="text/javascript" src="<?php print plugins_url('javascript/jquery.numeric.js', __FILE__ ); ?>"></script>
    <script type="text/javascript" src="<?php print plugins_url('javascript/respond.min.js', __FILE__ ); ?>"></script>
    
    <script type="text/javascript" src="<?php print plugins_url('javascript/phpimageeditor.js', __FILE__ ); ?>"></script>

      <!--[if lt IE 9]>
        <script src="<?php print plugins_url('javascript/html5', __FILE__ ); ?>"></script>
      <![endif]-->
		
        <script type="text/javascript">
	        var ImageMaxWidth = <?php PIE_Echo(PIE_IMAGE_MAX_WIDTH); ?>;
	        var ImageMaxHeight = <?php PIE_Echo(PIE_IMAGE_MAX_HEIGHT); ?>;
	        var ImageWidth = <?php PIE_Echo($objPIE->GetWidthFinal()); ?>;
	        var ImageHeight = <?php PIE_Echo($objPIE->GetHeightFinal()); ?>;
	        var TextIsRequired = "<?php PIE_Echo($objPIE->texts["IS REQUIRED"]); ?>";
	        var TextMustBeNumeric = "<?php PIE_Echo($objPIE->texts["MUST BE NUMERIC"]); ?>";
	        var TextWidth = "<?php PIE_Echo($objPIE->texts["WIDTH"]); ?>";
	        var TextHeight = "<?php PIE_Echo($objPIE->texts["HEIGHT"]); ?>";
	        var TextNotNegative = "<?php PIE_Echo($objPIE->texts["NOT NEGATIVE"]); ?>";
	        var TextNotInRange = "<?php PIE_Echo($objPIE->texts["NOT IN RANGE"]); ?>";
	        var TextCantBeLargerThen = "<?php PIE_Echo($objPIE->texts["CANT BE LARGER THEN"]); ?>";
	        var TextAnUnexpectedError = "<?php PIE_Echo($objPIE->texts["AN UNEXPECTED ERROR"]); ?>";
	        var Brightness = <?php PIE_Echo($objPIE->inputBrightness); ?>;
	        var Contrast = <?php PIE_Echo($objPIE->inputContrast); ?>;
	        var BrightnessMax = <?php PIE_Echo($objPIE->brightnessMax); ?>;
	        var ContrastMax = <?php PIE_Echo($objPIE->contrastMax); ?>;
            var FormAction = "<?php PIE_Echo($objPIE->GetFormAction()); ?>";
            var FormId = "<?php PIE_Echo($objPIE->formName); ?>";
            var ActionUpdate = "<?php PIE_Echo($objPIE->actionUpdate); ?>";
            var ActionUndo = "<?php PIE_Echo($objPIE->actionUndo); ?>";
            var ActionSaveAndClose = "<?php PIE_Echo($objPIE->actionSaveAndClose); ?>";
            var ActionRotateLeft = "<?php PIE_Echo($objPIE->actionRotateLeft); ?>";
            var ActionRotateRight = "<?php PIE_Echo($objPIE->actionRotateRight); ?>";
            var ActionSaveAndClose = "<?php PIE_Echo($objPIE->actionSaveAndClose); ?>";
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

			<form id="<?php PIE_Echo($objPIE->formName); ?>" name="<?php PIE_Echo($objPIE->formName); ?>" method="post" action="<?php PIE_Echo($objPIE->GetFormAction()); ?>">
				<?php if (!$objPIE->ErrorHasOccurred()) { ?>
					 
					<div class="tabs">
            <div id="menu-as-select-container">					
  					  <select id="menu-as-select">
  					   <option <?php PIE_Echo($objPIE->inputPanel == PIE_MENU_RESIZE ? 'selected="selected"' : ''); ?> value="<?php print PIE_MENU_RESIZE; ?>"><?php PIE_Echo($objPIE->texts["RESIZE IMAGE"]); ?></option>
               <option <?php PIE_Echo($objPIE->inputPanel == PIE_MENU_ROTATE ? 'selected="selected"' : ''); ?> value="<?php print PIE_MENU_ROTATE; ?>"><?php PIE_Echo($objPIE->texts["ROTATE IMAGE"]); ?></option>
               <option <?php PIE_Echo($objPIE->inputPanel == PIE_MENU_CROP ? 'selected="selected"' : ''); ?> value="<?php print PIE_MENU_CROP; ?>"><?php PIE_Echo($objPIE->texts["CROP IMAGE"]); ?></option>
               <option <?php PIE_Echo($objPIE->inputPanel == PIE_MENU_EFFECTS ? 'selected="selected"' : ''); ?> value="<?php print PIE_MENU_EFFECTS; ?>"><?php PIE_Echo($objPIE->texts["EFFECTS"]); ?></option>
  					  </select>
					  </div>
					
						<div id="menu">
							<?php if (PIE_RESIZE_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPIE->inputPanel == PIE_MENU_RESIZE ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_RESIZE); ?>">
									<h1><?php PIE_Echo($objPIE->texts["RESIZE IMAGE"]); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_ROTATE_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPIE->inputPanel == PIE_MENU_ROTATE ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_ROTATE); ?>">
									<h1><?php PIE_Echo($objPIE->texts["ROTATE IMAGE"]); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_CROP_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPIE->inputPanel == PIE_MENU_CROP ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_CROP); ?>">
									<h1><?php PIE_Echo($objPIE->texts["CROP IMAGE"]); ?></h1>
								</div>
							<?php } ?>
							<?php if (PIE_EFFECTS_ENABLED) { ?>
								<div class="<?php PIE_Echo($objPIE->inputPanel == PIE_MENU_EFFECTS ? 'selected' : 'not-selected'); ?>" id="menuitem_<?php PIE_Echo(PIE_MENU_EFFECTS); ?>">
									<h1><?php PIE_Echo($objPIE->texts["EFFECTS"]); ?></h1>
								</div>
							<?php } ?>
						</div>
							
						<div id="actionContainer">
						
							<div id="panel_<?php PIE_Echo(PIE_MENU_RESIZE); ?>" class="panel">
								<div class="fields">
									<div class="field width">
										<label for="width"><?php PIE_Echo($objPIE->texts["WIDTH"]); ?></label>
										<input class="input-number" type="text" name="width" id="width" value="<?php PIE_Echo($objPIE->GetWidthFinal()); ?>"/>
										<input type="hidden" name="widthoriginal" id="widthoriginal" value="<?php PIE_Echo($objPIE->GetWidth()); ?>"/>
									</div>
									<div class="field height">
										<label for="height"><?php PIE_Echo($objPIE->texts["HEIGHT"]); ?></label>
										<input class="input-number" type="text" name="height" id="height" value="<?php PIE_Echo($objPIE->GetHeightFinal()); ?>"/>
										<input type="hidden" name="heightoriginal" id="heightoriginal" value="<?php PIE_Echo($objPIE->GetHeight()); ?>"/>
									</div>
                  <div class="field keepproportions">
                    <input class="checkbox" type="checkbox" name="<?php PIE_Echo($objPIE->fieldNameKeepProportions); ?>" id="<?php PIE_Echo($objPIE->fieldNameKeepProportions); ?>" <?php PIE_Echo($objPIE->inputKeepProportions ? 'checked="checked"' : ''); ?>/>
                    <input type="hidden" name="keepproportionsval" id="keepproportionsval" value="<?php PIE_Echo($objPIE->inputKeepProportions ? '1' : '0'); ?>"/>
                    <label for="<?php PIE_Echo($objPIE->fieldNameKeepProportions); ?>" class="checkbox"><?php PIE_Echo($objPIE->texts["KEEP PROPORTIONS"]); ?></label>
                  </div>
								</div>
								<div class="help">
									<div class="header"><?php PIE_Echo($objPIE->texts["INSTRUCTIONS"]); ?></div>
									<div class="content"><?php PIE_Echo($objPIE->texts["RESIZE HELP"]); ?></div>
								</div>
							</div>
		
							<div id="panel_<?php PIE_Echo(PIE_MENU_ROTATE); ?>" class="panel">
                <div class="fields">
  								<div class="field">
  									<input id="btnRotateLeft" type="button" value="<?php PIE_Echo($objPIE->texts["LEFT 90 DEGREES"]); ?>"/>
  								</div>
                  <div class="field">
                    <input id="btnRotateRight" type="button" value="<?php PIE_Echo($objPIE->texts["RIGHT 90 DEGREES"]); ?>"/>
                  </div>
                  <input type="hidden" name="rotate" id="rotate" value="-1"/>
                </div>
							</div>
		
							<div id="panel_<?php PIE_Echo(PIE_MENU_CROP); ?>" class="panel">
								<div class="fields">
									<input class="input-number" type="hidden" name="croptop" id="croptop" value="0"/>
									<input class="input-number" type="hidden" name="cropleft" id="cropleft" value="0"/>
									<input class="input-number" type="hidden" name="cropright" id="cropright" value="0"/>
									<input class="input-number" type="hidden" name="cropbottom" id="cropbottom" value="0"/>
                  <div class="field cropwidth">
                    <label for="cropwidth"><?php print $objPIE->texts["CROP WIDTH"]; ?></label>
                    <input class="input-number" type="text" name="cropwidth" id="cropwidth" value="0"/>
                  </div>
                  <div class="field cropheight">
                    <label for="cropheight"><?php print $objPIE->texts["CROP HEIGHT"]; ?></label>
                    <input class="input-number" type="text" name="cropheight" id="cropheight" value="0"/>
                  </div>
                  <div class="field cropx">
                    <label for="cropx"><?php print $objPIE->texts["START POSITION X"]; ?></label>
                    <input class="input-number" type="text" name="cropx" id="cropx" value="0"/>
                  </div>
                  <div class="field cropy">
                    <label for="cropy"><?php print $objPIE->texts["START POSITION Y"]; ?></label>
                    <input class="input-number" type="text" name="cropy" id="cropy" value="0"/>
                  </div>
                  <div class="field crop-settings">
                    <input id="cropkeepproportions" class="checkbox" type="checkbox" name="cropkeepproportions" <?php PIE_Echo($objPIE->inputCropKeepProportions ? 'checked="checked"' : ''); ?>/>
                    <label class="checkbox" for="cropkeepproportions"><?php PIE_Echo($objPIE->texts["CROP KEEP PROPORTIONS"]); ?></label>
                    <input id="cropkeepproportionsval" type="hidden" name="cropkeepproportionsval" value="<?php PIE_Echo($objPIE->inputCropKeepProportions ? '1' : '0'); ?>"/>                 
                    <input id="cropkeepproportionsratiow" type="hidden" name="cropkeepproportionsratiow" value="<?php print $objPIE->inputCropKeepProportionsRatioW; ?>"/>                  
                    <input id="cropkeepproportionsratioh" type="hidden" name="cropkeepproportionsratioh" value="<?php print $objPIE->inputCropKeepProportionsRatioH; ?>"/>                  
                  </div>
								</div>
                <div class="help">
                  <div class="header"><?php PIE_Echo($objPIE->texts["INSTRUCTIONS"]); ?></div>
                  <div class="content"><?php PIE_Echo($objPIE->texts["CROP HELP"]); ?><br/><?php print $objPIE->texts["CROP HELP FIELDS"]; ?></div>
                </div>
							</div>
							
							<div id="panel_<?php PIE_Echo(PIE_MENU_EFFECTS); ?>" class="panel">
                <div class="fields">
  								<div class="field brightness">
  									<label for="brightness"><?php PIE_Echo($objPIE->texts["BRIGHTNESS"]); ?></label>
  									<div id="brightness_slider_track"></div>
  								</div>
  								<input type="hidden" name="brightness" id="brightness" value="<?php PIE_Echo($objPIE->inputBrightness); ?>"/>
  								<div class="field contrast">
  									<label for="contrast"><?php PIE_Echo($objPIE->texts["CONTRAST"]); ?></label>
  									<div id="contrast_slider_track"></div>
  								</div>
  								<input type="hidden" name="contrast" id="contrast" value="<?php PIE_Echo($objPIE->inputContrast); ?>"/>
  								<div class="field">
  									<input class="checkbox" type="checkbox" name="<?php PIE_Echo($objPIE->actionGrayscale); ?>" id="<?php PIE_Echo($objPIE->actionGrayscale); ?>" <?php PIE_Echo($objPIE->inputGrayscale ? 'checked="checked"' : ''); ?>/>
  									<label for="<?php PIE_Echo($objPIE->actionGrayscale); ?>" class="checkbox"><?php PIE_Echo($objPIE->texts["GRAYSCALE"]); ?></label>
  									<input type="hidden" name="grayscaleval" id="grayscaleval" value="<?php PIE_Echo($objPIE->inputGrayscale ? '1' : '0'); ?>"/>
  								</div>
                </div>
							</div>

							<div id="loading" style="display: none;"><?php PIE_Echo($objPIE->texts["LOADING"]); ?>...</div>
		
						</div>
						
						<div class="main-actions">
							<div class="inner">
							 <input type="button" id="btnupdate" name="btnupdate" value="<?php PIE_Echo($objPIE->texts["UPDATE"]); ?>"/>
							 <input type="button" id="btnsave" name="btnsave" value="<?php PIE_Echo($objPIE->texts["SAVE AND CLOSE"]); ?>"/>
							 <input type="button" <?php PIE_Echo($objPIE->actions == "" ? 'disabled="disabled"' : ''); ?> id="btnundo" name="btnundo" value="<?php PIE_Echo($objPIE->texts["UNDO"]); ?>"/>
						  </div>
						</div>
		
					</div>
					<input type="hidden" name="actiontype" id="actiontype" value="<?php PIE_Echo($objPIE->actionUpdate); ?>"/>
					<input type="hidden" name="panel" id="panel" value="<?php PIE_Echo($objPIE->inputPanel); ?>"/>
					<input type="hidden" name="language" id="language" value="<?php PIE_Echo($objPIE->inputLanguage); ?>"/>
					<textarea name="actions" id="actions"><?php $objPIE->GetActions(); ?></textarea>
					<input type="hidden" name="widthlast" id="widthlast" value="<?php PIE_Echo($objPIE->GetWidthFinal()); ?>"/>
					<input type="hidden" name="heightlast" id="heightlast" value="<?php PIE_Echo($objPIE->GetHeightFinal()); ?>"/>
					<input type="hidden" name="widthlastbeforeresize" id="widthlastbeforeresize" value="<?php PIE_Echo($objPIE->GetWidthKeepProportions()); ?>"/>
					<input type="hidden" name="heightlastbeforeresize" id="heightlastbeforeresize" value="<?php PIE_Echo($objPIE->GetHeightKeepProportions()); ?>"/>
					<input type="hidden" name="userid" id="userid" value="<?php PIE_Echo($objPIE->userId); ?>"/>
					<input type="hidden" name="contrastlast" id="contrastlast" value="<?php PIE_Echo($objPIE->inputContrast); ?>"/>
					<input type="hidden" name="brightnesslast" id="brightnesslast" value="<?php PIE_Echo($objPIE->inputBrightness); ?>"/>
					<input type="hidden" name="isajaxpost" id="isajaxpost" value="false"/>
				<?php } ?>
			</form>
			<?php $objPIE->GetErrorMessages(); ?>
			<div id="divJsErrors" class="error" style="display: none;">
				<ul id="ulJsErrors" style="display: none;"><li></li></ul>
			</div>
			<div><img src="<?php print plugins_url('images/empty.gif', __FILE__ ); ?>" alt=""/></div>
			<?php if (!$objPIE->ErrorHasOccurred()) { ?>
				<div id="editimage">
					<img id="image" style="position: absolute; left: 0px; top: 0px; width: <?php PIE_Echo($objPIE->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPIE->GetHeightFinal()); ?>px;" alt="" src="<?php PIE_Echo(PIE_PLUGINS_URL_FROM_INDEX.'/editimagesworkwith/'.str_replace(PIE_IMAGE_WORK_WITH_PATH, '', $objPIE->srcWorkWith)); ?>?timestamp=<?php PIE_Echo(time()); ?>"/>
					<div id="imageResizerKeepProportions" style="diplay: <?php PIE_Echo(($objPIE->inputKeepProportions && $objPIE->inputPanel == PIE_MENU_RESIZE) ? 'block' : 'none'); ?>; width: <?php PIE_Echo($objPIE->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPIE->GetHeightFinal()); ?>px;"></div>
					<div id="imageResizerNoProportions" style="diplay: <?php PIE_Echo((!$objPIE->inputKeepProportions && $objPIE->inputPanel == PIE_MENU_RESIZE) ? 'block' : 'none'); ?>; width: <?php PIE_Echo($objPIE->GetWidthFinal()); ?>px; height: <?php PIE_Echo($objPIE->GetHeightFinal()); ?>px;"></div>
				</div>	
			<?php } ?>

<?php if (!$objPIE->isAjaxPost) { ?>
		</div>
	</body>
	</html>
<?php } ?>

<?php $objPIE->CleanUp(); ?>
<?php die; ?>