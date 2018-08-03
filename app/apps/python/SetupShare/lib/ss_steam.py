#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Setup Share Steam utils.
"""

def get_steam_id_32(ac_folder="."):
    """ Get the last ID32 logged into steam. """
    id_32 = 0
    with open("{}/../../../logs/connection_log.txt".format(ac_folder), "r") as content_file:
        content = content_file.read()
        id_index = content.rfind("U:1:")
        end_index = content.find("]", id_index)
        id_32 = int(content[id_index+4:end_index])
    return id_32

def get_steam_id_64(ac_folder="."):
    """ Get the last ID64 logged into steam. """
    return steam_id_32_to_64(get_steam_id_32(ac_folder))

def steam_id_32_to_64(id_32):
    """ Convert the steam ID32 to ID64. """
    return id_32 + 76561197960265728

def steam_id_64_to_32(id_64):
    """ Convert the steam ID64 to ID32. """
    return id_64 - 76561197960265728

if __name__ == "__main__":
    print(get_steam_id_32("D:/Program Files (x86)/Steam/steamapps/common/assettocorsa"))
    print(get_steam_id_64("D:/Program Files (x86)/Steam/steamapps/common/assettocorsa"))