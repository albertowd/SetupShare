var rowTempalte = "<div class=\"{color} py-3 setup row\"><div class=\"col-6 col-md-2 driver\">{driver}</div><div class=\"col-6 col-md-2 name\">{name}</div><div class=\"col-6 col-md-2 track\">{track}</div><div class=\"col-6 col-md-2 car\">{car}</div><div class=\"col-6 col-md-1\"></div>{ac_version}<div class=\"col-6 col-md-1\">{version}</div><div class=\"col-6 col-md-2\"><a class=\"btn btn-danger download w-100\" onclick=\"downloadSetup({setup})\">Download</a></div></div>";
var setupList = [];

/**
 * Makes the download of the ini and sp files of a setup.
 * 
 * @param setup
 *            Setup infos.
 */
function downloadSetup(setup) {
	var link = document.createElement("a");
	link.style.display = "none";

	document.body.appendChild(link);

	link.setAttribute("download", setup.name + ".ini");
	link.setAttribute("href", makeUrl(setup.id, "ini"));
	link.click();

	link.setAttribute("download", setup.name + ".sp");
	link.setAttribute("href", makeUrl(setup.id, "sp"));
	link.click();

	document.body.removeChild(link);
}

/**
 * Make a download url.
 * 
 * @param id
 *            Setup identifier.
 * @param extension
 *            Extension of the file (ini or sp).
 * @returns The encoded URL.
 */
function makeUrl(id, extension) {
	return "api/download.php?ext=" + ext + "&id=" + id;
}

/**
 * Refresh the setup list requesting all the setups to the server.
 */
function loadList() {
	$("#mask").toggleClass("d-none", false);
	var filter = "car=" + $("#car").val();
	filter = filter + "driver=" + $("#driver").val();
	filter = filter + "name=" + $("#name").val();
	filter = filter + "track=" + $("#track").val();
	$.ajax("api/list.php?" + filter).done(function (data) {
		setupList = data;
		updateList();
	}).fail(function () {
		alert("Sorry, it wasn't possible to load any setup.");
	}).always(function () {
		$("#mask").toggleClass("d-none", true);
		$("#search").prop("disabled", false);
	});
}

/**
 * Updates the list contents with new setups.
 */
function updateList() {
	$(".setup").remove();
	$("#empty").toggleClass("d-none", setupList.length > 0);
	$.each(setupList, function (index, setup) {
		var row = rowTempalte.replace("{ac_version}", setup.ac_version).replace(
			"{version}", setup.version);
		row = row.replace("{driver}", setup.driver).replace(
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

$(document).ready(function () {
	loadList();
});