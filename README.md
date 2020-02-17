# README #

This README contains all the information needed to get the Intelligent Tutoring System up and running.

## What is this repository for? ##

This repository contains all the required scripts to deploy the Intelligent Tutoring System on a machine. This system can be set up on any machine running a flavor of Linux. This could be physical host machine or a guest VM running inside of a host machine. There are additional scripts which can be used for maintenance of the system.

## Requirements ##
* Linux (any standard flavor)
* Docker ([https://www.docker.com/](https://www.docker.com/))
* Git ([https://git-scm.com/](https://git-scm.com/))
* Python

The following are needed for maintenance and automation of some tasks: 

* mysql client
* php-mysql


## Getting Started ##

### __Step 1:__ Configure the installation.

  The following are the important configurable options (and the files that contain them):

* Number of web-application/engine containers (_config/config.ini): This can be configured by editing the "NUM_WEBAPP" and "NUM_ENGINE" values inside the "config.ini" file in the "_config" directory. 

* Local codebase repositories (_config/config.ini): This can be configured by modifying the ssh URLs of the "WEBAPP_SRC" and "ENGINE_SRC" parameters, located inside the "config.ini" file. Also the corresponding "REPO_HOST" value must also be changed.

* Components whose sources should be updated (_config/config.ini): In case you updated the sources.list file mentioned above, you may want to restrict the use of this sources.list file for specific components. Setting the values of "CACHE", "WEBAPP", etc. to 1, forces the installer to use the custom sources.list file. A value of 0 results in the respective component using the default sources.list file.

* APPORT (_config/config.ini): The port where main application runs.
    
* COURSE_ID (_config/env.ini): The identification number of the course you plan to teach

* Database users (_config/env.ini): The credentials to access mysql and mongo databases.

* Programming Languages (nosql/init_mongo.js): Update the list of programming languages and related tools for the mongodb environment. See the comments and existing entries in the file.
    
* Linux repos (sources.list): In case you have a local mirror or one that is closer than the repository mentioned in the default sources.list file, you can update the sources.list file to point to the respective mirrors. Please take care to add a mirror containing all recent updates. Modify the sources.list file inside the "_config" folder to suit your needs.

* Key-pairs for accessing the repositories (id_rsa, id_rsa.pub): The public/private key-pairs are used to access the codebase from the repositories. Update these to match the repositories that you use.

### __Step 2:__ Build the images.

Assuming that you have a fresh Linux installation with Docker and Git, you need to first build the images corresponding to the system components. Doing this is a breeze. All you have to do is run the "build" script from inside of the directory containing it. Simply run:

```
#!shell

./build
```
This process will take several minutes, as the individual images will take time to build. After this is complete (assuming that there are no errors), you should have a set of images ready. You can view these images by typing:

```
#!shell

docker images
```
You should see a set of images starting with "prutor/". These are the images corresponding to the various components of the system.

### __Step 3:__ Deploy and Start Prutor.

After the images have been built, you can start the system by running another utility script. Simply run the following in the same directory:

```
#!shell

./deploy
```
This will start all the required containers and do the necessary configurations. Once the system boots up, you shall be able to view the following web interfaces (the ports may differ if you modified the values in _config/config.ini):

* Service discovery UI: http://<ip_addr>:81/
* Database Management UI: http://<ip_addr>:84/
* Web Application UI: http://<ip_addr>:82/

The credentials for accessing the database management and service discovery interface are given in _config/env.ini. The default values are:

* **username** - prutor
* **password** - toor

The default credentials for accessing the web application UI are:

* **username** - guest@prutor.edu
* **password** - escits

__IMPORTANT: guest@prutor.edu account should be deleted after creating regular administrative accounts.__

In case you encountered errors during the process, you can revert back by running the clean script. It will undo all the changes that were done by the "build" and "deploy" scripts. Just run the following to do a complete cleanup:

```
#!shell

./clean
```

### __Step 4:__ Enable __escompiler__.

You need to integrate escompiler feedback tool to get error messages properly, as part of Editor UI. To do so, run the following:

```
#!shell

./_bin/integrate-feedback-tool.py escompiler/escompiler.json
```

## Integrating Feedback Tools ##
It is quite likely that you desire integrating feedback tools into the ITS system. The task of doing so is pretty simple. You first need to build and run the Docker containers corresponding to the feedback tools. In the event that the containers require database or cache services, you can specify these services using the host addresses of the respective service. This information can be found using the service discovery user interface. After the feedback services are up and running inside containers, you need to make the ITS aware of them. This is the part where you would integrate the feedback tool into the ITS. Inside the "_bin" directory is an executable named "integrate-feedback-tool.py". In order to run this script you must first create a configuration file which contains the details of the integration. The configuration file should be a JSON file having the following format:

```
#!JSON

{
    "name": "<name_of_the_feedback_tool>",
    "services": [<an_array_of_services_provided_by_this_tool>]
}
```
Each service provided by the tool should have the following format:

```
#!JSON

{
    "trigger": "<event_on_which_tool_should_execute_this_service>",
    "containers": [<a_list_of_strings_of_docker_container_names_which_host_this_service>],
    "port": <the_port_on_the_container_on_which_this_service_is_hosted>,
    "endpoint": "<url_endpoint_for_this_service>"
}
```
You can refer to the "example.json" file in the same directory to get an idea of how a configuration looks like. Valid values for trigger are "ON_COMPILE", "PRE_COMPILE", "ON_EVALUATE", "ON_ACCEPTED", etc.

After creating this configuration file, invoke the above mentioned executable in the following manner:

```
#!shell

./_bin/integrate-feedback-tool.py <your_configuration_file>.json
```
The tool will then be integrated into the system.

## Updating ##
You can update the codebase of the web-application and engine components from their source repositories. In order to do so, you need to run the "update-webapp" and "update-engine" scripts, inside the "_bin" directory. The web application will be restarted, once it is updated. You can also restart the web application services by executing the "restart-webapp" script in the same directory.

## Scaling ##
You can add/remove web-application and engine containers to/from the system in a breeze. To add a web application container to the system, simply run the "upscale-webapp" script from inside the "_bin" directory. Likewise, to remove a web application container invoke the "downscale-webapp" script from the same directory. Similar scripts exist for the engine as well and are named "upscale-engine" and "downscale-engine" respectively.

## Restart and Backup ##

To enable prutor server at system restart, you can make the "restart-containers-on-reboot" as the init script. An example follows:
```
#!shell

ln -s <path-to-prutor>/_bin/restart-containers-on-reboot /etc/init.d
update-rc.d restart-containers-on-reboot defaults

```

Similarly, we have provided a script to take a backup of prutor database. To use it, set the db_user and db_passw parameters in "_scripts/backup-its.sh", and run it.
```
#!shell

./_scripts/backup-its.sh

```
It is advisable to create a user with "readonly" access to database for maintainence jobs that do not need to update the data.

## Who do I talk to? ##

* Amey Karkare <karkare@cse.iitk.ac.in>
* Dept of CSE, IIT Kanpur
