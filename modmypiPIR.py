#!/usr/bin/python
# source http://www.modmypi.com/blog/raspberry-pi-gpio-sensing-motion-detection

import RPi.GPIO as GPIO
import time
import sqlite3

GPIO.setmode(GPIO.BCM)
PIR_PIN = 7
GPIO.setup(PIR_PIN, GPIO.IN)

# store the temperature in the database
def log_presence():

    conn=sqlite3.connect("/var/www/PIRlog.db")
    curs=conn.cursor()

    curs.execute("INSERT INTO TxOccupationBox values(datetime('now'), (?))", (1,))

    # commit the changes
    conn.commit()

    conn.close()

def MOTION(PIR_PIN):
	print "Motion Detected!"
	log_presence()

print "PIR Module Test (CTRL+C to exit)"
time.sleep(2)
print "Ready"

try:
	GPIO.add_event_detect(PIR_PIN, GPIO.RISING, callback=MOTION)
	while 1:
		time.sleep(100)
except KeyboardInterrupt:
	print " Quit"
	GPIO.cleanup()
