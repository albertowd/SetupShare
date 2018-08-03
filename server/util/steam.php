<?php
require_once __DIR__ . "/openid.php";
require_once __DIR__ . "/util.php";

class SteamAPI
{
    private static $key = "5D53DBE3F1ABEAD506F36B8B20D832E9";

    /**
     * Returns the auth url to use.
     *
     * @return string
     */
    public static function getAuthUrl()
    {
        $openid = new LightOpenID("albertowd.com.br");
        $openid->identity = "https://steamcommunity.com/openid";
        return $openid->authUrl();
    }

    /**
     * Returns a list of friends Steam ID64 from the api.
     *
     * @param int $steamId Steam user ID64.
     */
    public static function getFriendIds(int $steamId)
    {
        $friendIds = array();

        // Requesting the friend list.
        $key = self::$key;
        $ch = curl_init("http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=$key&steamid=$steamId&relationship=friend");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Parsing the list.
        $list = json_decode(curl_exec($ch));
        if (isset($list->friendslist) && isset($list->friendslist->friends)) {
            $list = $list->friendslist->friends;
            foreach ($list as $friend) {
                array_push($friendIds, $friend->steamid);
            }
        }
        curl_close($ch);

        return $friendIds;
    }

    /**
     * Get the login Steam ID64.
     *
     * @return int
     */
    public static function getId()
    {
        $openid = new LightOpenID("albertowd.com.br");
        $login = "";

        if (!$openid->mode) {
            return 0;
        } elseif ($openid->mode != "cancel" && $openid->validate()) {
            return intval(end(explode("/", $openid->identity)));
        }
    }

    /**
     * Get user infos.
     */
    public static function getUser(int $steamId)
    {
        $user = null;

        // Requesting the user info.
        $key = self::$key;
        $ch = curl_init("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$key&steamids=$steamId");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Parsing the avatar.
        $info = json_decode(curl_exec($ch));
        if (isset($info->response) && isset($info->response->players) && count($info->response->players) > 0) {
            $user = reset($info->response->players);
        }
        curl_close($ch);

        return $user;
    }
}

if (isTest()) {
    debug("Getting steam friends...");
    debug(SteamAPI::getFriendIds(76561197991651936));
}
