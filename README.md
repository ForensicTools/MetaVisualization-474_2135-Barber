MetaVisualization-474_2135-Barber
=================================

This tool will take a collection of images, extract metadata, and analyze against data from other images metadata to reveal patterns. Results will be displayed in a graphical form. The front-end will be developed in php with MySQL and the back-end will be written in Python. A python wrapper for exiftool call pyexiftool (available at http://smarnach.github.io/pyexiftool/) is being used for metadata extraction.

The goal of this project is to allow forensic investigators to see patterns not obvious in the invidual meta data. It's hard to see minor discrepancies in metadata that might point to data being spoofed. By comparing a number of images together, these flaws will quickly become evidant.

*** DISCLAIMER *** This source code is absurdly insecure. I've done minimal input checking and sanitization thus far. 