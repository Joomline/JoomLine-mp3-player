var
	cookieOptions = {expires: 7, path: '/'},
	jPlayerLoaded = 0;

// Contact Accordion
;(function($, window, document, undefined){
	$.fn.jlPlayer = function(opt){	
		
		var defaults = {
			id: 95,
			pllist: "",
			autoplay: false,
			baseUri: ''
		},
		o = $.extend(defaults, opt);
		
		this.each(function(i) {
		
			var $this = $(this),						// element
			listhide 	= $this.find('.jp-playlist'), 	// playlist block
            cookieVolume = $.cookie('volume' + o.id),
            cookieProgress = $.cookie('progress' + o.id);
			/** jPlayer options **/
			
			cssSelector = {jPlayer: '#jquery_jplayer_' + o.id, cssSelectorAncestor: '#jp_container_' + o.id},
			options 	= {
				loadstart: function(event)
                {


					$.each(o.pllist, function(i, v) { // media
						if(event.jPlayer.status.src == v.mp3) $.cookie('media' + o.id, i, cookieOptions);
					});

                    $marquee = $this.find('marquee');
                    
					if(!$marquee.length){
                        $this.find('.jp-nowplay div').html(event.jPlayer.status.media.name);
                    }
                    else{
                        $marquee.html(event.jPlayer.status.media.name); // marquee
                    }

					if(typeof cookieProgress != 'undefined' && cookieProgress != '' && jPlayerLoaded < 2){
						$(this).jPlayer('playHead', cookieProgress);  // progress
						jPlayerLoaded++;
					}


                    if(typeof cookieVolume != 'undefined' && cookieVolume != '')
						$(this).jPlayer('volume', cookieVolume);  // volume
					
				},
				play: function(event) {
					$(this).jPlayer('pauseOthers'); // stop others jn play
					$.cookie('paused' + o.id, 'play', cookieOptions);
				},
				timeupdate: function(event) {
					$.cookie('progress' + o.id, event.jPlayer.status.currentPercentRelative, cookieOptions);
				},
				pause: function(event) {
					$.cookie('paused' + o.id, 'paused', cookieOptions);
				},
				volumechange: function(event) {
					$.cookie('volume' + o.id, event.jPlayer.options.volume, cookieOptions);
				},
				playlistOptions: {
					autoPlay: o.autoplay
				},
				swfPath:		o.baseUri + 'modules/mod_jlplayer2/assets/js',
				supplied: 		'mp3',
				wmode: 			'window',
				smoothPlayBar: 	true,
				keyEnabled: 	true
			},

			/** jPlayerPlaylist options **/
			
			Playlist 	= new jPlayerPlaylist(cssSelector, o.pllist, options);
				
			/** jlPlayer actions **/
				
            var $mediaCookie = $.cookie('media' + o.id),
                $pausedCookie = $.cookie('paused' + o.id),
                $shuffleCookie = $.cookie('shuffle' + o.id),
                $repeatCookie = $.cookie('repeat' + o.id),
                $mutedCookie = $.cookie('muted' + o.id),
                $showhideCookie = $.cookie('showhide' + o.id)
                ;

			$this.find('.jp-shuffle').on('click', function() { // .jp-shuffle
				if($shuffleCookie == 'shuffle')
					$.cookie('shuffle' + o.id, 'unshuffle', cookieOptions);
				else $.cookie('shuffle' + o.id, 'shuffle', cookieOptions);
			});	
						
			$this.find('.jp-repeat').on('click', function() { // .jp-repeat
				if($repeatCookie == 'repeat')
					$.cookie('repeat' + o.id, 'unrepeat', cookieOptions);
				else $.cookie('repeat' + o.id, 'repeat', cookieOptions);
			});		
						
			$this.find('.jp-mute').on('click', function() { // .jp-mute
				if($mutedCookie == 'mute')
					$.cookie('muted' + o.id, 'unmute', cookieOptions);
						else $.cookie('muted' + o.id, 'mute', cookieOptions);
			});
									
			$this.find('.jp-unmute').on('click', function() { // .jp-unmute
				$.cookie('muted' + o.id, 'unmute', cookieOptions);
			});		
						
			$this.find('.jp-volume-max').on('click', function() { // .jp-volume-max
				$.cookie('muted' + o.id, 'unmute', cookieOptions);
			});		
						
			$this.find('.showhide_playlist').on('click', function() { // .showhide_playlist
				listhide.slideToggle('slow');
				$(this).toggleClass('opened');
				if($showhideCookie == 'open')
					$.cookie('showhide' + o.id, 'close', cookieOptions);
				else $.cookie('showhide' + o.id, 'open', cookieOptions);
			});			
						
			if($mediaCookie != '' && $pausedCookie=='play')
				Playlist.play(parseInt($mediaCookie));
				
			if($pausedCookie == 'play')
				Playlist.option('autoPlay', true); 
			else
                Playlist.option('autoPlay', false);
						
			if($shuffleCookie == 'shuffle')
				$this.find('.jp-shuffle').trigger('click');
						
			if($repeatCookie == 'repeat')
				$this.find('.jp-repeat').trigger('click'); 
						
			if($mutedCookie == 'mute')
				$this.find('.jp-mute').trigger('click'); 
						
			if($showhideCookie == 'open' || $showhideCookie == "")
				listhide.show(); 
			else listhide.hide();			
				
			$this.find('a.openNewWindowA').on('click', function() {
                var status = ($this.find('.jp-play').is(":visible")) ? 'paused' : 'play';
                var nwindow = o.baseUri + 'modules/mod_jlplayer2/standalone.php?mid=' + o.id + '&paused=' + status;
				window.open(nwindow, 'JLPlayer', 'width=500, height=300, left=400, top=150, toolbar=no, location=no, directories=no, menubar=no, status=no, fullscreen=no, scrollbars=yes, resize=yes');
                $.jPlayer.pause();
			});	
		});
	}
})(jQuery, window, document);

/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function(a){if(typeof define==="function"&&define.amd){define(["jquery"],a)}else{a(jQuery)}}(function(f){var a=/\+/g;function d(i){return b.raw?i:encodeURIComponent(i)}function g(i){return b.raw?i:decodeURIComponent(i)}function h(i){return d(b.json?JSON.stringify(i):String(i))}function c(i){if(i.indexOf('"')===0){i=i.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\")}try{i=decodeURIComponent(i.replace(a," "));return b.json?JSON.parse(i):i}catch(j){}}function e(j,i){var k=b.raw?j:c(j);return f.isFunction(i)?i(k):k}var b=f.cookie=function(q,p,v){if(p!==undefined&&!f.isFunction(p)){v=f.extend({},b.defaults,v);if(typeof v.expires==="number"){var r=v.expires,u=v.expires=new Date();u.setTime(+u+r*86400000)}return(document.cookie=[d(q),"=",h(p),v.expires?"; expires="+v.expires.toUTCString():"",v.path?"; path="+v.path:"",v.domain?"; domain="+v.domain:"",v.secure?"; secure":""].join(""))}var w=q?undefined:{};var s=document.cookie?document.cookie.split("; "):[];for(var o=0,m=s.length;o<m;o++){var n=s[o].split("=");var j=g(n.shift());var k=n.join("=");if(q&&q===j){w=e(k,p);break}if(!q&&(k=e(k))!==undefined){w[j]=k}}return w};b.defaults={};f.removeCookie=function(j,i){if(f.cookie(j)===undefined){return false}f.cookie(j,"",f.extend({},i,{expires:-1}));return !f.cookie(j)}}));
