serialsolutionscsv2primo
========================

For some reason the Ex Libris PRIMO discovery tool wasn't built to natively import the Serial Solutions export csv files. This script converts a standard SerSol csv to an XML format that can be imported by PRIMO. Inspired by a VBScript by colleague Frederick Reiss, due to differences between PHP and VBScript this was pretty much a ground-up rebuild of that script. Runs fast, sips system resources, and contains a heuristic algorithm that dedups multiple records for different holdings periods (PRIMO needs this).

If the deduping is giving you trouble, tweak the parameters of the heuristic. This can be set up to converted a regularly outputted version of the SerSol csv so that the end result may be regularly uploaded into PRIMO (we were doing this at RMB). If, for some reason, this thing uses too many system resources, reduce the number of lines in the chunking. Runs fine for us.
