<?php
 /**
 * @package mod_jlplayer
 * @author Joomline (sale@joomline.net)
 * @version 3.0
 * @copyright (C) 2011 by Joomline (http://www.joomline.net)
 * @license JoomLine: http://joomline.net/licenzija-joomline.html
 *
*/
define('_JEXEC', 1);

chdir("../../");

define('JPATH_BASE', getcwd() );

define( 'DS', DIRECTORY_SEPARATOR );

if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
	define('JPATH_BASE', dirname(__FILE__));
	require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

require_once (dirname(__FILE__).DS.'helper.php');

jimport( 'joomla.application.module.helper' );

jimport( 'joomla.html.parameter' ); 
jimport( 'joomla.string.string' );

function getValue($object, $name, $default='')
{
    return !empty($object->$name) ? $object->$name : $default;
}

// Instantiate the application.
$app 		= JFactory::getApplication('site');
$mid 		= $app->input->getInt('mid', 0);
$paused 		= $app->input->getString('paused', 'play');
$db = JFactory::getDBO();

$jlpv2Lang = JFactory::getLanguage();
$jlpv2Lang->load("mod_jlplayer2");

preg_match('|(.*)\/modules\/mod_jlplayer2|', JURI::base(), $matches);
$base_uri 		= $matches[1]."/";
$nw				= 0;

$db->setQuery("SELECT `params` FROM #__modules WHERE `module`='mod_jlplayer2' AND id = $mid");
$params = $db->loadResult();

$params 		= json_decode($params);

$music_dir 		= getValue($params, 'directory');
$playlist 		= getValue($params, 'playlist');
$customlist 	= getValue($params, 'customlist');
$shuffle 		= getValue($params, 'shuffle');
$plvisible 		= getValue($params, 'plvisible');
$autoplay		= getValue($params, 'autoplay') == 1 ? "true" : "false";
$server_utf8 	= getValue($params, 'server_utf8');
$skin 			= getValue($params, 'skin');
$width	    	= getValue($params, 'width');
$scrollTrackName= getValue($params, 'scroll_track_name');


if ($customlist) 
	$pl_list = modJlplayer2Helper::playListgGen($music_dir ,$base_uri, $server_utf8, $nw, $customlist);
else 
	$pl_list = modJlplayer2Helper::genPlaylist($music_dir, $base_uri, $server_utf8, $playlist, $nw, $customlist, $playlist);

$htmlFile = JPATH_ROOT.'/modules/mod_jlplayer2/skins/'.$skin.'/skin.html';

$nosolution = '<span>'.JText::_('MOD_JLP_UPDATE_REQUIRED')
    .'</span>'.JText::_('MOD_JLP_UPDATE_DESC')
    .' <a href="http://get.adobe.com/flashplayer/" target="_blank">'
    .JText::_('MOD_JLP_FLASH').'</a>.';

$html = '';
if(is_file($htmlFile)){
    ob_start();
    require $htmlFile;
    $html = ob_get_clean();
    $html = JString::str_ireplace(
        array('{{jquery_jplayer_id}}', '{{jp_container_id}}', '{{no-solution}}'),
        array('jquery_jplayer_'.$mid,  'jp_container_'.$mid, $nosolution),
        $html
    );
}

//header("Content-Type: text/html; charset=UTF-8 ");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<base href="<?php echo $base_uri; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>JL Player</title>
	<link href="<?php echo $base_uri; ?>modules/mod_jlplayer2/skins/<?php echo $skin; ?>/skin.css" rel="stylesheet" type="text/css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">jQuery.noConflict();</script>
	<script src="<?php echo $base_uri; ?>modules/mod_jlplayer2/assets/js/jquery.jplayer.min.js" type="text/javascript"></script>
	<script src="<?php echo $base_uri; ?>modules/mod_jlplayer2/assets/js/add-on/jplayer.playlist.min.js" type="text/javascript"></script>
	<script src="<?php echo $base_uri; ?>modules/mod_jlplayer2/assets/js/player.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		jQuery(window).load(function()
        {
            jQuery.cookie('paused<?php echo $mid; ?>', '<?php echo $paused; ?>', cookieOptions);

			jQuery("#jlplayer_<?php echo $mid; ?>").jlPlayer({
                id: "<?php echo $mid; ?>",
                pllist: [
					<?php echo implode(",\n", $pl_list); ?>
				],
                autoplay: false,
                baseUri: "<?php echo $base_uri; ?>"
            });
		});	
	</script>
	
</head>
<body>
<div id="jlplayer_<?php echo $mid; ?>" style="width:<?php echo $width; ?>" class="skin_<?php echo $skin; ?>">

	<div class="jp-nowplay">
        <?php if ($scrollTrackName) : ?>
            <marquee behavior="scroll" direction="left" onclick="this.stop();" ondblclick="this.start();"><?php echo JText::_('MOD_JLP_NOSONGS'); ?></marquee>
        <?php  else : ?>
            <div><?php echo JText::_('MOD_JLP_NOSONGS'); ?></div>
        <?php endif; ?>
    </div>

	<?php if(empty($html)) : ?>
	<div id="jquery_jplayer_<?php echo $mid; ?>" class="jp-jplayer"></div>

	<div id="jp_container_<?php echo $mid; ?>" class="jp-audio">
		<div class="jp-type-playlist">
			<div class="jp-gui jp-interface">
				<ul class="jp-controls">
					<li><a href="javascript:;" class="jp-previous" tabindex="1"><?php echo JText::_('MOD_JLP_PREV'); ?></a></li>
					<li><a href="javascript:;" class="jp-play" tabindex="1"><?php echo JText::_('MOD_JLP_PLAY'); ?></a></li>
					<li><a href="javascript:;" class="jp-pause" tabindex="1"><?php echo JText::_('MOD_JLP_PAUSE'); ?></a></li>
					<li><a href="javascript:;" class="jp-next" tabindex="1"><?php echo JText::_('MOD_JLP_NEXT'); ?></a></li>
					<li><a href="javascript:;" class="jp-stop" tabindex="1"><?php echo JText::_('MOD_JLP_STOP'); ?></a></li>
					<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute"><?php echo JText::_('MOD_JLP_MUTE'); ?></a></li>
					<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute"><?php echo JText::_('MOD_JLP_UNMUTE'); ?></a></li>
					<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume"><?php echo JText::_('MOD_JLP_MAX_VOLUME'); ?></a></li>
				</ul>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
						</div>
				</div>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
				<div class="jp-current-time"></div>
				<div class="jp-duration"></div>
				<ul class="jp-toggles">
					<?php if ($shuffle) : ?>
					<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle"><?php echo JText::_('MOD_JLP_SHUFFLE'); ?></a></li>
					<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="<?php echo JText::_('MOD_JLP_SHUFFLE'); ?>"><?php echo JText::_('MOD_JLP_SHUFFLE'); ?></a></li>
					<?php endif; ?>
					<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat"><?php echo JText::_('MOD_JLP_REPEAT'); ?></a></li>
					<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off"><?php echo JText::_('MOD_JLP_REPEAT_OFF'); ?></a></li>
				</ul>
			</div>
			<div class="jp-playlist">
				<ul>
					<li></li>
				</ul>
			</div>
			<div class="showhide_playlist"><?php echo JText::_('MOD_JLP_SHOWHIDE'); ?></div>
			<div class="jp-no-solution">
                <?php echo $nosolution ?>
            </div>
		</div>
	</div>
    <?php
        else :
            echo $html;
        endif;
    ?>
</div>
</body>
</html>
