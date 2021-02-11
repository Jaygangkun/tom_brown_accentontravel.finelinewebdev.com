<?	
class resizeImage 
{
	function resize($sourcefile, $destfile, $forcedwidth, $forcedheight, $imgcomp=0)  
		{
			// set the quality, 0 best quality | 100 worst quality
			$img_quality = 100-$imgcomp;
			if(file_exists($sourcefile)) 
			{
				$image_size = getimagesize($sourcefile);
				$src_width = $image_size[0];
				$src_height = $image_size[1];
				$src_type = $image_size[2]; // 1 = GIF, 2 = JPG, 3 = PNG
				
				if(($src_width < $forcedwidth) && ($src_height < $forcedheight))
				{
					$new_width = $src_width;
					$new_height = $src_height;
				}
				
				else if(($src_width/$forcedwidth)>=($src_height/$forcedheight)) 
				{
					$new_width = $forcedwidth;
					$new_height = ($forcedwidth/$src_width)*$src_height;
				}
				//otherwise do as commented above but swap width for height
				else 
				{
					$new_height = $forcedheight;
					$new_width = ($new_height/$src_height)*$src_width;   
				}
				
				//create an image resource from the source file, this function will return $a, which
				//will become $img_src
				$img_src = $this->img_create_from_file($sourcefile, $src_type);
				
				//create an image resource for the destination file, use the new width and height
				//caculated above, returns $a, which will become $img_dst
				$img_dst = $this->img_create($new_width, $new_height, $src_type);
				
				imagecopyresampled($img_dst, $img_src, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
				//destination img resource, source file path, img composition, file type
				//this function will put the dest image resouce into the dest file path
				$this->img_output($img_dst, $destfile, $img_quality, $src_type);
				
				imagedestroy($img_dst);
				
				$new_size_array = array();
				$new_size_array['width'] = $new_width;
				$new_size_array['height'] = $new_height;
				return $new_size_array;
			}
			else
			return false;
		}
		
		function img_create_from_file($sourcefile, $src_type) 
		{
			ini_set('memory_limit', '32M');
			switch ($src_type) 
			{
				case 1: // for gif
					$a = imagecreatefromgif($sourcefile);
					return $a;
				case 2: // for jpeg
					$a = imagecreatefromjpeg($sourcefile);
					return $a;
				case 3: // for png
					$a = imagecreatefrompng($sourcefile);
					return $a;
			}	
		}
		
		function img_create($new_width, $new_height, $src_type) 
		{
			switch ($src_type) 
			{
				case 1: // for gif
					$a = imagecreate($new_width, $new_height);
					return $a;
				case (2 || 3): // for jpeg and png
					$a = imagecreatetruecolor($new_width, $new_height);
					return $a;
			}		
		}
		
		function img_output($img_dst, $destfile, $img_quality, $src_type) 
		{
			switch ($src_type) 
			{
				case 1: // for gif
					imagegif($img_dst, $destfile); // for gif
					break;
				case 2: // for jpeg
					imagejpeg($img_dst, $destfile, $img_quality); // for jpeg
					break;
				case 3: // for png
					$img_quality /= 10;
					if($img_quality > 9) $img_quality = 9;
					imagepng($img_dst, $destfile, $img_quality); // for png
					break;
			}		
		}
	  
	}
?>