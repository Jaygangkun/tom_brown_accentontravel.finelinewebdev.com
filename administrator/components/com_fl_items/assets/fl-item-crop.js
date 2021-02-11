var image = jQuery('#crop-canvas');
var newFileName = '';
var propName = '';
var fileType = '';
var dimX = 0;
var dimY = 0;
var maxX = 0;
var maxY = 0;
var cropper;

jQuery(document).ready(function() {
	image = jQuery('#crop-canvas');
	image.cropper({
  		aspectRatio: 1,
  		viewMode: 1,
  		autoCropArea: 1
	});
	
	cropper = image.data('cropper');
	
	jQuery('#crop-save').click(function() {
        var canvas;
        var isGallery = false;
        if(propName == "new_filename_1") {
        	jQuery('#'+propName).val('');
        	isGallery = true;
        }
        jQuery('#file-'+propName).val('');

      	jQuery('.crop-wrapper').hide();
      	jQuery(".image-progress").show();
      	
      	var running = true;
      	
      	setTimeout(function() {
      		if(running) {
	  			jQuery(".image-progress .stripesLoader").addClass("compressing");
	  			jQuery(".image-progress h4").html("Compressing");
  			}
      	}, 2000);

        if (cropper) {
        	if(dimX > 0 && dimY > 0) {
	          	canvas = cropper.getCroppedCanvas({
	            	width: dimX,
	            	height: dimY
	          	});
         	} else {
	         	canvas = cropper.getCroppedCanvas({
	            	maxWidth: maxX,
	            	maxHeight: maxY
	          	});
         	}
          	jQuery('#thumb-'+propName).attr('src', canvas.toDataURL());
          	canvas.toBlob(function (blob) {
            	var formData = new FormData();

            	formData.append('newimg', blob, newFileName);
            	jQuery.ajax("/administrator/index.php?option=com_fl_items&c=item&task=ajaximg&property="+propName+"&item_id="+jsItemId, {
              		method: 'POST',
              		data: formData,
              		processData: false,
              		contentType: false,
              		success: function (data) {
              			running = false;
              			jQuery(".image-progress").hide();
              			jQuery(".image-progress .stripesLoader").removeClass("compressing");
              			jQuery(".image-progress h4").html("Processing");
              			if(!isGallery) {
		                	if(data === "done") {
		                		jQuery('#img-alert-'+propName).html('<div class="alert alert-success">Image Saved!</div>');
		                	} else {
		                		jQuery('#img-alert-'+propName).html('<div class="alert alert-danger">Image Upload Failed!</div>');
		                	}
	                	} else {
	                		if(isNaN(data)) {
	                			//errrrrrrrrrrrrrr
	                		} else {
	                			jQuery('#gallery-alert').html('<div class="alert alert-success">Image Added!</div>');
	                			var dragDropHtml = '<li id="image_li_'+data+'" data-imgid="'+data+'" class="ui-state-default ui-sortable-handle"><img src="'+canvas.toDataURL()+'" style="width: 100%; height: 100%; object-fit: contain;"><div class="img-del"><img title="Click to Delete" src="/administrator/components/com_fl_items/assets/icon-delete.png"></div><div class="img-pub"><img title="Click to Unpublish" src="/administrator/components/com_fl_items/assets/icon-publish.png"></div><input name="delete_gallery_image_'+data+'" id="delete_gallery_image_'+data+'" type="hidden" value="0"><input name="showImage_'+data+'" id="showImage_'+data+'" type="hidden" value="1"></li>';
	                			jQuery("ul#sortable").append(dragDropHtml);
	                			if(jsIsSingleImage) {
	                				jQuery(".upload-images").hide();
	                			}
	                		}
	                	}
	              	}
            	});
          	}, fileType, 0.95);
    	}
	});
	jQuery('#crop-cancel').click(function() {
		jQuery('#file-'+propName).val('');
		jQuery('.crop-wrapper').hide();
	});
	jQuery('body').on(
	    'dragend',
	    function(e) {
	    	jQuery('.img-upload-wrap').removeClass('hover').removeClass('on');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('body').on(
	    'dragexit',
	    function(e) {
	    	jQuery('.img-upload-wrap').removeClass('hover').removeClass('on');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('body').on(
	    'dragleave',
	    function(e) {
	    	jQuery('.img-upload-wrap').removeClass('hover').removeClass('on');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('body').on(
	    'dragover',
	    function(e) {
	    	jQuery('.img-upload-wrap').addClass('hover');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('body').on(
	    'dragenter',
	    function(e) {
	    	jQuery('.img-upload-wrap').addClass('hover');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('.img-upload-wrap').on(
	    'dragover',
	    function(e) {
	    	jQuery('.img-upload-wrap').addClass('hover');
	    	jQuery(this).addClass('on');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
	jQuery('.img-upload-wrap').on(
	    'dragenter',
	    function(e) {
	    	jQuery('.img-upload-wrap').addClass('hover');
	    	jQuery(this).addClass('on');
	        e.preventDefault();
	        e.stopPropagation();
	    }
	)
});

function setDimension(x = 0, y = 0, newMaxX = 0, newMaxY = 0) {
	if(newMaxX == 0 || newMaxY == 0 ) {
		dimX = x;
		dimY = y;
	} else {
		dimX = 0;
		dimY = 0;
		maxX = newMaxX;
		maxY = newMaxY;
	}
}

function readFile(input) {
	jQuery(".img-upload-wrap").removeClass("hover");
	jQuery(".img-alert").html("");
	propName = input.name;
	propName = propName.replace("file-", "");
	if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
        	var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
        	if(jQuery.inArray(input.files[0].type, validImageTypes) < 0) {
            	alert("You must upload a valid image!");
            	jQuery('.crop-wrapper').hide();
            } else {
            	if(dimX > 0 && dimY > 0) {
            		cropper.setAspectRatio(dimX / dimY);
            	} else {
            		cropper.setAspectRatio(0);
            	}
            	cropper.replace(e.target.result);
            	newFileName = input.files[0].name;
            	fileType = input.files[0].type;
            } 
        }
        
        reader.readAsDataURL(input.files[0]);
        jQuery('.crop-wrapper').show();
    }
    else {
        swal('Sorry - your browser does not support the FileReader API');
    }
}
