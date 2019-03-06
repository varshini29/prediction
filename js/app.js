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
function displayDate() {
	var x = new Date()

	var x1 = x.getDate() + "/" + x.getMonth() + 1 + "/" + x.getFullYear();
	x1 = x1 + " - " + x.getHours() + ":" + ("0" + x.getMinutes()).substr(-2);
	document.getElementById('ct').innerHTML = x1;
	//refreshtime();
	let time = setTimeout(displaydate, 500);
}

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

	let result = ''+ days[day] + "<br/>" + months[month] + ', ' + d + ' ' + year + "<br/>" + h + ':' + m + ':' + s;
	document.getElementById(id).innerHTML = result;
	setTimeout('displayDate("'+id+'");','1000');
}

function generateChart(chart, times, rainfalls, waterlevels) {
	var ctx = document.getElementById(chart).getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: times,
			datasets: [
			{
				label: 'Rainfall Intensity',
				data: rainfalls,
				backgroundColor: 'transparent',
				borderColor:'rgba(255,99,132)',
				borderWidth: 3
			},
			{
				label: 'Water Level',
				data: waterlevels,
				backgroundColor: 'transparent',
				borderColor:'rgba(0,255,255)',
				borderWidth: 3
			}
			]
		},
		options: {
			scales: {scales: { 
				yAxes: [{ beginAtZero: false }],
				xAxes: [{ autoskip: true, maxTicketsLimit: 20 }]
			}},
			tooltips: { mode: 'index' },
			responsive: true,
			legend: { display: true, position: 'top', labels: { fontColor: 'rgb(255,255,255)', fontSize: 16 } }
		}
	})
	return myChart;
};