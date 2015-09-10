#!/usr/bin/python

import time
import sqlite3

# met des 0 dans la base de donnees
def log_zero():
    conn=sqlite3.connect("/var/www/PIRlog.db")
    curs=conn.cursor()
    curs.execute("INSERT INTO TxOccupationBox values(datetime('now','localtime'), 0)")

    # commit the changes
    conn.commit()
    conn.close()

log_zero()
