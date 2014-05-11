function showTrip(i) {
				var numTrips = numberOfTrips;
				activeTrip += i;
				if (activeTrip < 0) {
					activeTrip = 0;
					return;
				} else if (activeTrip > lastTrip) {
					activeTrip = lastTrip;
					return;
				}
				$("#trip_" + activeTrip).slideToggle();
				$("#trip_" + (activeTrip - i)).slideToggle();
			}
			
			$(document).ready(function() {
				$("#time").hide();
			});
		
			function getLocation() {
  				if (navigator.geolocation) {
    				navigator.geolocation.getCurrentPosition(gotPosition);
    			} else { 
  					$("#from-val").val("");
    				gotPosition("Platslokalisering stöds inte av denna webbläsare.");
    			}
    		}

			function gotPosition(position) {
				// Error handling
				if (typeof position == 'string' || position instanceof String) {
					alert(position);
					return;
				}
  				var latitude = position.coords.latitude;
  				var longitude = position.coords.longitude;
  				$("#from-text-box").val("Min plats"); 
  				$("#from-text-box").addClass("ok"); 
  				$("#from-val").val("GPS:" + latitude + ":" + longitude);
  			}
  			
			function goNowChanged() {
				if (!$("#go-now").prop("checked")) {
					$("#time").show();
				} else {
					$("#time").hide();
				}
			}
		
			function myPositionChanged() {
				if (!$("#my-position").prop("checked")) {
					$("#from-text-box").val("Från").removeClass("ok");
  					$("#from-val").val("");
				} else {
					getLocation();
				}
			}
			
			function fromFocus() {
				if ($("#from-text-box").val() == "Min plats") {
					$("#from-text-box").val("").removeClass("ok");
					$("#my-position").attr("checked", false);
				}
			}
			
			function validateForm() {
				var error = new Array();
			
				if (!$("#from-val").val()) { 
					error.push("Ange varifrån du vill åka.");
					$("#from-text-box").addClass('error');
    			} else {
					$("#from-text-box").removeClass('error');
    			}
    		
    			if (!$("#to-val").val()) { 
					error.push("Ange vart du vill åka.");
					$("#to-text-box").addClass('error');
    			} else {
					$("#to-text-box").removeClass('error');
   		 		}
    			if (error.length != 0) {
    				return false;
    			}
    			return true;
			}