<?php
 /**
 * @package mod_jlplayer
 * @author Joomline (sale@joomline.net)
 * @version 3.0
 * @copyright (C) 2011 by Joomline (http://www.joomline.net)
 * @license JoomLine: http://joomline.net/licenzija-joomline.html
 *
*/
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.string.string' );
require_once (dirname(__FILE__).'/helper.php');

$base_uri 		= JURI::base();
$music_dir 		= $params->get('directory');
$playlist 		= $params->get('playlist');
$customlist 	= $params->get('customlist');
$shuffle 		= $params->get('shuffle');
$plvisible 		= $params->get('plvisible', 2);
$autoplay		= $params->get('autoplay') == 1 ? "true" : "false"; 
$server_utf8 	= $params->get('server_utf8');
$skin 			= $params->get('skin');
$enablejquery	= $params->get('enablejquery');
$jqnoconflict	= $params->get('jqnoconflict');
$onebutton	    = $params->get('onebutton');
$width	    	= $params->get('width');
$scrollTrackName = $params->get('scroll_track_name', 1);
$nw				= 0;
// playlist 
if ($customlist) 
	$pl_list = modJlplayer2Helper::playListgGen($music_dir ,$base_uri, $server_utf8, $nw, $customlist);
else 
	$pl_list = modJlplayer2Helper::genPlaylist($music_dir, $base_uri, $server_utf8, $playlist, $nw, $customlist, $playlist);
	
	
$doc = JFactory::getDocument();

$doc->addStyleSheet($base_uri . 'modules/mod_jlplayer2/skins/' . $skin . '/skin.css');

if ($enablejquery){
    if(version_compare(JVERSION, '3.0', 'ge'))
    {
        JHtml::_('jquery.framework');
    }
    else{
        $doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
    }
}

if ($jqnoconflict) $doc->addScriptDeclaration("jQuery.noConflict();\n");

$doc->addScript($base_uri.'modules/mod_jlplayer2/assets/js/jquery.jplayer.min.js');
$doc->addScript($base_uri.'modules/mod_jlplayer2/assets/js/add-on/jplayer.playlist.min.js');
$doc->addScript($base_uri.'modules/mod_jlplayer2/assets/js/player.js');

$js = '
jQuery(window).load(function(){';

if($plvisible == 0)
{
    $js .= '
    jQuery.cookie("showhide' . $module->id . '", "close", cookieOptions);';
}
else if($plvisible == 1)
{
    $js .= '
    jQuery.cookie("showhide' . $module->id . '", "open", cookieOptions);';
}

$js .= '
	jQuery("#jlplayer_' . $module->id . '").jlPlayer({
	    id: "' . $module->id . '",
	    pllist: [
	    ' . implode(",\n", $pl_list) . '
	    ],
        autoplay: ' . $autoplay . ',
        baseUri: "' . $base_uri . '"
    });
});
'; 

$doc->addScriptDeclaration($js);

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
        array('jquery_jplayer_'.$module->id,  'jp_container_'.$module->id, $nosolution),
        $html
    );
}

require JModuleHelper::getLayoutPath('mod_jlplayer2');
?>




