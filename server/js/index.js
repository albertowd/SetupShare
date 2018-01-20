var rowTempalte = "<div class=\"row {color} py-3\"><div class=\"col-6 col-md-2\">{driver}</div><div class=\"col-6 col-md-2\">{name}</div><div class=\"col-6 col-md-2\">{track}</div><div class=\"col-6 col-md-2\">{car}</div><div class=\"col-6 col-md-2\"></div><div class=\"col-6 col-md-2\"><a class=\"btn btn-info w-100\" href=\"setups/setupshare.php?car={car}&driver={driver}&name={name}&track={track}&download=true\" download=\"{name}.ini\">Download</a></div></div>";

function filterList() {

}

function loadList() {
    $.ajax("setups/setupshare.php")
        .done(function (data) {
            updateList(JSON.parse(data));
        })
        .fail(function () {
            alert("Sorry, it wasn't possible to load any setup.");
        })
        .always(function () {
            //$("#search").prop("disabled", false);
        });
}

function updateList(setups) {
    var rows = $("#rows");
    $.each(setups, function (index, setup) {
        var row = rowTempalte.replace("{driver}", setup.driver).replace("{driver}", setup.driver);
        row = row.replace("{name}", setup.name).replace("{name}", setup.name).replace("{name}", setup.name);
        row = row.replace("{track}", setup.track).replace("{track}", setup.track);
        row = row.replace("{car}", setup.car).replace("{car}", setup.car);
        row = row.replace("{color}", index % 2 == 1 ? "even" : "" );
        rows.append(row);
    });
}

$(document).ready(function () {
    loadList();
});