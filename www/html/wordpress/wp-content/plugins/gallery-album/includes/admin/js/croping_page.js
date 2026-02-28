	var wpda_gall_crop_z = 0;
	wpda_gall_click_recrop_Button();
	function wpda_gall_click_recrop_Button(){
	
		jQuery("#wpda_gall_recrop_image").click(function(){
			if(wpda_gall_crop_z == 0){	
				var r = confirm("Are You want recrop all gallery images?");
				if (r == true) {
					document.getElementById("wpda_gall_myProgress").style.backgroundColor = "#ddd";
					wpda_gall_clickButton();
				} else {
					return false;
				}
			}			
		});	
	}
	function wpda_gall_clickButton() {

				jQuery.ajax({
					url: wpda_gall_admin_ajax_url + '?action=wpda_gall_recrop_image&recrop_image=' + wpda_gall_crop_z,
				}).done(function(date) {															
					wpda_gall_move(date);
				})		
	}
	function wpda_gall_move(length) {
		var elem = document.getElementById("wpda_gall_myBar");   
		elem.style.width = (wpda_gall_crop_z + 1) * 100 / length + '%';	  
		if(wpda_gall_crop_z < length - 1){
			document.getElementById("wpda_gall_myBar").innerHTML = wpda_gall_crop_z + 1 + '/' + length;
					wpda_gall_crop_z++;
					wpda_gall_clickButton();			
		}else{
			document.getElementById("wpda_gall_myBar").innerHTML = "Cropping images completed successfully :count images=" + length;	
		}
	}