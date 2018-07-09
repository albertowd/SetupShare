#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Setup Share Connection utils.
"""
from json import dumps
from requests import get, post
import sys

SS_API_SERVER = "http://albertowd.com.br/setupshare/api"

def combo_list(car, track):
    """ Gets the list os available setups of the car/track. """
    global SS_API_SERVER
    response = get("{}/list.php?app&car={}&track={}".format(SS_API_SERVER, car, track), timeout=5)
    return response.json() if response.status_code == 200 else []


def download(setup_id, ext="ini"):
    """ Downloads a specific setup. """
    global SS_API_SERVER
    response = get("{}/download.php?id={}&ext={}".format(SS_API_SERVER, setup_id, ext), timeout=5)
    return response.text if response.status_code == 200 else None


def upload(setup):
    """ Uploads the user setup. """
    global SS_API_SERVER
    return post("{}/upload.php".format(SS_API_SERVER), data=dumps(setup), timeout=5).text


def verify_server():
    """ Verify the server connection. """
    global SS_API_SERVER
    return get("{}/download.php".format(SS_API_SERVER), timeout=5).status_code == 403


if __name__ == "__main__":
    print("Server status: {}".format("on" if verify_server() else "off"))
    CAR = "bmw_m3_gt2"
    DRIVER = "Alberto Dietrich"
    SETUP = "test"
    TRACK = "spa"
    #print(upload(CAR, DRIVER, "my ini content", SETUP, "my sp content", TRACK))
    print(combo_list(CAR, TRACK))
    print(download(1))
