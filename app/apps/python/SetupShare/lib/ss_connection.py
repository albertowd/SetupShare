#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Setup Share Connection utils.
"""
from json import dumps
from requests import get, post
import sys

SS_API_SERVER = "http://192.168.25.5/albertowd.com.br/setupshare/api"
STEAM_ID = 76561197991651936
VERSION = "1.2"

def combo_list(car, track):
    """ Gets the list os available setups of the car/track. """
    global SS_API_SERVER, STEAM_ID
    response = get("{}/list.php?app&car={}&track={}&id={}".format(SS_API_SERVER, car, track, STEAM_ID), timeout=5)
    return response.json() if response.status_code == 200 else []

def count_setups():
    global SS_API_SERVER, VERSION
    count = 0
    ret = get("{}/count.php?ver={}".format(SS_API_SERVER, VERSION), timeout=5)
    if ret.status_code == 200:
        count = int(ret.text)
    return count


def download(setup_id, ext="ini"):
    """ Downloads a specific setup. """
    global SS_API_SERVER, VERSION
    response = get("{}/download.php?id={}&ext={}&ver={}".format(SS_API_SERVER, setup_id, ext, VERSION), timeout=5)
    return response.text if response.status_code == 200 else None


def upload(setup):
    """ Uploads the user setup. """
    global SS_API_SERVER, STEAM_ID, VERSION
    setup["steam_id"] = STEAM_ID
    return post("{}/upload.php?ver={}".format(SS_API_SERVER, VERSION), data=dumps(setup), timeout=5).text


if __name__ == "__main__":
    STEAM_ID = 76561197991651936
    print("Server status: {} setup(s) on system.".format(count_setups()))
    CAR = "bmw_z4_gt3"
    TRACK = "ks_nurburgring"
    print(combo_list(CAR, TRACK))
    print(download(1))
else:
    from lib.ss_steam import get_steam_id_64
    STEAM_ID = get_steam_id_64()