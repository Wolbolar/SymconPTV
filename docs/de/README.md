# SymconPTV
[![Version](https://img.shields.io/badge/Symcon-PHPModul-red.svg)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Version](https://img.shields.io/badge/Symcon%20Version-5.0%20%3E-green.svg)](https://www.symcon.de/forum/threads/38222-IP-Symcon-5-0-verf%C3%BCgbar)

SymconPTV ist eine Erweiterung für die Heimautomatisierung IP Symcon. Mithilfe dieser Erweiterung könnt Ihr euren Panasonic TV steuern.

## Dokumentation

**Inhaltverzeichnis**

1. [Funktionsumfang](#1-funktionsumfang)  
2. [Voraussetzungen](#2-voraussetzungen)  
3. [Installation](#3-installation)  
4. [Funktionsreferenz](#4-funktionsreferenz)
5. [Konfiguration](#5-konfiguartion)  
6. [Anhang](#6-anhang)  


### 1. Funktionsumfang

- Ein und Ausschalten des Fernsehers
- Senden von Befehlen an den Panasonic TV
- Ermittlung des Zustands eventbasiert mithilfe von DLNA

### 2. Voraussetzungen

- IP-Symcon ab Version 5.1
- bei IP-Symcon Versionen kleiner 5.1 ist der Branch _Old-Version_ zu wählen

## 3. Installation

### a. Laden des Moduls

Die Webconsole von IP-Symcon mit _http://<IP-Symcon IP>:3777/console/_ öffnen. 


Anschließend oben rechts auf das Symbol für den Modulstore (IP-Symcon > 5.1) klicken

![Store](img/store_icon.png?raw=true "open store")

Im Suchfeld nun

```
Panasonic TV
```  

eingeben

![Store](img/module_store_search.png?raw=true "module search")

und schließend das Modul auswählen und auf _Installieren_

![Store](img/install.png?raw=true "install")

drücken.


#### Alternatives Installieren über Modules Instanz (IP-Symcon < 5.1)

Die Webconsole von IP-Symcon mit _http://<IP-Symcon IP>:3777/console/_ öffnen. 

Anschließend den Objektbaum _Öffnen_.

![Objektbaum](img/objektbaum.png?raw=true "Objektbaum")	

Die Instanz _'Modules'_ unterhalb von Kerninstanzen im Objektbaum von IP-Symcon (>=Ver. 5.x) mit einem Doppelklick öffnen und das  _Plus_ Zeichen drücken.

![Modules](img/Modules.png?raw=true "Modules")	

![Plus](img/plus.png?raw=true "Plus")	

![ModulURL](img/add_module.png?raw=true "Add Module")
 
Im Feld die folgende URL eintragen und mit _OK_ bestätigen:

```
https://github.com/Wolbolar/SymconPTV
```  
	        
Anschließend erscheint ein Eintrag für das Modul in der Liste der Instanz _Modules_    

Es wird im Standard der Zweig (Branch) _master_ geladen, dieser enthält aktuelle Änderungen und Anpassungen.
Nur der Zweig _master_ wird aktuell gehalten.

![Master](img/master.png?raw=true "master") 

Sollte eine ältere Version von IP-Symcon die kleiner ist als Version 5.1 eingesetzt werden, ist auf das Zahnrad rechts in der Liste zu klicken.
Es öffnet sich ein weiteres Fenster,

![SelectBranch](img/select_branch.png?raw=true "select branch") 

hier kann man auf einen anderen Zweig wechseln, für ältere Versionen kleiner als 5.1 ist hier
_Old-Version_ auszuwählen. 

### b. Einrichtung in IPS
	
In IP-Symcon nun _Instanz hinzufügen_ (_Rechtsklick -> Objekt hinzufügen -> Instanz_) auswählen unter der Kategorie, unter der man die Panasonic TV hinzufügen will,
und _Panasonic TV_ auswählen.
 
Es wird dabei automatisch ein Webhook für den Panasonic TV angelegt. Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

##### Statusvariablen

Name         | Typ       | Beschreibung
------------ | --------- | ----------------
Zustand      | Integer   | Gibt den Zustand des Fernsehers an.

##### Profile:

Es werden keine weiteren Profile benötigt.

## 4. Funktionsreferenz

### Panasonic:

`PTV_SetPowerOn(integer $InstanzID);`

Schaltet den Fernseher mit der InstanzID $InstanzID ein.
Die Funktion liefert keinerlei Rückgabewert.

Beispiel:
`PTV_SetPowerOn(12345);`

`PTV_SetPowerOff(integer $InstanzID);`

Schaltet den Fernseher mit der InstanzID $InstanzID aus.
Die Funktion liefert keinerlei Rückgabewert.

Beispiel: 
`PTV_SetPowerOff(12345);`

## 5. Konfiguration:

### Panasonic:

| Eigenschaft | Typ     | Standardwert | Funktion                                  |
| :---------: | :-----: | :----------: | :---------------------------------------: |
| Host        | string  |              | IP Adresse des Panasonic Fernsehers       |

### PTVHook

Name                   | Beschreibung
---------------------- | ---------------------------------
Host                   | Die Adresse des Symconservers für den Hookaufruf
Port                   | Der dazugehörige Port (Default 3777)


## 6. Anhang

###  a. Funktionen:

#### Panasonic:

`PTV_SetPowerOn(integer $InstanzID);`

Schaltet den Fernseher mit der InstanzID $InstanzID ein.
Die Funktion liefert keinerlei Rückgabewert.

`PTV_SetPowerOn(12345);`

`PTV_SetPowerOff(integer $InstanzID);`

Schaltet den Fernseher mit der InstanzID $InstanzID aus.
Die Funktion liefert keinerlei Rückgabewert.

`PTV_SetPowerOff(12345);`

`PTV_SetState(integer $InstanzID, boolean $Value);`

Schaltet den Fernseher mit der InstanzID $InstanzID auf den Wert $Value (true = An; false = Aus).
Die Funktion liefert keinerlei Rückgabewert.

`PTV_SetState(12345, true);`

`PTV_SendKey(integer $InstanzID, string $Key);`

Sendet zum Fernseher mit der InstanzID $InstanzID den Tastencode $Key.
Die Funktion liefert keinerlei Rückgabewert.

`PTV_SendKey(12345, 'NRC_CH_DOWN-ONOFF');`

#### Tastencodes

Tastencode            | Taste
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

###  b. GUIDs und Datenaustausch:

#### Panasonic:

GUID: `{0B401818-DF07-489E-BD93-4DD67BE50B29}` 