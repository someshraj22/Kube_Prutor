#
# Some useful functions for Prutor Python support
# 
# @author      Amey Karkare
# @copyright   (c) 2016- IIT Kanpur
# @license     All rights reserved.
# 

def plot(fig, filename=None):
    '''Plot the given figure as a SVG file.
    The optional parameter filename is the name of a file without
    path or extension.
    The URL of the file is returned.'''
    try:
        import os
        import sys
        import hashlib

        # get a random looking string from the script name
        theBase = sys.argv[0].encode()
        theBase = os.path.splitext(theBase)[0]
        theBase = 'p' + hashlib.sha1(theBase).hexdigest()

        #and add the filename to it.
        theFile = os.path.basename(filename)
        theFile = theBase + '_' + theFile
 
        theURL = 'http://172.20.169.222:3228/' + theFile
        fig.savefig('plots/' + theFile)
        print ('Plot generated at', theURL)
    except Exception as e:
        raise e

        
