#!/usr/bin/python

import os
import sys
import subprocess
import ConfigParser
import json

if (len(sys.argv) < 2):
	print "Usage: ./integrate-feedback-tool.py <CONFIG_FILE>"
	print "eg. ./integrate-feedback-tool.py ./example.json"
	sys.exit(1)

configFile = sys.argv[1];

if (os.path.isfile(configFile) == False):
	print "Configuration file does not exist!"
	sys.exit(1)

fp = open(configFile, "r")
cfg = json.load(fp)
fp.close()

toolName = cfg['name'];

codebaseDefault = "/codebase"

codebase = raw_input('Give the location of the codebase [Default is /codebase]:')
if (codebase == ""):
	codebase = codebaseDefault

if (os.path.isfile(codebase + "/engine/app/config/tools.ini") == False):
	print "Directory " + codebase + " is not a valid location!"
	sys.exit(1)

configPath = codebase + "/engine/app/config/tools.ini"


Config = ConfigParser.ConfigParser()
Config.read(configPath)
Config.add_section(toolName)

for service in cfg['services']:
	trigger = service['trigger']
	containers = service['containers']
	port = service['port']
	endpoint = service['endpoint']
	addrList = []
	for container in containers:
		process = subprocess.Popen("docker inspect --format '{{ .NetworkSettings.IPAddress }}' " + container, stdout=subprocess.PIPE, shell=True)
		output, err = process.communicate()
		addr = '"' + output.strip() + ":" + str(port) + '"'
		addrList.append(addr)
	query = "INSERT INTO configuration (section,setting,value) VALUES ('" + trigger.upper() + "','" + toolName + "','" + json.dumps(addrList) + "')"
	process = subprocess.Popen("docker exec rdb mysql its -e \"" + query + "\"", stdout=subprocess.PIPE, shell=True)
	output, err = process.communicate()
	print output.strip()
	query = "INSERT INTO configuration (section,setting,value) VALUES ('TOOLS','" + toolName + "','\"1\"')"
	process = subprocess.Popen("docker exec rdb mysql its -e \"" + query + "\"", stdout=subprocess.PIPE, shell=True)
	output, err = process.communicate()
	print output.strip()
	Config.set(toolName, trigger, endpoint)

fp = open(configPath, 'w')
Config.write(fp)
fp.close()
