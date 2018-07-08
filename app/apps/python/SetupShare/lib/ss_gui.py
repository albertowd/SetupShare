#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Setup Share Gui utils.
"""
import math

from lib.ss_connection import combo_list, download, upload
from lib.ss_documents import list_setups, read_setup, write_setup
from lib.ss_log import log
from sim_info.sim_info import info
import ac


class Driver:
    """ Keeps the information about each driver in the server. """

    def __init__(self, name=""):
        self.current = 0
        self.name = name
        self.setups = []


class DriverList:
    """ Class to store the server driver list and manage it's setups. """

    def __init__(self):
        """ Default constructor. """
        self.__drivers = []

    def clear(self):
        """ Clear the list. """
        self.__drivers = []

    def driver(self, index, page):
        """ Returns the driver at the index. """
        driver = None
        driver_index = index + page * 10
        if driver_index in range(len(self.__drivers)):
            driver = self.__drivers[driver_index]
        return driver
    
    def driver_index(self, name):
        """ Returns the driver index on the list. """
        for driver_index in range(len(self.__drivers)):
            if self.__drivers[driver_index].name == name:
                return driver_index
        return -1

    def page(self, page):
        """ Returns the driver, name and setup list whitin the page given. """
        drivers = []
        driver_range = range(len(self.__drivers))
        for driver_index in range(10 * page, 10 * page + 10):
            if driver_index not in driver_range:
                drivers.append(Driver())
            else:
                drivers.append(self.__drivers[driver_index])

        return drivers

    def page_count(self):
        """ Returns the total page count. """
        return math.ceil(len(self.__drivers) / 10.0)

    def is_valid(self, page):
        """ Verifies if the page number is valid. """
        return page > -1 and page < self.page_count()

    def update(self, setups):
        """ Updates drivers setups. """
        for setup in setups:
            driver_index = self.driver_index(setup["driver"])
            if driver_index is -1:
                self.__drivers.append(Driver(setup["driver"]))
                driver_index = self.driver_index(setup["driver"])
            self.__drivers[driver_index].setups.append((setup["id"], setup["name"]))

    def update_setup(self, index, page_index, add=0):
        """ Updates the driver setup index. """
        driver_index = index + 10 * page_index
        if driver_index in range(len(self.__drivers)):
            driver = self.__drivers[driver_index]
            driver.current += add
            if driver.current not in range(len(driver.setups)):
                driver.current = 0
            self.__drivers[driver_index] = driver


class Gui:
    """ Class to manage GUI widgets. """

    def __init__(self):
        """ Default constructor. """
        self.app_window = 0
        self.bt_change = 0
        self.bt_left = 0
        self.bt_refresh = 0
        self.bt_right = 0
        self.bt_status_done = 0
        self.bt_status_error = 0
        self.bt_upload = 0
        self.done = ""
        self.driver = Driver(ac.getDriverName(0))
        self.drivers = DriverList()
        self.error = ""
        self.lb_mine = 0
        self.lb_page = 0
        self.lb_setup = 0
        self.lb_status = 0
        self.list = []
        self.page = 0
        self.server = True

    def clear(self):
        """ Clear the state of the GUI. """
        self.drivers.clear()
        self.driver.setups = []

    def download(self, index):
        """ Downloads driver setup. """
        car = ac.getCarName(0)
        driver = self.drivers.driver(index, self.page)
        if driver is not None:
            setup = driver.setups[driver.current]
            track = ac.getTrackName(0)
            log("Downloading setup (car: {}, dirver: {}, name: {}, track: {})...".format(car, driver.name, setup[1], track))
            ini = download(setup[0])
            if ini != None:
                write_setup(car, ini, setup[1], track)
                sp = download(id, "sp")
                if sp != None:
                    write_setup(car, sp, setup[1], track, "sp")
                self.set_status("{} downloaded.".format(setup[1]))
            else:
                self.set_status("Download failed.", True)
        else:
            self.set_status("Invalid driver.", True)

    def img_buttow(self, icon, width=24, height=24):
        """ Creates a nem icon button. """
        button_id = ac.addButton(self.app_window, "")
        ac.setBackgroundTexture(button_id, "apps/python/SetupShare/img/{}.png".format(icon))
        ac.setSize(button_id, width, height)
        return button_id

    def set_status(self, message, error=False):
        """ Sets the error or done status. """
        log(message)
        self.done = "" if error else message
        self.error = message if error else ""

    def update(self):
        """ Updates the interface. """
        # Updates the current setup.
        if len(self.driver.setups) == 0:
            ac.setVisible(self.lb_mine, 0)
            ac.setText(self.lb_setup, "No setups")
            ac.setVisible(self.bt_change, 0)
            ac.setVisible(self.bt_upload, 0)
        else:
            ac.setVisible(self.lb_mine, 1)
            ac.setText(self.lb_setup, self.driver.setups[self.driver.current])
            ac.setVisible(self.bt_change, 1)
            ac.setVisible(self.bt_upload, 1)

        # Updates the driver list.
        drivers = self.drivers.page(self.page)
        for index, driver in enumerate(drivers):
            # Updates the label of the driver.
            ac.setText(self.list[index]["label"], driver.name)

            # Updates the setup, change and download buttons.
            has_setup = len(driver.setups) > 0
            ac.setText(self.list[index]["setup"], "" if len(driver.setups) == 0 else driver.setups[driver.current][1])
            ac.setVisible(self.list[index]["change"], 1 if has_setup else 0)
            ac.setVisible(self.list[index]["download"], 1 if has_setup else 0)

        # Updates the page infos.
        ac.setVisible(self.bt_left, 1 if self.page > 0 else 0)
        ac.setVisible(self.lb_page, 1 if self.drivers.is_valid(self.page) else 0)
        ac.setText(self.lb_page, "{}/{}".format(self.page + 1, self.drivers.page_count()))
        ac.setVisible(self.bt_right, 1 if self.drivers.is_valid(self.page + 1) else 0)

        # Updates the info.
        if len(self.error) > 0:
            ac.setFontColor(self.lb_status, 1.0, 0.5, 0.5, 1.0)
            ac.setText(self.lb_status, self.error)
            ac.setVisible(self.bt_status_done, 0)
            ac.setVisible(self.bt_status_error, 1)
        elif len(self.done) > 0:
            ac.setFontColor(self.lb_status, 0.5, 1.0, 0.5, 1.0)
            ac.setText(self.lb_status, self.done)
            ac.setVisible(self.bt_status_done, 1)
            ac.setVisible(self.bt_status_error, 0)
        else:
            ac.setText(self.lb_status, "")
            ac.setVisible(self.bt_status_done, 0)
            ac.setVisible(self.bt_status_error, 0)

    def update_driver_setup(self, index, add=0):
        """ Updates a driver setups index. """
        self.drivers.update_setup(index, self.page, add)

    def update_page(self, add=0):
        """ Updates the driver list page. """
        self.page += add
        if not self.drivers.is_valid(self.page):
            self.page = 0

    def update_setup(self, add=0):
        """ Updates the index of the current setup. """
        self.driver.current += add
        if self.driver.current not in range(len(self.driver.setups)):
            self.driver.current = 0

    def update_setups(self):
        """ Updates the driver setup list. """
        car = ac.getCarName(0)
        track = ac.getTrackName(0)
        # Updates the user setups.
        self.driver.setups = list_setups(car, track)
        self.update_setup()

        # Updates the drivers setups.
        self.drivers.clear()
        log("Updating setup list from server (car: {}, track: {})...".format(car, track))
        setups = combo_list(car, track)
        log("{} setup(s) found.".format(len(setups)))
        self.drivers.update(setups)

    def upload(self):
        """ Uploads the current setup to the server. """
        name = self.driver.setups[self.driver.current]
        ini_content = read_setup(ac.getCarName(0), name, ac.getTrackName(0))
        if ini_content == None:
            self.set_status("Invalid setup.", True)
        else:
            setup = {}
            setup["ac_version"] = info.static._acVersion
            setup["car"] = ac.getCarName(0)
            setup["driver"] = self.driver.name
            setup["ini"] = ini_content
            setup["name"] = name
            setup["sp"] = read_setup(ac.getCarName(0), name, ac.getTrackName(0), "sp")
            setup["track"] = ac.getTrackName(0)
            log("Uploading setup {}...".format(name))
            upload_response = upload(setup)
            self.set_status(upload_response, "not" in upload_response)
