"""
Setup Share Connection utils.
"""
import requests


def available(car, track):
    """ Gets the list os available setups of the car/track. """
    url = "{}&service=list".format(default_url(car, track))
    response = requests.get(url, timeout=5)
    return response.json()


def download(car, driver, name, track, extension="ini"):
    """ Downloads a specific setup. """
    url = "driver={}&ext={}&name={}&service=download"
    url = url.format(driver, extension, name)
    response = requests.get("{}&{}".format(default_url(car, track), url), timeout=5)
    return response.text


def default_url(car, track):
    """ Makes the default URL for the player server name, track and car. """
    url = "http://albertowd.com.br/setupshare/setups/setupshare.php?car={}&track={}"
    return url.format(car, track)


def upload(car, driver, ini_content, name, sp_content, track):
    """ Uploads the user setup. """
    url = "driver={}&ini={}&name={}&service=upload&sp={}"
    url = url.format(driver, ini_content, name, sp_content)
    response = requests.get("{}&{}".format(default_url(car, track), url), timeout=5)
    return response.text


def verify_server():
    """ Verify the server connection. """
    response = requests.get("http://albertowd.com.br/setupshare/setups/setupshare.php", timeout=5)
    return response.status_code == 403


if __name__ == "__main__":
    print("Server status: {}".format("on" if verify_server() else "off"))
    CAR = "bmw_m3_gt2"
    DRIVER = "Alberto Dietrich"
    SETUP = "test"
    TRACK = "spa"
    print(upload(CAR, DRIVER, "my ini content", SETUP, "my sp content", TRACK))
    print(available(CAR, TRACK))
    print(download(CAR, DRIVER, SETUP, TRACK))
    print(download(CAR, DRIVER, SETUP, TRACK, "sp"))
