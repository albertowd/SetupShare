var rowTempalte = "<div class=\"{color} py-3 setup row\"><div class=\"col-6 col-md-2 driver\">{driver}</div><div class=\"col-6 col-md-2 name\">{name}</div><div class=\"col-6 col-md-2 track\">{track}</div><div class=\"col-6 col-md-2 car\">{car}</div><div class=\"col-6 col-md-2\"></div><div class=\"col-6 col-md-2\"><a class=\"btn btn-danger download w-100\" onclick=\"downloadSetup({setup})\">Download</a></div></div>";
var setupList = [];

/**
 * Makes the download of the ini and sp files of a setup.
 * 
 * @param setup
 *            Setup infos.
 * @returns
 */
function downloadSetup(setup) {
	var link = document.createElement("a");
	link.style.display = "none";

	document.body.appendChild(link);

	link.setAttribute("download", setup.name + ".ini");
	link.setAttribute("href", makeUrl(setup, "ini"));
	link.click();

	link.setAttribute("download", setup.name + ".sp");
	link.setAttribute("href", makeUrl(setup, "sp"));
	link.click();

	document.body.removeChild(link);
}

/**
 * Filter the setup list with the users entries.
 * 
 * @returns
 */
function filterList() {
	var car = $("#car").val();
	var driver = $("#driver").val();
	var name = $("#name").val();
	var track = $("#track").val();

	var rows = $(".setup")
	$.each(rows,
			function(index, row) {
				var visible = true;
				if (car.length > 0) {
					visible = visible
							&& $(row).find(".car").text().includes(car);
				}
				if (driver.length > 0) {
					visible = visible
							&& $(row).find(".driver").text().includes(driver);
				}
				if (name.length > 0) {
					visible = visible
							&& $(row).find(".name").text().includes(name);
				}
				if (track.length > 0) {
					visible = visible
							&& $(row).find(".track").text().includes(track);
				}
				$(row).toggleClass("d-none", !visible);
			});
}

/**
 * Make a download url.
 * 
 * @param setup
 *            Setup infos.
 * @param extension
 *            Extension of the file (ini or sp).
 * @returns The encoded URL.
 */
function makeUrl(setup, extension) {
	var url = "setups/setupshare.php?service=download&car={car}&driver={driver}&ext={ext}&name={name}&track={track}";
	url = url.replace("{driver}", setup.driver);
	url = url.replace("{ext}", extension);
	url = url.replace("{name}", setup.name);
	url = url.replace("{track}", setup.track);
	return url.replace("{car}", setup.car);
}

/**
 * Refresh the setup list requesting all the setups to the server.
 * 
 * @returns
 */
function loadList() {
	$.ajax("setups/setupshare.php?service=list").done(function(data) {
		setupList = JSON.parse(data);
		updateList();
	}).fail(function() {
		alert("Sorry, it wasn't possible to load any setup.");
	}).always(function() {
		$("#search").prop("disabled", false);
	});
}

/**
 * Updates the list contents with new setups.
 * 
 * @returns
 */
function updateList() {
	var rows = $("#rows");
	rows.empty();

	$.each(setupList, function(index, setup) {
		var row = rowTempalte.replace("{driver}", setup.driver).replace(
				"{driver}", setup.driver);
		row = row.replace("{name}", setup.name).replace("{name}", setup.name)
				.replace("{name}", setup.name);
		row = row.replace("{track}", setup.track).replace("{track}",
				setup.track);
		row = row.replace("{car}", setup.car).replace("{car}", setup.car);
		row = row.replace("{color}", index % 2 == 1 ? "even" : "");
		row = row.replace("{setup}", "window.setupList[" + index + "]");
		rows.append(row);
	});
}

$(document).ready(function() {
	loadList();
});