#!/usr/bin/python

import os
import glob
import time
import MySQLdb
from time import sleep, strftime
from datetime import datetime
#from Adafruit_CharLCD import Adafruit_CharLCD
from RPLCD import CharLCD
from subprocess import * 
import RPIO
 
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')
 
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0]
device_file = device_folder + '/w1_slave'

db = None
lcd = CharLCD()

cmd = "ip addr show wlan0 | grep inet | awk '{print $2}' | cut -d/ -f1"

def run_cmd(cmd):
        p = Popen(cmd, shell=True, stdout=PIPE)
        output = p.communicate()[0]
        return output

def init_db():
	global db
	# Open database connection
	db = MySQLdb.connect("localhost","pi","pi","pi")
	
	# prepare a cursor object using cursor() method
	cursor = db.cursor()

	# Drop table if it already exist using execute() method.
	cursor.execute("DROP TABLE IF EXISTS DATETEMP")
	
	# Create table as per requirement
	sql = "CREATE TABLE DATETEMP (DATE TIMESTAMP, TEMP FLOAT)"

	cursor.execute(sql)

def insert(date, temp):
	# prepare a cursor object using cursor() method
	cursor = db.cursor()

	# Prepare SQL query to INSERT a record into the database.
	sql = "INSERT INTO DATETEMP(DATE, TEMP) \
         	VALUES ('%s', '%s')" % (date, temp)
	try:
   		# Execute the SQL command
   		cursor.execute(sql)
   		# Commit your changes in the database
   		db.commit()
	except:
   		# Rollback in case there is any error
   		db.rollback()

def read_temp_raw():
    f = open(device_file, 'r')
    lines = f.readlines()
    f.close()
    return lines
 
def read_temp():
    lines = read_temp_raw()
    while lines[0].strip()[-3:] != 'YES':
        time.sleep(0.2)
        lines = read_temp_raw()
    equals_pos = lines[1].find('t=')
    if equals_pos != -1:
        temp_string = lines[1][equals_pos+2:]
        temp_c = float(temp_string) / 1000.0
        temp_f = temp_c * 9.0 / 5.0 + 32.0
        return temp_f
	
init_db()
while True:
	date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
	temp = read_temp()
	#print("%s %s" % (date, temp))
	insert(date, temp)
	door = 0;

	lcd.clear()
	ipaddr = run_cmd(cmd)
	lcd.cursor_pos = (0,0)
	lcd.write_string("%s" % date)
	lcd.cursor_pos = (1,0)
	lcd.write_string("IP: %s" % ipaddr)
	lcd.cursor_pos = (2,0)
	lcd.write_string("Temp: %sF" % temp)
	lcd.cursor_pos = (3,0)
	lcd.write_string("Opened Today: %s" % door)

	time.sleep(5)
