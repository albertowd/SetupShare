"""
Setup Share is a Python app to exchange seputs inside an Assetto Corsa session.
"""
import os
import platform
import sys
STD_LIB = "stdlib"
if platform.architecture()[0] == "64bit":
    STD_LIB = "stdlib64"
sys.path.append(os.path.join(os.path.dirname(__file__), STD_LIB))
os.environ["PATH"] = os.environ["PATH"] + ";."

import ac
import Connection
import Gui
import Log

# Manage the GUI
GUI = Gui.Gui()


def acMain(ac_version):
    """ Setups the app. """
    Log.log("Starting Setup Share on AC Python API version {}...".format(ac_version))

    global GUI
    GUI.app_window = ac.newApp("Setup Share")
    ac.setIconPosition(GUI.app_window, 0, -10000)
    ac.setSize(GUI.app_window, 400, 400)

    lb_version = ac.addLabel(GUI.app_window, "v1.0.2")
    ac.setPosition(lb_version, 10, 3)

    GUI.bt_refresh = GUI.img_buttow("refresh")
    ac.addOnClickedListener(GUI.bt_refresh, listener_refresh)
    ac.setPosition(GUI.bt_refresh, 10, 40)

    GUI.lb_mine = ac.addLabel(GUI.app_window, "My setups")
    ac.setPosition(GUI.lb_mine, 44, 40)
    ac.setSize(GUI.lb_mine, 60, 30)

    GUI.bt_change = GUI.img_buttow("change")
    ac.addOnClickedListener(GUI.bt_change, listener_change)
    ac.setPosition(GUI.bt_change, 160, 40)
    ac.setVisible(GUI.bt_change, 0)

    GUI.lb_setup = ac.addLabel(GUI.app_window, "")
    ac.setFontAlignment(GUI.lb_setup, "center")
    ac.setPosition(GUI.lb_setup, 184, 40)
    ac.setSize(GUI.lb_setup, 182, 30)

    GUI.bt_upload = GUI.img_buttow("upload")
    ac.addOnClickedListener(GUI.bt_upload, listener_upload)
    ac.setPosition(GUI.bt_upload, 366, 40)
    ac.setVisible(GUI.bt_upload, 0)

    download_listeners = [listener_download_0, listener_download_1, listener_download_2, listener_download_3, listener_download_4,
                          listener_download_5, listener_download_6, listener_download_7, listener_download_8, listener_download_9]
    change_listeners = [listener_change_0, listener_change_1, listener_change_2, listener_change_3, listener_change_4,
                        listener_change_5, listener_change_6, listener_change_7, listener_change_8, listener_change_9]
    for driver_index in range(10):
        label = ac.addLabel(GUI.app_window, "")
        ac.setPosition(label, 10, 70 + driver_index * 30)
        ac.setSize(label, 150, 30)

        change = GUI.img_buttow("change")
        ac.addOnClickedListener(change, change_listeners[driver_index])
        ac.setPosition(change, 160, 73 + driver_index * 30)
        ac.setVisible(change, 0)

        setup = ac.addLabel(GUI.app_window, "")
        ac.setFontAlignment(setup, "center")
        ac.setPosition(setup, 184, 70 + driver_index * 30)
        ac.setSize(setup, 182, 30)

        download = GUI.img_buttow("download")
        ac.addOnClickedListener(download, download_listeners[driver_index])
        ac.setPosition(download, 366, 73 + driver_index * 30)
        ac.setVisible(download, 0)

        GUI.list.append({"download": download, "change": change,
                         "label": label, "setup": setup})

    GUI.bt_left = GUI.img_buttow("left")
    ac.addOnClickedListener(GUI.bt_left, listener_left)
    ac.setPosition(GUI.bt_left, 10, 370)
    ac.setVisible(GUI.bt_left, 0)

    GUI.lb_page = ac.addLabel(GUI.app_window, "0/0")
    ac.setFontAlignment(GUI.lb_page, "center")
    ac.setPosition(GUI.lb_page, 44, 370)
    ac.setSize(GUI.lb_page, 32, 30)
    ac.setVisible(GUI.lb_page, 0)

    GUI.bt_right = GUI.img_buttow("right")
    ac.addOnClickedListener(GUI.bt_right, listener_right)
    ac.setPosition(GUI.bt_right, 86, 370)
    ac.setVisible(GUI.bt_right, 0)

    GUI.bt_status_done = GUI.img_buttow("done")
    ac.addOnClickedListener(GUI.bt_status_done, listener_done)
    ac.setPosition(GUI.bt_status_done, 120, 370)
    ac.setVisible(GUI.bt_status_done, 0)

    GUI.bt_status_error = GUI.img_buttow("error")
    ac.addOnClickedListener(GUI.bt_status_error, listener_error)
    ac.setPosition(GUI.bt_status_error, 120, 370)
    ac.setVisible(GUI.bt_status_error, 0)

    GUI.lb_status = ac.addLabel(GUI.app_window, "")
    ac.setPosition(GUI.lb_status, 154, 370)
    ac.setSize(GUI.lb_status, 236, 370)

    if Connection.verify_server():
        GUI.set_status("Refresh app to start.")
    else:
        GUI.set_status("Server down, sorry.", True)
    GUI.update()

    return "Setup Share"


def acShutdown():
    """ Called when the session ends (or restarts). """
    Log.log("Shuting down Community Setup...")


def listener_change(*args):
    """ Changes the user setup. """
    global GUI
    GUI.update_setup(1)
    GUI.update()


def listener_change_0(*args):
    """ Changes the driver 1 setup. """
    global GUI
    GUI.update_driver_setup(0, 1)
    GUI.update()


def listener_change_1(*args):
    """ Changes the driver 2 setup. """
    global GUI
    GUI.update_driver_setup(1, 1)
    GUI.update()


def listener_change_2(*args):
    """ Changes the driver 3 setup. """
    global GUI
    GUI.update_driver_setup(2, 1)
    GUI.update()


def listener_change_3(*args):
    """ Changes the driver 4 setup. """
    global GUI
    GUI.update_driver_setup(3, 1)
    GUI.update()


def listener_change_4(*args):
    """ Changes the driver 5 setup. """
    global GUI
    GUI.update_driver_setup(4, 1)
    GUI.update()


def listener_change_5(*args):
    """ Changes the driver 6 setup. """
    global GUI
    GUI.update_driver_setup(5, 1)
    GUI.update()


def listener_change_6(*args):
    """ Changes the driver 7 setup. """
    global GUI
    GUI.update_driver_setup(6, 1)
    GUI.update()


def listener_change_7(*args):
    """ Changes the driver 8 setup. """
    global GUI
    GUI.update_driver_setup(7, 1)
    GUI.update()


def listener_change_8(*args):
    """ Changes the driver 9 setup. """
    global GUI
    GUI.update_driver_setup(8, 1)
    GUI.update()


def listener_change_9(*args):
    """ Changes the driver 10 setup. """
    global GUI
    GUI.update_driver_setup(9, 1)
    GUI.update()


def listener_done(*args):
    """ Hides the done status. """
    global GUI
    GUI.set_status("")
    GUI.update()


def listener_download_0(*args):
    """ Downloads the driver 1 setup. """
    global GUI
    GUI.download(0)
    GUI.update()


def listener_download_1(*args):
    """ Downloads the driver 2 setup. """
    global GUI
    GUI.download(1)
    GUI.update()


def listener_download_2(*args):
    """ Downloads the driver 3 setup. """
    global GUI
    GUI.download(2)
    GUI.update()


def listener_download_3(*args):
    """ Downloads the driver 4 setup. """
    global GUI
    GUI.download(3)
    GUI.update()


def listener_download_4(*args):
    """ Downloads the driver 5 setup. """
    global GUI
    GUI.download(4)
    GUI.update()


def listener_download_5(*args):
    """ Downloads the driver 6 setup. """
    global GUI
    GUI.download(5)
    GUI.update()


def listener_download_6(*args):
    """ Downloads the driver 7 setup. """
    global GUI
    GUI.download(6)
    GUI.update()


def listener_download_7(*args):
    """ Downloads the driver 8 setup. """
    global GUI
    GUI.download(7)
    GUI.update()


def listener_download_8(*args):
    """ Downloads the driver 9 setup. """
    global GUI
    GUI.download(8)
    GUI.update()


def listener_download_9(*args):
    """ Downloads the driver 10 setup. """
    global GUI
    GUI.download(9)
    GUI.update()


def listener_error(*args):
    """ Hides the error status. """
    global GUI
    GUI.set_status("")
    GUI.update()


def listener_left(*args):
    """ Go to the prevous page. """
    global GUI
    GUI.update_page(-1)
    GUI.update()


def listener_right(*args):
    """ Go to the next page. """
    global GUI
    GUI.update_page(1)
    GUI.update()


def listener_refresh(*args):
    """ Refresh the driver list. """
    global GUI
    GUI.set_status("")
    if Connection.verify_server():
        GUI.update()
        GUI.drivers.update()
        GUI.update_setups()
    else:
        GUI.clear()
        GUI.set_status("Server down, sorry.", True)
    GUI.update()


def listener_upload(*args):
    """ Upload the selected setup. """
    global GUI
    GUI.upload()
    GUI.update()
