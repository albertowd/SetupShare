"""
Setup Share Gui utils.
"""

import math

import ac
import Connection
import Documents


class Driver:
    """ Keeps the information about each driver in the server. """

    def __init__(self, driver_index=-1):
        """ Default constructor the tries to get driver info from the game. """
        self.car = "" if driver_index < 0 else ac.getCarName(driver_index)
        self.current = 0
        self.connected = False if driver_index < 0 else ac.isConnected(
            driver_index)
        self.name = "" if driver_index < 0 else ac.getDriverName(driver_index)
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

    def page(self, page):
        """ Returns the driver name and setup list whitin the page given. """
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

    def update(self):
        """ Updates the driver list. """
        self.__drivers = []
        driver_count = ac.getCarsCount()
        for driver_index in range(1, driver_count):
            self.__drivers.append(Driver(driver_index))

    def update_driver_setups(self, setups):
        """ Updates driver setups with the new ones from the server. """
        for driver in self.__drivers:
            driver.setups = []
            if driver.name in setups:
                driver.setups = setups[driver.name]

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
        self.driver = Driver(0)
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
        driver = self.drivers.driver(index, self.page)
        if driver is not None:
            setup = driver.setups[driver.current]
            track = ac.getTrackName(0)
            json = Connection.download(driver.car, driver.name, setup, track)
            if isinstance(json, dict):
                Documents.write_setup(driver.car, json["ini"], setup, track)
                if len(json["sp"]) > 0:
                    Documents.write_setup(
                        driver.car, json["sp"], setup, track, "sp")
                self.set_status("{} downloaded.".format(setup))
            else:
                self.set_status(json, True)
        else:
            self.set_status("Invalid driver.", True)

    def img_buttow(self, icon, width=24, height=24):
        """ Creates a nem icon button. """
        button_id = ac.addButton(self.app_window, "")
        ac.setBackgroundTexture(
            button_id, "apps/python/SetupShare/img/{}.png".format(icon))
        ac.setSize(button_id, width, height)
        return button_id

    def set_status(self, message, error=False):
        """ Sets the error or done status. """
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
        car = ac.getCarName(0)
        drivers = self.drivers.page(self.page)
        for index, driver in enumerate(drivers):
            # Updates the label of the driver.
            ac.setText(self.list[index]["label"], driver.name)
            rgb = 1 if driver.car == car and driver.connected else 0.5
            ac.setFontColor(self.list[index]["label"], rgb, rgb, rgb, 1)

            # Updates the setup, change and download buttons.
            has_setup = len(driver.setups) > 0
            ac.setText(self.list[index]["setup"], "" if len(
                driver.setups) == 0 else driver.setups[driver.current])
            ac.setVisible(self.list[index]["change"], 1 if has_setup else 0)
            ac.setVisible(self.list[index]["download"], 1 if has_setup else 0)

        # Updates the page infos.
        ac.setVisible(self.bt_left, 1 if self.page > 0 else 0)
        ac.setVisible(
            self.lb_page, 1 if self.drivers.is_valid(self.page) else 0)
        ac.setText(self.lb_page, "{}/{}".format(self.page +
                                                1, self.drivers.page_count()))
        ac.setVisible(self.bt_right, 1 if self.drivers.is_valid(
            self.page + 1) else 0)

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
        car = self.driver.car
        track = ac.getTrackName(0)
        # Updates the user setups.
        self.driver.setups = Documents.list_setups(car, track)
        self.update_setup()

        # Updates the drivers setups.
        json = Connection.available(car, track)
        if isinstance(json, dict):
            self.drivers.update_driver_setups(json)

    def upload(self):
        """ Uploads the current setup to the server. """
        car = self.driver.car
        setup = self.driver.setups[self.driver.current]
        track = ac.getTrackName(0)
        ini_content = Documents.read_setup(car, setup, track)
        sp_content = Documents.read_setup(car, setup, track, "sp")
        if len(ini_content) == 0:
            self.set_status("Invalid setup.", True)
        else:
            self.set_status(Connection.upload(car, ac.getDriverName(
                0), ini_content, setup, sp_content, track))
