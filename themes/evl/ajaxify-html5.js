// https://gist.github.com/854622
(function(window,undefined){
	
	// Prepare our Variables
	var
		History = window.History,
		$ = window.jQuery,
		document = window.document;

	// Check to see if History.js is enabled for our Browser
	if ( !History.enabled ) {
		return false;
	}

	// Wait for Document
	$(function(){
		// Prepare Variables
		var
			/* Application Specific Variables */
			contentSelector = 'section#body',
			$content = $(contentSelector).filter(':first'),
			contentNode = $content.get(0),
                        $header = $('head').filter(':first'),
			$menus = $('#navarea, #menus'),
			activeClass = 'nav-selected',
			activeSelector = '.'+activeClass,
			menuChildrenSelector = 'li.menu-element',
			/* Application Generic Variables */
			$body = $(document.body),
			rootUrl = History.getRootUrl(),
			scrollOptions = {
				duration: 800,
				easing:'swing'
			};
		
		// Ensure Content
		if ( $content.length === 0 ) {
			$content = $body;
		}
		
		// Internal Helper
		$.expr[':'].internal = function(obj, index, meta, stack){
			// Prepare
			var
				$this = $(obj),
				url = $this.attr('href')||'',
				isInternalLink;
			
			// Check link
			isInternalLink = url.substring(0,rootUrl.length) === rootUrl || url.indexOf(':') === -1;
			
			// Ignore or Keep
			return isInternalLink;
		};
		
		// HTML Helper
		var documentHtml = function(html){
			// Prepare
			var result = String(html)
				.replace(/<\!DOCTYPE[^>]*>/i, '')
				.replace(/<(html|head|body|title|meta|script|link)([\s\>])/gi,'<div class="document-$1"$2')
				.replace(/<\/(html|head|body|title|meta|script|link)\>/gi,'</div>')
			;
			
			// Return
			return result;
		};
		
		// Ajaxify Helper
		$.fn.ajaxify = function(){
			// Prepare
			var $this = $(this);
			
			// Ajaxify
			$this.find('a:internal:not(.no-ajaxy, .AmiantImageGalleryThumbnailLink)').click(function(event){
				// Prepare
				var
					$this = $(this),
					url = $this.attr('href'),
					title = $this.attr('title')||null;
				
				// Continue as normal for cmd clicks etc
				if ( event.which == 2 || event.metaKey ) { return true; }
				
				// Ajaxify this link
				History.pushState(null,title,url);
				event.preventDefault();
				return false;
			});
			
			// Chain
			return $this;
		};
		
		// Ajaxify our Internal Links
		$body.ajaxify();
                $loader = $('<div class="content-throbber">').css('width', $content.css('width')).append($('<img>').attr('src', theme_path+"/images/ajax-loader.gif"));
                $body.append($loader.hide());
		
		// Hook into State Changes
		$(window).bind('statechange',function(){
			// Prepare Variables
			var
				State = History.getState(),
				url = State.url,
				relativeUrl = url.replace(rootUrl,'');

			// Set Loading
			$body.addClass('loading');

			// Start Fade Out
			// Animating to opacity to 0 still keeps the element's height intact
			// Which prevents that annoying pop bang issue when loading in new content
			$content.animate({opacity:0},800, function() {$(this).empty().css('opacity', 1).append($loader.detach().show());});
			
			// Ajax Request the Traditional Page
			$.ajax({
				url: url,
				success: function(data, textStatus, jqXHR){
					// Prepare
					var
						$data = $(documentHtml(data)),
						$dataBody = $data.find('.document-body:first'),
                                                $dataHead = $data.find('.document-head:first'),
						$dataContent = $dataBody.find(contentSelector).filter(':first'),
                                                $dataMeta = $data.find('.document-meta:first'),
						$menuChildren, contentHtml, $scripts;
					
					// Fetch the scripts
					$scripts = $dataContent.find('.document-script');
					if ( $scripts.length ) {
						$scripts.detach();
					}
                                        
					$headScripts = $dataHead.find('.document-script');
					if ( $headScripts.length ) {
						$headScripts.detach();
					}             
                                        
                                        $links = $dataHead.find('.document-link[rel="stylesheet"]');
					if ( $links.length ) {
						$links.detach();
					}

					// Fetch the content
					contentHtml = $dataContent.html()||$data.html();
					if ( !contentHtml ) {
						document.location.href = url;
						return false;
					}
					
					// Update the menu
					$menuChildren = $menus.find(menuChildrenSelector);
					$menuChildren.filter(activeSelector).removeClass(activeClass).find("a").removeClass(activeClass);
					$menuChildren = $menuChildren.has('a[href="'+relativeUrl+'"],a[href="/'+relativeUrl+'"],a[href="'+url+'"]');
					$menuChildren.addClass(activeClass).find("a").addClass(activeClass);
                                        $menuChildren.parents(menuChildrenSelector).addClass(activeClass).find("a").addClass(activeClass);

					// Update the title
					document.title = $data.find('.document-title:first').text();
					try {
						document.getElementsByTagName('title')[0].innerHTML = document.title.replace('<','&lt;').replace('>','&gt;').replace(' & ',' &amp; ');
					}
					catch ( Exception ) { }
                                        
					// Add the links
					$links.each(function(){
						var $link = $(this), linkNode = document.createElement('link');
                                                if(!$header.find('link[href="'+$link.attr('href')+'"]').length) {
                                                    linkNode.rel = 'stylesheet';
                                                    linkNode.type = $link.attr('type');
                                                    linkNode.href = $link.attr('href');
                                                    document.getElementsByTagName("head")[0].appendChild(linkNode);
                                                }
					});   
                                        
                                        $.ajaxSetup({async: false});  //force to wait for script loads
                                        // Add the header scripts
					$headScripts.each(function(){
						var $s = $(this), sNode = document.createElement('script'), src = $s.attr('src');
                                                if(src && !$header.find('script[src="'+src+'"]').length) {
                                                    //$.getScript($s.attr('src'));
                                                    sNode.type = $s.attr('type');
                                                    sNode.src = src;
                                                    document.getElementsByTagName("head")[0].appendChild(sNode);
                                                    console.log("Loaded: "+src);
                                                }
					}); 
                                        $.ajaxSetup({async: true});
					
                                        // Update the content
					$content.stop(true,true);
                                        $loader.detach().hide().appendTo($body);
                                        $content.attr("class", $dataContent.attr("class"));
					$content.html(contentHtml).ajaxify().css('opacity',100).show(); /* you could fade in here if you'd like */
                                        
					// Add the scripts
					$scripts.each(function(){
						$('<script>').text($(this).text()).appendTo(contentNode);
					});
                                     

					// Complete the change
					if ( $body.ScrollTo||false ) { $body.ScrollTo(scrollOptions); } /* http://balupton.com/projects/jquery-scrollto */
					$body.removeClass('loading');                

					// Inform ReInvigorate of a state change
					if ( typeof window.reinvigorate !== 'undefined' && typeof window.reinvigorate.ajax_track !== 'undefined' ) {
						reinvigorate.ajax_track(url);
						// ^ we use the full url here as that is what reinvigorate supports
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					document.location.href = url;
					return false;
				}
			}); // end ajax

		}); // end onStateChange

	}); // end onDomLoad

})(window); // end closure