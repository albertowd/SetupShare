#!/usr/bin/env python
# -*- coding: utf-8 -*-
"""
Setup Share Log utils.
"""
import ac


def log(message):
    """ Logs a message on the log and console. """
    ac.log("[SS] {}".format(message))
    ac.console("[SS] {}".format(message))
