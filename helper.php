<?php
 /**
 * @package mod_jlvkgroup
 * @author Kunicin Vadim (vadim@joomline.ru), Anton Voynov (anton@joomline.net)
 * @version 2.4
 * @copyright (C) 2010-2012 by JoomLine (http://www.joomline.net)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
*/

// no direct access
defined('_JEXEC') or die;

require_once(JPATH_ROOT.'/modules/mod_jlplayer2/lib/audioinfo.class.php');

class modJlplayer2Helper
{
    public static function playListgGen($music_dir = 'music', $base_uri, $server_utf8 = 0, $nw = 0, $customlist = '')
    {
        $base_uri	.=  substr($base_uri,-1) ;

        $dir = str_replace("/","/", $music_dir);
        $dir = JPATH_ROOT . "/" . $dir;

        $pn = 1;

        $playlist = array();

        if ($customlist !='') {
            if ($nw == 0) $customlist = explode("\n", $customlist); else $customlist = explode('\n', $customlist);

            if (count($customlist) > 0) {
                foreach ($customlist as $list ) {
                    $list = preg_replace("/\n\r|\r\n|\n|\r/", "", $list);
                    if ($list != "") {
                        $list = explode('#',$list);
                        $playlist[] = '{name:"'.$pn.". ".$list[0].'",mp3:"'.$list[1].'", artist: "", title: "'.$list[0].'", free: true}';
                        $pn++;
                    }
                }
            }
        } else {

            if (!is_dir($dir)) {
                echo "Wrong dir in settings. <b>$dir</b> is not a directory!";
            } else {
                $files = glob($dir . "/" . "*.{mp3,MP3}", GLOB_BRACE);

                if (count($files) > 0)
                {
                    sort($files);
                    $host = $base_uri;

                    foreach ($files as $file)
                    {
                        $AudioInfo = new AudioInfo;
                        $tags = $AudioInfo->getId3tags($file);
                        unset($AudioInfo);
                        $file = explode ("/", $file);
                        if ($server_utf8 == 1) {
                            $fname = rawurlencode(iconv("cp1251","utf-8",$file[count($file)-1]));
                        } else {
                            $fname = rawurlencode($file[count($file)-1]);
                        }

                        $file = $host."".$music_dir."/".$fname;

                        $artist = trim($tags['artist']);

                        $name = $artist == "" 
							? $pn.". ".addslashes($tags['title']) 
							: $pn.". ".addslashes($artist).' - '.addslashes($tags['title']);

                        $playlist[] = array(
                            'name' 		=> $name,
                            'mp3'  		=> str_replace('u//','u/',$file),
                            'artist'	=> addslashes($artist),
                            'title'		=> addslashes($tags['title'])
                        );

                        $pn++;
                    }
                }
            }
        }
        return $playlist;
    }

// genPlaylist convert array playlist
    public static function genPlaylist($music_dir ,$base_uri, $server_utf8, $custom_pl ,$nw, $customlist, $params)
    {
        $pl = array();

        $pl_list = self::playListgGen($music_dir ,$base_uri, $server_utf8 ,$custom_pl ,$nw, $customlist);

        foreach($pl_list as $list)
        {
            if (in_array((string) $list['name'], (array) $params))
                $pl[] = '{name:"' . addslashes($list['name']) . '",mp3:"' . str_replace('u//','u/', $list['mp3']) . '", artist: "' . addslashes($list['artist']) . '", title: "' . addslashes($list['title']) . '", free: true}';
        }

        return $pl;
    }
}