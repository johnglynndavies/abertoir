jQuery(function($){ 
	$(window).load(function(){
		var totalWidth = 0;
		var imageWidth = $(".navigation-button").outerWidth();
		var imageWidth = imageWidth + 2;
		var newPosition = 0;
		$(".navigation-button").each(function(){
		 	totalWidth = totalWidth + imageWidth;
		});
			
		var maxScrollPosition = totalWidth - imageWidth;
		
		//console.log('imageWidth:' + imageWidth);
		//console.log('totalWidth:' + totalWidth);
		//console.log('maxScrollPosition:' + maxScrollPosition);
		
		function toGalleryItem($targetItem, $thisItem){
	        // Make sure the target item exists, otherwise do nothing
			
			if($targetItem.length){
				
	            // The new position is just to the left of the targetItem
	            newPosition = parseInt($targetItem.position().left);
				//console.log('newPosition:' + newPosition);

	            // If the new position isn't greater than the maximum width
	            if(newPosition <= maxScrollPosition){
	                // Animate .gallery element to the correct left position.
	                $(".navigation-wrapper").animate({
	                    left : - newPosition + imageWidth
	                });
				}  else {
	                // Animate .gallery element to the correct left position.
	                $(".navigation-wrapper").animate({
	                    left : - maxScrollPosition
	                });
	            };
	        };	
	    };
		
		$(".navigation-wrapper").width(totalWidth);
		$(".navigation-button:first").addClass("active");
		
		$(".navigation-previous").click(function(){
			var left = parseInt($('.navigation-wrapper').css('left'));
			//console.log('left position:' + left);
	        // If the navigation wrapper is already at the beginning move to the end
			if (left == 0) {
                $(".navigation-wrapper").animate({
                    left : - maxScrollPosition
                });
			} else {
				var $targetItem = $(".navigation-button.active").next();
		        toGalleryItem($targetItem);  
			}
	    });
		
		$(".navigation-next").click(function(){
	        // Set target item to the item after the active item
	        var $targetItem = $(".navigation-button.active").next();
	        toGalleryItem($targetItem);
	    });

	});
});