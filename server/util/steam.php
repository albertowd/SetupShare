<?php
require_once __DIR__ . "/includes.php";

class SteamAPI
{
    private static $key = "5D53DBE3F1ABEAD506F36B8B20D832E9";

    /**
     * Returns a list of friends ids from steam api.
     *
     * @param int $steamId Steam user identifier.
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
}

if (isTest()) {
    debug("Getting steam friends...");
    debug(SteamAPI::getFriendIds(76561197991651936));
}
