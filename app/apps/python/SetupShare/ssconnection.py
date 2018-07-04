"""
Setup Share Connection utils.
"""
import requests

SS_API_SERVER = "http://192.168.15.2/albertowd.com.br/setupshare/api"

def combo_list(car, track):
    """ Gets the list os available setups of the car/track. """
    global SS_API_SERVER
    response = requests.get("{}/list.php?car={}&track={}".format(SS_API_SERVER, car, track), timeout=5)
    return response.json()


def download(id, extension="ini"):
    """ Downloads a specific setup. """
    global SS_API_SERVER
    response = requests.get("{}/download.php?id={}&ext={}".format(SS_API_SERVER, id, extension), timeout=5)
    return response.text


def upload(setup):
    """ Uploads the user setup. """
    global SS_API_SERVER
    response = requests.get("{}/upload.php?setup={}".format(SS_API_SERVER, setup), timeout=5)
    return response.text


def verify_server():
    """ Verify the server connection. """
    global SS_API_SERVER
    response = requests.get("{}/download.php".format(SS_API_SERVER), timeout=5)
    return response.status_code == 403


if __name__ == "__main__":
    print("Server status: {}".format("on" if verify_server() else "off"))
    CAR = "bmw_m3_gt2"
    DRIVER = "Alberto Dietrich"
    SETUP = "test"
    TRACK = "spa"
    #print(upload(CAR, DRIVER, "my ini content", SETUP, "my sp content", TRACK))
    print(combo_list(CAR, TRACK))
    print(download(1))
    #print(download(CAR, DRIVER, SETUP, TRACK, "sp"))
