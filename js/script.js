jQuery(document).ready(function(jQuery)
	{	


	jQuery(".kjg-loadmore").click(function()
			{

		jQuery("#load-more .content").addClass("loading");

				var posttype = jQuery(this).attr("posttype");
				var per_page = parseInt(jQuery(this).attr("per_page"));
				var offset = parseInt(jQuery(this).attr("offset"));
				var kjg_id = jQuery(this).attr("kjg_id");
		
				var has_post = jQuery(this).attr("has_post");

				jQuery("#"+kjg_id+" .kjg-loadmore .content").html("loading...");

				if(has_post=="no")
					{

						jQuery("#"+kjg_id+" .kjg-loadmore .content").text("No more post.");
						jQuery("#"+kjg_id+" .kjg-loadmore .content").removeClass("loading");
						jQuery("#"+kjg_id+" .kjg-loadmore .content").addClass("no-post");
					}
				else if(has_post=="yes")
					{
						
						jQuery.ajax(
							{
						type: 'POST',
						url: kento_justified_image_gallery_ajax.kento_justified_image_gallery_ajaxurl,
						data: {"action": "kento_justified_image_gallery_ajax","posttype":posttype,"offset":offset,"kjg_id":kjg_id},
						success: function(data)
								{	

									jQuery("#"+kjg_id+" .kento-justified-gallery-girds").append(data);

									jQuery("#"+kjg_id+" .kjg-loadmore .content").removeClass("loading");
									jQuery("#"+kjg_id+" .kjg-loadmore .content").html("Load More...");
									var offest_last = parseInt(offset+per_page);
									jQuery("#"+kjg_id+" .kjg-loadmore").attr("offset",offest_last);
									

	
									
								}
							});
					
					}					

				
				
				  
		});



















		
	});