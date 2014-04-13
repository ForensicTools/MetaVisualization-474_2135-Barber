#!/usr/bin/python

import sys
import exiftool
import MySQLdb as mdb
import hashlib

# Establish Database Connection
# Change 'yourpassword' to your mysql password
con = mdb.connect('localhost', 'root', 'yourpassword', 'MetaViz');

# Files being processed
files = str(sys.argv)

# List of hashes that gets returned to PHP
hahses = [];

# SQL string to add value to database
sql = "INSERT INTO images(%s) VALUES(%s)"


def hashimage(image, hasher, blocksize=65536):
	buf = image.read(blocksize)
	while len(buf) > 0:
		hasher.update(buf)
		buf = image.read(blocksize)
	return hasher.digest()


# Checks the hash of each image being processed
for image in files:
	imagehash = hashimage(open(image, 'rb'), hashlib.sha256())
	# If hash exists in database, remove from list of files
	
	else:
		args = 'name', image[14:] 
		cur.execute(sql, args)
		args = 'hash', imagehash
		cur.execute(sql, args)

	hashes.append(imagehash)

# Pulls the metadata Out
with exiftool.ExifTool() as et:
	metadata = et.get_metadata_batch(files)

for d in metadata:
	with con:
		cur = con.cursor()
	#Cycle through each dictonary and add each piece of metadata to the database
	keys = list(d)
	for key in keys:
		args = key, keys[key]
		cur.execute(sql, args) 

# The hashes are the Unique ID to find the metadata from the databse
return hashes;