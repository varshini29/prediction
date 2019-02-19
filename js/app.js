(function ($, document, window) {

	$(document).ready(function () {

		// Cloning main navigation for mobile menu
		$(".mobile-navigation").append($(".main-navigation .menu").clone());

		// Mobile menu toggle 
		$(".menu-toggle").click(function () {
			$(".mobile-navigation").slideToggle();
		});

		var map = $(".map");
		var latitude = map.data("latitude");
		var longitude = map.data("longitude");
		if (map.length) {

			map.gmap3({
				map: {
					options: {
						center: [latitude, longitude],
						zoom: 15,
						scrollwheel: false
					}
				},
				marker: {
					latLng: [latitude, longitude],
				}
			});

		}
	});

	$(window).load(function () {

	});

})(jQuery, document, window);

//slideshow
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
	showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("dot");
	if (n > slides.length) {
		slideIndex = 1
	}
	if (n < 1) {
		slideIndex = slides.length
	}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex - 1].style.display = "block";
	dots[slideIndex - 1].className += " active";
}
var displaydate = function () {
	var x = new Date()

	var x1 = x.getDate() + "/" + x.getMonth() + 1 + "/" + x.getFullYear();
	x1 = x1 + " - " + x.getHours() + ":" + ("0" + x.getMinutes()).substr(-2);
	document.getElementById('ct').innerHTML = x1;
	refreshtime();
}();

function refreshtime() {
	setTimeout(displaydate, 60*1000);
}

function displayDate(id) {
	var date = new Date();
	let year = date.getFullYear();
	let month = date.getMonth();
	let months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	let d = date.getDate();
	let day = date.getDay();
	let days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

	let br = document.createElement('br');
	
	let h = date.getHours();
	if (h < 10) {
		h = "0" + h;
	}

	let m = date.getMinutes();
	if (m < 10) {
		m = "0" + m;
	}

	let s = date.getSeconds();
	if (s < 10) {
		s = "0" + s;
	}

	//let result = ''+ days[day]+ ' ' + months[month] + ' ' + d + ' ' + year + ' ' + h + ':' + m + ':' + s;
	let result = ''+ days[day] + "<br/>" + months[month] + ', ' + d + ' ' + year + "<br/>" + h + ':' + m + ':' + s;
	document.getElementById(id).innerHTML = result;
	setTimeout('displayDate("'+id+'");','1000');
}