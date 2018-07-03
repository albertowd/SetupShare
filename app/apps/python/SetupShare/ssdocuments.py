"""
Setup Share Document utils.
"""
import ctypes.wintypes
import os


def setup_dir():
    """ Returns the current Assetto Corsa user setups folder. """
    csidl_personal = 5  # My Documents
    shgfp_type_current = 0  # 0 Get current, 1 default value
    buf = ctypes.create_unicode_buffer(ctypes.wintypes.MAX_PATH)
    ctypes.windll.shell32.SHGetFolderPathW(None, csidl_personal, None, shgfp_type_current, buf)
    return "{}/Assetto Corsa/setups".format(buf.value).replace("\\", "/")


def read_setup(car, setup, track, extension="ini"):
    """ Returns the content of the setup .ini or .sp. """
    content = ""
    content_path = "{}/{}/{}/{}.{}".format(setup_dir(), car, track, setup, extension)
    if os.path.isfile(content_path):
        with open(content_path, "r") as content_file:
            content = content_file.read()
    return content


def list_setups(car, track):
    """ Returns the car/track setup list. """
    setups = []
    for file_name in os.listdir("{}/{}/{}".format(setup_dir(), car, track)):
        if file_name.endswith(".ini"):
            setups.append(file_name[:-4])
    return setups


def write_setup(car, content, setup, track, extension="ini"):
    """ Writes/overwrites a setup .ini or .sp. """
    content_path = "{}/{}/{}/{}.{}".format(setup_dir(), car, track, setup, extension)
    with open(content_path, "w") as content_file:
        content_file.write(content)


if __name__ == "__main__":
    CAR = "bmw_m3_gt2"
    TRACK = "spa"
    print(setup_dir())
    write_setup(CAR, "my ini content", "test", TRACK)
    write_setup(CAR, "my sp content", "test", TRACK, "sp")
    SETUPS = list_setups(CAR, TRACK)
    print(SETUPS)
    SETUPS_LEN = len(SETUPS)
    if SETUPS_LEN > 0:
        print(read_setup(CAR, SETUPS[0], TRACK))
        print(read_setup(CAR, SETUPS[0], TRACK, "sp"))
