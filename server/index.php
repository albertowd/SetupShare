<?php
require_once __DIR__ . "/util/steam.php";

/**
 * Check if it's a login callback.
 */
$steamId = SteamAPI::getId();
if (!isset($_REQUEST["id"]) && $steamId > 0) {
    header("Location: http://localhost/albertowd.com.br/setupshare/?id=$steamId");
    die();
}

/**
 * Put the login or the logged steam profile button.
 */
if (!isset($_REQUEST["id"])) {
    $login = "&nbsp&nbsp&nbsp<a href=\"" . SteamAPI::getAuthUrl() . "\"><img alt=\"Login on steam.\" src=\"img/steam_login.png\"/></a>";
} else {
    $user = SteamAPI::getUser(intval($_REQUEST["id"]));
    if ($user != null) {
        $login = "<a class=\"btn btn-danger\" href=\"$user->profileurl\"><img alt=\"Logged avatar.\" height=\"20px\" src=\"$user->avatar\"/> $user->realname.</a>";
    } else {
        $login = "";
    }
}

?>

<!DOCTYPE html>
<html lang="en_US">

<head>
    <link href="css/bootstrap-4.0.0.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link rel="icon" href="img/Setup Share_ON.png">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="Alberto Wollmann Dietrich">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Facebook tags. -->
    <meta property="og:description" content="A simple App for sharing setups within Assetto Corsa sessions." />
    <meta property="og:image" content="http://albertowd.com.br/setupshare/img/facebook-meta.png" />
    <meta property="og:image:width" content="1201" />
    <meta property="og:image:height" content="630" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="Setup Share" />
    <meta property="og:title" content="Setup Share" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://albertowd.com.br/setupshare/index.php" />
    <title>Setup Share</title>
</head>

<body>
    <div class="container p-5 text-center">
        <h1>
            <img src="img/Setup Share_ON.png" width="48px" /> Setup Share
        </h1>
        <p>A simple App for sharing setups within Assetto Corsa sessions.
        </p>
        <div class="row">
            <div class="col-12 col-md-6 py-3">
                <a class="btn btn-danger" href="https://github.com/albertowd/SetupShare">Download App</a>
            </div>
            <div class="col-12 col-md-6 py-3"><?php echo $login; ?></div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row py-3">
            <div class="col-6 col-md-2">
                <input type="text" class="form-control" id="driver" placeholder="Driver">
            </div>
            <div class="col-6 col-md-2">
                <input type="text" class="form-control" id="name" placeholder="Setup">
            </div>
            <div class="col-6 col-md-2">
                <input type="text" class="form-control" id="track" placeholder="Track">
            </div>
            <div class="col-6 col-md-2">
                <input type="text" class="form-control" id="car" placeholder="Car">
            </div>
            <div class="col-6 col-md-2">
                <label>Info</label>
            </div>
            <div class="col-12 col-md-2">
                <button class="btn btn-danger search w-100" disabled id="search" onclick="loadList()">Search</button>
            </div>
        </div>
    </div>
    <div class="container-fluid" id="rows">
        <div class="container text-center" id="mask"></div>
        <div class="container d-none text-center" id="empty">
            No setup found.
        </div>
    </div>
    <footer class="text-center">v1.2</footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper-1.12.9.min.js"></script>
    <script src="js/bootstrap-4.0.0.min.js"></script>
    <script src="js/index.js"></script>
</body>

</html>
