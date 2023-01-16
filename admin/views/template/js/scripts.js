jQuery(document).ready(function ($) {
	// Filter
	$("#searchInput").keyup(function () {
		// Split the current value of input
		var data = this.value.split(" ");
		// Create a jquery object of the rows
		var jo = $("tbody").find("tr");
		if (this.value == "") {
			jo.show();
			$("#count").text(jo.length);
			return;
		}
		// Hide all the rows
		jo.hide();
		// Recusively filter the jquery object to get results
		var count = 0;
		// Show the rows that match
		jo.filter(function (i, v) {
			var $t = $(this);
			for (var d = 0; d < data.length; ++d) {
				if ($t.is(':contains("' + data[d] + '")')) {
					count++;
					return true;
				}
			}
			return false;
		}).show();
		// Update the counter
		$("#count").text(count);
	});

	// Remove case-sensitive from ':contains'
	$.expr[":"].contains = $.expr.createPseudo(function (arg) {
		return function (elem) {
			return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
	});

	// Delete
	$(".delete-item").click(function (e) {
		e.preventDefault();
		$("#deleteById").attr("href", $(this).attr("href"));
	});

	// Using cells like links
	$(".link").click(function () {
		window.location = $(this).parent().find(".btn-primary").attr("href");
	});
});

/*
 * Start Bootstrap - Simple Sidebar v6.0.5 (https://startbootstrap.com/template/simple-sidebar)
 * Copyright 2013-2022 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-simple-sidebar/blob/master/LICENSE)
 */
window.addEventListener("DOMContentLoaded", (event) => {
	const sidebarToggle = document.body.querySelector("#sidebarToggle");
	if (sidebarToggle) {
		// Uncomment Below to persist sidebar toggle between refreshes
		// if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
		//     document.body.classList.toggle('sb-sidenav-toggled');
		// }
		sidebarToggle.addEventListener("click", (event) => {
			event.preventDefault();
			document.body.classList.toggle("sb-sidenav-toggled");
			if (document.body.classList.contains("sb-sidenav-toggled") === true) {
				sidebarToggle.innerHTML = '<i class="bi bi-toggle2-off"></i>';
			} else {
				sidebarToggle.innerHTML = '<i class="bi bi-toggle2-on"></i>';
			}
			localStorage.setItem("sb|sidebar-toggle", document.body.classList.contains("sb-sidenav-toggled"));
		});
	}
});
