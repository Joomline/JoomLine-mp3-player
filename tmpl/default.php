<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_news
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div id="jlplayer_<?php echo $module->id; ?>" style="width:<?php echo $width; ?>" class="skin_<?php echo $skin; ?>">
<?php if ($onebutton) : ?>
    <a  class="openNewWindowA" href="javascript:;">
        <img src="<?php echo $base_uri; ?>modules/mod_jlplayer2/skins/<?php echo $skin; ?>/btn_popup.png" alt="play"/>
    </a>
<?php  else : ?>
	<div class="openNewWindow">
        <a  class="openNewWindowA" href="javascript:;">
            <?php echo JText::_('MOD_JLP_NEWWINDOW'); ?>
        </a>
    </div>

	<div class="jp-nowplay">
        <?php if ($scrollTrackName) : ?>
            <marquee behavior="scroll" direction="left" onclick="this.stop();" ondblclick="this.start();"><?php echo JText::_('MOD_JLP_NOSONGS'); ?></marquee>
        <?php  else : ?>
            <div><?php echo JText::_('MOD_JLP_NOSONGS'); ?></div>
        <?php endif; ?>
    </div>

    <?php if(empty($html)) : ?>
	<div id="jquery_jplayer_<?php echo $module->id; ?>" class="jp-jplayer"></div>

	<div id="jp_container_<?php echo $module->id; ?>" class="jp-audio">
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
					<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle"><?php echo JText::_('MOD_JLP_SHUFFLE'); ?>"></a></li>
					<?php if ($shuffle) : ?><li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="<?php echo JText::_('MOD_JLP_SHUFFLE'); ?>"><?php echo JText::_('MOD_JLP_SHUFFLE'); ?></a></li><?php endif; ?>
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
<?php endif; ?>
</div>
