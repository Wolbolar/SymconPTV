# SymconPTV

SymconPTV ist eine Erweiterung für die Heimautomatisierung IP Symcon. Mithilfe dieser Erweiterung könnt Ihr euren Panasonic TV steuern.

### Inhaltverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)
5. [Statusvariablen und Profile](#5-statusvariablen-und-profile)
6. [WebFront](#6-webfront)
7. [PHP-Befehlsreferenz](#7-php-befehlsreferenz)
7. [Tastencodes](#8-tastencodes)

### 1. Funktionsumfang

- Ein und Ausschalten des Fernsehers
- Ermittlung des Zustands eventbasiert mithilfe von DLNA

### 2. Voraussetzungen

- IP-Symcon ab Version 4.0 (ggf. auch früher)

### 3. Software-Installation

Über das Modul-Control folgende URL hinzufügen.  
`git://github.com/traxanos/SymconPTV.git`  

### 4. Einrichten der Instanzen in IP-Symcon

- Anlage eines Panasonic TV
- Dabei wir automatisch ein Panasonic TV Hook angelegt.

__Konfigurationsseite (PTV)__:

Panasonic TV

Name                   | Beschreibung
---------------------- | ---------------------------------
Host                   | Die Adresse des Panasonic Fernsehers

__Konfigurationsseite (PTVHook)__:

Name                   | Beschreibung
---------------------- | ---------------------------------
Host                   | Die Adresse des Symconservers für den Hookaufruf
Port                   | Der dazugehörige Port (Default 3777)

### 5. Statusvariablen und Profile

Die Statusvariablen/Kategorien werden automatisch angelegt. Das Löschen einzelner kann zu Fehlfunktionen führen.

##### Statusvariablen

Name         | Typ       | Beschreibung
------------ | --------- | ----------------
Zustand      | Integer   | Gibt den Zustand des Fernsehers an.

##### Profile:

Es werden keine weiteren Profile benötigt.

### 6. WebFront

Über das WebFront kann der Fernseher ein- und ausgeschaltet werden.

### 7. PHP-Befehlsreferenz

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

##### bekannte Tastencodes für SendKey

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
NRC_SD_CARD-ONOFF     | Media Player
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
NRC_APPS-ONOFF        | Apps Menü
NRC_HOME-ONOFF        | Home
NRC_SURROUND-ONOFF    | Surround Menü
NRC_GUIDE-ONOFF       | eHelp
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


`PTV_LaunchApp(integer $InstanzID, string $productid);`
Startet auf dem Fernseher mit der InstanzID $InstanzID die zur ProductID $productid gehörige Applikation. 
Die Funktion liefert keinen Rückgabewert.

##### bekannte ProductIDs für PTV_LaunchApp

ProductID            | Applikation
---------------------| ----------------
0010000100000001     | Amazon Prime
0010000200000001     | Netflix
0010000300000001     | Cinema Now
0010000600000001     | Pandora
0010000F00000001     | Hulu Plus
0010001200000001     | WSJ Live
0010001300000001     | Vudu HD
0020000600000001     | Arte
0020000E00000001     | Bild
0020001200000001     | Cine Trailer
0020007100000001     | Meteo News
0020004000000001     | RossiaTV
0020004700000001     | Chili
002002A00000002      | RakutenTV
002003F00000001      | Maxdome
0070000200000001     | YouTube
0070000600000001     | Skype
0070000900000001     | Weather
0070001406000008     | Match Number Beginners
007000C00000001      | Accu Weather
00760108070000001    | ARD
0077777700000001     | Produkt Support
0077777700000002     | Web Browser
00777777001140002    | Browser
0076002307000001     | Digital Concert
0076010507000001     | Plex
0076011607000001     | Netzkino
0387878700000001     | TV
0387878700000003     | TV Guide
0387878700000009     | Menü
0387878700000013     | Media Player Aufnahmen (Recorded TV)
0387878700000014     | DLNA Player
0387878700000016     | Viera Link
0387878700000022     | Jotter
0387878700000026     | Paint
0387878700000032     | Media Player
0387878700000036     | Put Three
0387878700000037     | Family Riversi
0387878700000038     | Touch Connect
0387878700000049     | Mirroring
0387878700000050     | Multi Window
0387878700000032     | Media Player
0387878700000102     | Apps Market
0387878700150020     | Kalendar
0387878700000009     | Haupt Menü
0387878700000032     | Media Player
0387878700000049     | TV Spiegelung
0387878700000014     | Media Server
0387878700000064     | Screen Market

