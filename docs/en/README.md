# SymconPTV
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-5.0%20%3E-green.svg)](https://www.symcon.de/forum/threads/38222-IP-Symcon-5-0-verf%C3%BCgbar)

SymconPTV is an extension for home automation IP Symcon. With this extension you can control your Panasonic TV.

## Documentation

**Table of Contents**

1. [Features](#1-features)
2. [Requirements](#2-requirements)
3. [Installation](#3-installation)
4. [Function reference](#4-functionreference)
5. [Configuration](#5-configuration)
6. [Annex](#6-annex)

## 1. Features

- Turn the TV on and off
- Sending commands to the Panasonic TV
- Determine the state event-based using DLNA
  

## 2. Requirements

 - IP Symcon version 5.1 or higher
 - For IP-Symcon versions smaller than 5.1, the branch _Old-Version_ must be selected
 

## 3. Installation

### a. Loading the module

Open the IP Console's web console with _http://<IP-Symcon IP>:3777/console/_.

Then click on the module store icon (IP-Symcon > 5.1) in the upper right corner.

![Store](img/store_icon.png?raw=true "open store")

In the search field type

```
Panasonic TV
```  


![Store](img/module_store_search_en.png?raw=true "module search")

Then select the module and click _Install_

![Store](img/install_en.png?raw=true "install")


#### Install alternative via Modules instance (IP-Symcon < 5.1)

Open the IP Console's web console with _http://<IP-Symcon IP>:3777/console/_.

_Open_ the object tree .

![Objektbaum](img/object_tree.png?raw=true "Objektbaum")	

Open the instance _'Modules'_ below core instances in the object tree of IP-Symcon (>= Ver 5.x) with a double-click and press the _Plus_ button.

![Modules](img/modules.png?raw=true "Modules")	

![Plus](img/plus.png?raw=true "Plus")	

![ModulURL](img/add_module.png?raw=true "Add Module")
 
Enter the following URL in the field and confirm with _OK_:

```
https://github.com/Wolbolar/SymconPTV
```  
	         
Then an entry for the module appears in the list of the instance _Modules_

By default, the branch _master_ is loaded, which contains current changes and adjustments.
Only the _master_ branch is kept current.

![Master](img/master.png?raw=true "master") 

If an older version of IP-Symcon smaller than version 4.1 is used, click on the gear on the right side of the list.
It opens another window,

![SelectBranch](img/select_branch_en.png?raw=true "select branch") 

here you can switch to another branch, for older versions smaller than 4.1 select _Old-Version_ .

### b. Configuration in IP-Symcon

In IP-Symcon add _Instance_ (_rightclick -> add object -> instance_) under the category, under which you want to add the Panasonic TV,
and select _Panasonic TV_.
 
It will automatically create a web hook for the Panasonic TV. The status variables / categories are created automatically. Deleting variables can lead to malfunctions.

##### status variables

Name         | Type      | description
------------ | --------- | ----------------
State        | Integer   | Indicates the state of the TV.


##### Profile:

No further profiles are needed.


## 4. Function reference

### Panasonic:

`PTV_SetPowerOn(integer $InstanzID);`

Turn on the TV with the instance ID $InstanceID.
The function does not return any value.

Example: `PTV_SetPowerOn(12345);`

`PTV_SetPowerOff(integer $InstanzID);`

Turn off the TV with the instance ID $InstanceID.
The function does not return any value.

Example : `PTV_SetPowerOff(12345);`


## 5. Configuration:

### Panasonic:

| Property    | Type    | Default value | Function                                  |
| :---------: | :-----: | :-----------: | :---------------------------------------: |
| Host        | string  |               | IP address of the Panasonic TV            |


### PTVHook

Name                   | description
---------------------- | ---------------------------------
Host                   | The address of the symconserver for the hook call
Port                   | The associated port (default 3777)

## 6. Annnex

###  a. Functions:

#### Panasonic:

`PTV_SetPowerOn(integer $InstanzID);`

Turn on the TV with the instance ID $InstanceID.
The function does not return any value.

`PTV_SetPowerOn(12345);`

`PTV_SetPowerOff(integer $InstanzID);`

Turn off the TV with the instance ID $InstanceID.
The function does not return any value.

`PTV_SetPowerOff(12345);`

`PTV_SetState(integer $InstanzID, boolean $Value);`


Toggles the TV to $Value (true = On; false = Off) with InstanceID $InstanceID.
The function does not return any value.

`PTV_SetState(12345, true);`

`PTV_SendKey(integer $InstanzID, string $Key);`

Sends to the TV with the instanceID $InstanceID the key code $Key.
The function does not return any value.

`PTV_SendKey(12345, 'NRC_CH_DOWN-ONOFF');`

#### Key Codes

Keycode               | Key
--------------------- | ----------------
NRC_CH_DOWN-ONOFF     | Channel Down
NRC_CH_UP-ONOFF       | Channel Up
NRC_VOLDOWN-ONOFF     | Volume Down
NRC_VOLUP-ONOFF       | Volume Up
NRC_MUTE-ONOFF        | Mute
NRC_TV-ONOFF          | TV
NRC_CHG_INPUT-ONOFF   | AV
NRC_RED-ONOFF         | Red
NRC_GREEN-ONOFF       | Green
NRC_YELLOW-ONOFF      | Yellow
NRC_BLUE-ONOFF        | Blue
NRC_VTOOLS-ONOFF      | VIERA Tools
NRC_CANCEL-ONOFF      | Cancel / Exit
NRC_SUBMENU-ONOFF     | Option
NRC_RETURN-ONOFF      | Return
NRC_ENTER-ONOFF       | Enter
NRC_RIGHT-ONOFF       | Right
NRC_LEFT-ONOFF        | Left
NRC_UP-ONOFF          | Up
NRC_DOWN-ONOFF        | Down
NRC_3D-ONOFF          | 3D
NRC_SD_CARD-ONOFF     | SD-Card
NRC_DISP_MODE-ONOFF   | Aspect ratio
NRC_MENU-ONOFF        | Menu
NRC_INTERNET-ONOFF    | VIERA connect
NRC_VIERA_LINK-ONOFF  | VIERA link
NRC_EPG-ONOFF         | EPG
NRC_TEXT-ONOFF        | Text
NRC_STTL-ONOFF        | Subtitles
NRC_INFO-ONOFF        | Info
NRC_INDEX-ONOFF       | Index
NRC_HOLD-ONOFF        | Image Freeze
NRC_R_TUNE-ONOFF      | Last view
NRC_POWER-ONOFF       | Power
NRC_REW-ONOFF         | Rewind
NRC_PLAY-ONOFF        | Play
NRC_FF-ONOFF          | Fast Forward
NRC_SKIP_PREV-ONOFF   | Skip Previous
NRC_PAUSE-ONOFF       | Pause
NRC_SKIP_NEXT-ONOFF   | Skip Next
NRC_STOP-ONOFF        | Stop
NRC_POWER-ONOFF       | Record
NRC_D0-ONOFF          | 0
NRC_D1-ONOFF          | 1
NRC_D2-ONOFF          | 2
NRC_D3-ONOFF          | 3
NRC_D4-ONOFF          | 4
NRC_D5-ONOFF          | 5
NRC_D6-ONOFF          | 6
NRC_D7-ONOFF          | 7
NRC_D8-ONOFF          | 8
NRC_D9-ONOFF          | 9
NRC_P_NR-ONOFF        | Noise reduction
NRC_OFFTIMER-ONOFF    | Off Timer
NRC_R_TUNE-ONOFF      | Look same "Info"
NRC_CHG_NETWORK-ONOFF |
NRC_CC-ONOFF          |
NRC_SAP-ONOFF         |
NRC_RECLIST-ONOFF     |
NRC_DRIVE-ONOFF       |
NRC_DATA-ONOFF        |
NRC_BD-ONOFF          |
NRC_FAVORITE-ONOFF    |
NRC_DIGA_CTL-ONOFF    |
NRC_VOD-ONOFF         |
NRC_ECO-ONOFF         |
NRC_GAME-ONOFF        |
NRC_EZ_SYNC-ONOFF     |
NRC_PICTAI-ONOFF      |
NRC_MPX-ONOFF         |
NRC_SPLIT-ONOFF       |
NRC_SWAP-ONOFF        |
NRC_R_SCREEN-ONOFF    |
NRC_30S_SKIP-ONOFF    |
NRC_PROG-ONOFF        |
NRC_TV_MUTE_ON-ONOFF  |
NRC_TV_MUTE_OFF-ONOFF |
NRC_DMS_CH_UP-ONOFF   |
NRC_DMS_CH_DOWN-ONOFF |


###  b. GUIDs and data exchange:

#### Panasonic:

GUID: `{0B401818-DF07-489E-BD93-4DD67BE50B29}` 