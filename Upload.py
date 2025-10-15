import mysql.connector
from mysql.connector import Error
from pprint import pprint as pp

try:
        conn = mysql.connector.connect(host='localhost', user='root', password='Liberty197!', database='AD')
        if conn.is_connected():
                print("SUCCESS")
except Error as e:
	print(f"Error: {e}")
	exit()

cursor = conn.cursor()

cursor.execute("""
CREATE TABLE IF NOT EXISTS ADUsers (
    id INTEGER PRIMARY KEY,
    username VARCHAR(100),
    full_name VARCHAR(100),
    rid INTEGER,
    account_flags INTEGER,
    ntlm_hash VARCHAR(100)
)
""")

users = []
with open("users.txt", "r+") as file:
        for line in file:
                users.append(line.strip().split("\t"))

with open("temp.csv", "r+") as file:
        for line in file:
                name, fullname, _ = line.strip().replace("\"", "").split(',')
                for u in users:
                        #print(name, u[1])
                        if u[1] == name:
                                u.append(fullname)

for u in users:
        if len(u) < 5:
                u.append(u[1])

for i, (rid, username, password, flag, fullname) in enumerate(users):
        cursor.execute("INSERT INTO ADUsers (id, username, full_name, rid, account_flags, ntlm_hash) VALUES (%s, %s, %s, %s, %s, %s)", (i, username, fullname, rid, flag, password))

conn.commit()
cursor.close()
conn.close()

