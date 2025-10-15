

import mysql.connector
from mysql.connector import Error
from pprint import pprint as pp

try:
	conn = mysql.connector.connect(host='localhost', user='root', password='', database='AD')
	if conn.is_connected():
		print("SUCCESS")
except Error as e:
	print(f"Error: {e}")

cursor = conn.cursor()

cursor.execute("""
CREATE TABLE IF NOT EXISTS ad_hashes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100,
    full_name VARCHAR(100),
    rid INTEGER,
    ntlm_hash VARCHAR(100,
    account_flags INTEGER
)
""")

users = []
with open("users.txt", "w+") as file:
    for line in file:
	users.append(line.strip().split("\t"))

with open("temp.csv", "w+") as file:
    for line in file:
	name, fullname, _ = line.strip().split(',')
	
	for u in users:
	    if u[1] == name:
		u.append(fullname)

pp(users)