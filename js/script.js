$(document).ready(function(){

	//submit button click makes an ajax call to functions/weatherService.php
	//the called web service then calls the yahoo weather api and processes the data before sending it back
	$(document).on("click", "#citySelectSubmit", function(){
		var weatherContent = document.getElementById('weatherContent');
		var navbar = document.getElementById('navbarLinks');
		weatherContent.innerHTML = '';
		navbar.innerHTML = '';
		$.ajax({
			type: "POST",
			data: $('#citySelectForm').serialize(),
			url: "functions/weatherService.php",
			success: function(data){
				var result = $.parseJSON(data);
				if(result["total"]>0){
					$(result["weather"]).appendTo(weatherContent);
					$(result["navbar"]).appendTo(navbar);
					refreshScrollSpy();
					for(x=0;x<result["total"];x++){
						latLong=result["latLong"];
						latitude = latLong[x].latitude;
						longitude = latLong[x].longitude;
						showPosition(latitude,longitude, x);//function call to build a map
						$("#navbarList").removeClass("displayNone");
					}
				}
				else{
					alert("There was an error processing your request. Please check your selection and try again.")
				}
			}
		});
	});
	//Showposition builds a map using the latitude and longitude provided by Yahoo Weather
	//The map is then added to according div
	function showPosition(latitude, longitude, x) {
		var latlon = latitude + "," + longitude;
		var img_url = "http://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=7&size=400x400&sensor=false";

		document.getElementById("map_"+x).innerHTML = "<img class='img-rounded img-responsive' src='"+img_url+"' style='width-max:90%;'>";
	}
	function refreshScrollSpy() {
		$('[data-spy="scroll"]').each(function () {
			$(this).scrollspy('refresh');
		}); 
	};
	
	//On navbar link click this will change the active state of the button and remove the current view and display the selected city
	$(document).on("click", ".navbarSelected", function(){
		var selectView = $(this).attr('view');
		$('#navbarLinks .active').removeClass('active');
		$(this).parent('li').addClass('active');
		$('#weatherContent .active').addClass('displayNone');
		$("#"+selectView).removeClass("displayNone");
		$("#"+selectView).addClass("active");
		
	});
	
});