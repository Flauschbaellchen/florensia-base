var fitonpage = {
	init: function()
	{
		if (fitonpage_on == 1)
		{
			if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
			{
				$('posts').observe('DOMNodeInserted', fitonpage.QRdoResize);
			}
			fitonpage.doResize(0);
		}
	},
	
	QRdoResize: function()
	{
		fitonpage.doResize(1);
	},
		
	doResize: function(qr)
	{
		if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
		{
			$('posts').stopObserving('DOMNodeInserted', fitonpage.QRdoResize);
		}
		if(fitonpage_resize == 'auto')
		{
			var resize_width = Math.ceil(document.viewport.getWidth()*(fitonpage_fluid/100));
		}
		else
		{
			var resize_width = fitonpage_resize;
		}
		var resize_imgs = $$('img');
		var topbar_width = resize_width-23;
		if(fitonpage_location == "private_read")
		{
			resize_width = resize_width-180;
			topbar_width = topbar_width-180;
		}
		if(fitonpage_location == "portal")
		{
			resize_width = resize_width-200;
			topbar_width = topbar_width-200;
		}
		var count=0;
		resize_imgs.each(function(resize_img) 
		{
			if (((fitonpage_location == "showthread" && resize_img.descendantOf('posts')) || (fitonpage_location == "private_read" && resize_img.descendantOf('pid_')) || ((fitonpage_location == "newreply" || fitonpage_location == "editpost" || fitonpage_location == "portal" || fitonpage_location == "mod_split" || fitonpage_location == "mod_mergeposts" || fitonpage_location == "mod_deleteposts") && resize_img.descendantOf('content'))) && !resize_img.hasClassName('no_fop'))
			{
				var img_dims = resize_img.getDimensions();
				var orig_width = img_dims.width;
				var orig_height = img_dims.height;
				if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread" && qr == 1)
				{
					resize_img.onload = function(){orig_width = resize_img.getWidth()};
			    }
				var topbar_orig_width = orig_width-23;
				if (orig_width > resize_width) 
				{
					var borderRight = resize_img.getStyle('border-right-width').sub('px', '').strip();
					borderRight = isNaN(borderRight) ? 0 : borderRight;
					var borderLeft = resize_img.getStyle('border-left-width').sub('px', '').strip();
					borderLeft = isNaN(borderLeft) ? 0 : borderLeft;
					var paddingRight = resize_img.getStyle('padding-right').sub('px', '').strip();
					paddingRight = isNaN(paddingRight) ? 0 : paddingRight;
					var paddingLeft = resize_img.getStyle('padding-left').sub('px', '').strip();
					paddingLeft = isNaN(paddingLeft) ? 0 : paddingLeft;
					var borderTop = resize_img.getStyle('border-top-width').sub('px', '').strip();
					borderTop = isNaN(borderTop) ? 0 : borderTop;
					var borderBottom = resize_img.getStyle('border-bottom-width').sub('px', '').strip();
					borderBottom = isNaN(borderBottom) ? 0 : borderBottom;
					var paddingTop = resize_img.getStyle('padding-top').sub('px', '').strip();
					paddingTop = isNaN(paddingTop) ? 0 : paddingTop;
					var paddingBottom = resize_img.getStyle('padding-bottom').sub('px', '').strip();
					paddingBottom = isNaN(paddingBottom) ? 0 : paddingBottom;
					var offset_width = parseInt(borderRight) + parseInt(borderLeft) + parseInt(paddingRight) + parseInt(paddingLeft);
					var offset_height = parseInt(borderTop) + parseInt(borderBottom) + parseInt(paddingTop) + parseInt(paddingBottom);
					orig_width = img_dims.width - offset_width;
					orig_height = img_dims.height - offset_height;
					if (orig_width > resize_width) 
					{
						var resize_height = Math.ceil((orig_height/orig_width)*resize_width);
						var resize_percent = Math.ceil(((orig_width-resize_width)/orig_width)*100);
						var fitonpage_topbar_resized_wh = '';
						var fitonpage_topbar_full_wh = '';
						var fitonpage_divwrap = '0px';
						if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread" && qr == 1)
						{
				    		count = count+1000;
			    		}
						count++;
						if (resize_img.up('a'))
						{
							var img_link = resize_img.up('a');
							img_link.wrap('div', { 'id':'fop_img'+count, 'style':'width:'+resize_width+'px'});
						}
						else
						{
							resize_img.wrap('div', { 'id':'fop_img'+count, 'style':'width:'+resize_width+'px'});
						}
						if (resize_img.up('div').getStyle('text-align') == 'center')
						{
							fitonpage_divwrap = '0px auto';
						}
						else if (resize_img.up('div').getStyle('text-align') == 'right')
						{
							fitonpage_divwrap = '0px 0px 0px auto';
						}
						fitonpage_topbar_resized_wh = fitonpage_topbar_resized.sub('%RSIZE%', resize_width+'x'+resize_height);
						fitonpage_topbar_resized_wh = fitonpage_topbar_resized_wh.sub('%OSIZE%', orig_width+'x'+orig_height);
						fitonpage_topbar_resized_wh = fitonpage_topbar_resized_wh.sub('%PERCENT%', resize_percent+'%');
						$('fop_img'+count).insert({ 'before': '<div id=\"fop_topbar'+count+'\" class=\"'+fitonpage_topbar_text_class+'\" style=\"width:'+topbar_width+'px;text-align:left;background:#'+fitonpage_topbar_bground+' url(\''+fitonpage_topbar_icon+'\') no-repeat left;padding:3px 3px 3px 20px;margin:'+fitonpage_divwrap+';\">'+fitonpage_topbar_resized_wh+'</div>' });
						$('fop_topbar'+count).style.cursor = 'pointer';
						$('fop_img'+count).style.cursor = 'pointer';
						var fop_topbar = $('fop_topbar'+count);
						var fop_img = $('fop_img'+count);
						fop_img.style.margin = fitonpage_divwrap;
						resize_img.style.width = resize_width+'px';
						$('fop_topbar'+count).observe('click', function(event){
							if(resize_img.width == resize_width)
							{
								fop_topbar.style.width = topbar_orig_width+'px';
								fop_img.style.width = orig_width+'px';
								resize_img.style.width = orig_width+'px';
								if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
								{
									$('posts').stopObserving('DOMNodeInserted', fitonpage.QRdoResize);
								}
								fitonpage_topbar_full_wh = fitonpage_topbar_full.sub('%OSIZE%', orig_width+'x'+orig_height);
								fitonpage_topbar_full_wh = fitonpage_topbar_full_wh.sub('%RSIZE%', resize_width+'x'+resize_height);
								fop_topbar.update(fitonpage_topbar_full_wh);
								if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
								{
									$('posts').observe('DOMNodeInserted', fitonpage.QRdoResize);
								}
							}
							else
							{
								fop_topbar.style.width = topbar_width+'px';
								fop_img.style.width = resize_width+'px';
								resize_img.style.width = resize_width+'px';
								if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
								{
									$('posts').stopObserving('DOMNodeInserted', fitonpage.QRdoResize);
								}
								fitonpage_topbar_resized_wh = fitonpage_topbar_resized.sub('%RSIZE%', resize_width+'x'+resize_height);
								fitonpage_topbar_resized_wh = fitonpage_topbar_resized_wh.sub('%OSIZE%', orig_width+'x'+orig_height);
								fitonpage_topbar_resized_wh = fitonpage_topbar_resized_wh.sub('%PERCENT%', resize_percent+'%');
								fop_topbar.update(fitonpage_topbar_resized_wh);
								if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
								{
									$('posts').observe('DOMNodeInserted', fitonpage.QRdoResize);
								}
							}
						});
						$('fop_img'+count).observe('click', function(event){
								MyBB.popupWindow(resize_img.src, '', 800, 600);
						});
					}
				}
			}
		});
		if($('quick_reply_form') && use_xmlhttprequest == 1 && fitonpage_location == "showthread")
		{
			$('posts').observe('DOMNodeInserted', fitonpage.QRdoResize);
		}
	}
};
Event.observe(window, 'load', fitonpage.init);