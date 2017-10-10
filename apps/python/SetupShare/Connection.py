"""
Setup Share Connection utils.
"""
import requests

import Documents

def available(car, track):
    """ Gets the list os available setups of the car/track. """
    response = requests.get(default_url(car, track), timeout=5)
    return response.json()


def download(car, driver, setup, track):
    """ Downloads a specific setup. """
    url = "driver={}&setup={}"
    url = url.format(driver, setup)
    response = requests.get("{}&{}".format(default_url(car, track), url), timeout=5)
    if response.status_code == 200:
        return response.json()
    else:
        return response.text


def default_url(car, track):
    """ Makes the default URL for the player server name, track and car. """
    return "http://ligarst.com.br/setupshare.php?car={}&track={}".format(car, track)


def upload(car, driver, ini_content, setup, sp_content, track):
    """ Uploads the user setup. """
    url = "driver={}&ini={}&setup={}&sp={}"
    url = url.format(driver, ini_content, setup, sp_content)
    response = requests.get("{}&{}".format(default_url(car, track), url), timeout=5)
    return response.text

def verify_server():
    """ Verify the server connection. """
    response = requests.get("http://ligarst.com.br/setupshare.php", timeout=5)
    return response.status_code == 403


if __name__ == "__main__":
    print("Server status: {}".format("on" if verify_server() else "off"))
    CAR = "bmw_m3_gt2"
    DRIVER = "Alberto Dietrich"
    SETUP = "test"
    TRACK = "spa"
    INI_CONTENT = Documents.read_setup(CAR, SETUP, TRACK)
    SP_CONTENT = Documents.read_setup(CAR, SETUP, TRACK, "sp")
    print(upload(CAR, DRIVER, INI_CONTENT, SETUP, SP_CONTENT, TRACK))
    print(available(CAR, TRACK))
    print(download(CAR, DRIVER, SETUP, TRACK))
