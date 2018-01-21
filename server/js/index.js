var rowTempalte = "<div class=\"{color} py-3 setup row\"><div class=\"col-6 col-md-2 driver\">{driver}</div><div class=\"col-6 col-md-2 name\">{name}</div><div class=\"col-6 col-md-2 track\">{track}</div><div class=\"col-6 col-md-2 car\">{car}</div><div class=\"col-6 col-md-2\"></div><div class=\"col-6 col-md-2\"><a class=\"btn btn-danger download w-100\" href=\"setups/setupshare.php?car={car}&driver={driver}&name={name}&track={track}&download=true\" download=\"{name}.ini\">Download</a></div></div>";

function filterList() {
    var car = $("#car").val();
    var driver = $("#driver").val();
    var name = $("#name").val();
    var track = $("#track").val();

    var rows = $(".setup")
    $.each(rows, function (index, row) {
        var visible = true;
        if(car.length > 0) {
            visible = visible && $(row).find(".car").text().includes(car);
        };
        if(driver.length > 0) {
            visible = visible && $(row).find(".driver").text().includes(driver);
        };
        if(name.length > 0) {
            visible = visible && $(row).find(".name").text().includes(name);
        };
        if(track.length > 0) {
            visible = visible && $(row).find(".track").text().includes(track);
        };
        $(row).toggleClass( "d-none", !visible );
    });
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
            $("#search").prop("disabled", false);
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