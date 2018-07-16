var rowTempalte = "<div class=\"{color} py-3 setup row\">";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2 driver\" title=\":dtitle\">:driver</div>";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2 name\" title=\":ntitle\">:name</div>";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2 track\" title=\":ttitle\">:track</div>";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2 car\" title=\":ctitle\">:car</div>";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2\" title=\":vtitle\">:ver</div>";
rowTempalte = rowTempalte + "<div class=\"col-6 col-md-2\"><a class=\"btn btn-danger download w-100\" onclick=\"downloadSetup(:id,:sp)\">Download</a></div></div>";

/**
 * Makes the download of the ini and sp files of a setup.
 * 
 * @param id
 *            Setup identifier.
 */
function downloadSetup(id, sp) {
	var link = document.createElement("a");
	link.style.display = "none";
	document.body.appendChild(link);
	link.setAttribute("download", true);
	link.setAttribute("href", makeUrl(id, "ini"));
	link.click();
	if (sp) {
		link.setAttribute("href", makeUrl(id, "sp"));
		link.click();
	}
	document.body.removeChild(link);
}

/**
 * Make a download url.
 * 
 * @param id
 *            Setup identifier.
 * @param ext
 *            Extension of the file (ini or sp).
 * @returns The encoded URL.
 */
function makeUrl(id, ext) {
	return "api/download.php?ext=" + ext + "&id=" + id;
}

/**
 * Refresh the setup list requesting all the setups to the server.
 */
function loadList() {
	$("#mask").toggleClass("d-none", false);
	var filter = "car=" + $("#car").val();
	filter += "&driver=" + $("#driver").val();
	filter += "&name=" + $("#name").val();
	filter += "&track=" + $("#track").val();
	$.ajax("api/list.php?" + filter + "&" + window.location.search.substring(1)).done(function (data) {
		updateList(data);
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
function updateList(setupList) {
	$(".setup").remove();
	$("#empty").toggleClass("d-none", setupList.length > 0);

	var rows = $("#rows");
	$.each(setupList, function (index, setup) {
		var title = "AC Version: " + setup.ac_version;
		if (setup.sp) {
			var pit = "<img alt=\"Has pit strategy\" src=\"img/pit.png\"/>";
			title += "\nPit strategy: yes";
		} else {
			var pit = "";
			title += "\nPit strategy: no";
		}

		if (setup.visibility == 0) {
			var visibility = "<img alt=\"public\" src=\"img/public.png\"/>";
			title += "\nVisibility: public";
		} else if (setup.visibility == 1) {
			var visibility = "<img alt=\"friends only\" src=\"img/protected.png\"/>";
			title += "\nVisibility: friends only";
		} else if (setup.visibility == 2) {
			var visibility = "<img alt=\"private\" src=\"img/private.png\"/>";
			title += "\nVisibility: private";
		}

		title += "\nUploaded: " + new Date(setup.version_ts).toLocaleString();;

		var row = rowTempalte.replace(":car", setup.car).replace(":ctitle", setup.car);
		row = row.replace(":color", index % 2 == 1 ? "even" : "");
		row = row.replace(":driver", setup.driver).replace(":dtitle", setup.driver);
		row = row.replace(":id", setup.id);
		row = row.replace(":name", setup.name).replace(":ntitle", setup.name);
		row = row.replace(":sp", setup.sp);
		row = row.replace(":track", setup.track).replace(":ttitle", setup.track);
		row = row.replace(":ver", visibility + pit + " v" + setup.version.toString());
		row = row.replace(":vtitle", title);
		rows.append($.parseHTML(row));
	});
}

$(document).ready(function () {
	loadList();
});