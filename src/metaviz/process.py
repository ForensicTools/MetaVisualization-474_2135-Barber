#!/usr/bin/python

import sys
import exiftool
import MySQLdb as mdb
import hashlib

# Change 'yourpassword' to your mysql password
con = mdb.connect('localhost', 'root', 'yourpassword', 'MetaViz');

#sys.stderr = open('/dev/null', 'w')

image_dir = "uploaded/"
files = sys.argv
files = files[1:]
hashes = [];

def key_exists(key):
	with con:
		cur = con.cursor()
	sql = "SHOW COLUMNS FROM images LIKE " + "\'" + key + "\'"
	cur.execute(sql)
	result = cur.fetchall()
	if len(result) > 0:
		if key == result[0][0]:
			return True
	else:
		return False

def get_hashes():
	with con:
		cur = con.cursor()
	sql = "SELECT hash FROM images"
	cur.execute(sql)
	result = cur.fetchall()
	return result

def hashimage(image):
	with open(image) as hash_me:
		data = hash_me.read()
		img_hash = hashlib.sha256(data).hexdigest()
		return img_hash

for x in xrange(len(files)):
	files[x] = image_dir + files[x]#.strip("'")

for image in files:
	
	with con:
		cur = con.cursor()
	imagehash = hashimage(image)
	sql_statement = "SELECT hash FROM images WHERE \'" + str(imagehash) +"\' IN (SELECT hash FROM images)"
	check = cur.execute(sql_statement)
	
	if check == 0:
		sql = "INSERT INTO images (Hash) VALUES (\'" + imagehash + '\')'
		cur.execute(sql)
		sql2 = "UPDATE images SET Name =\'" + str(image[9:]) + "\' WHERE hash=\'" + imagehash + "\'"
		cur.execute(sql2)
	else:
		# Remove from list of files
		files.remove(image)

	hashes.append(imagehash)

if len(files)>0:
	with exiftool.ExifTool() as et: 
		metadata = et.get_metadata_batch(files)

	i=0
	for d in metadata:
		with con:
			cur = con.cursor()
		keys = list(d)
		for key in keys:
			value = d[key]
			key=key.split(':', 1)[-1]
			key=key.replace('/','1')
			if key_exists(key):
				sql = "UPDATE images SET " + key + "=\'" + str(value) + "\' WHERE hash=\'" + hashes[i] + "\'"
				cur.execute(sql) 

print hashes
