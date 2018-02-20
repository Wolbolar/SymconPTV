<?php
class PTV extends IPSModule
{
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString("Host", "");
    }

    public function Destroy()
    {
        parent::Destroy();
    }

    public function GetHost()
    {
        return $this->ReadPropertyString('Host');
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->ConnectParent("{111046C4-5DCE-4622-9741-4A9BCCAFA92D}");
    }

    public function ReceiveData($json)
    {
        $data = json_decode($json);
        if ($data->DeviceID != $this->InstanceID) {
            return;
        }

        $properties = $data->Properties;

        if (@$properties->X_ScreenState) {
            $stateId = $this->RegisterVariableBoolean("STATE", "Zustand", "~Switch");
            $this->EnableAction("STATE");
            SetValueBoolean($stateId, $properties->X_ScreenState == 'on');
        }
    }

    public function RequestAction($key, $value)
    {
        switch ($key) {
      case 'STATE':
        $this->SendKey('NRC_POWER-ONOFF');
        break;
    }
    }

    public function GetValue(string $key)
    {
        return GetValue(@IPS_GetObjectIDByIdent($key, $this->InstanceID));
    }

    public function SetPowerOn()
    {
        $this->SetState(true);
    }

    public function SetPowerOff()
    {
        $this->SetState(false);
    }

    public function SetState(bool $on)
    {
        if ($this->GetValue('STATE') != $on) {
            $this->SendKey('NRC_POWER-ONOFF');
        }
    }

    public function SendKey(string $keyCode)
    {
        return $this->SoapRequest(
      'nrc/control_0',
      'panasonic-com:service:p00NetworkControl:1',
      'X_SendKey',
      array(
        'args' => '<X_KeyEvent>' . $keyCode . '</X_KeyEvent>',
        'returnXml' => true
      )
    );
    }

    private function SoapRequest($path, $urn, $action, $option = array())
    {
        $input = '<'.'?xml version="1.0" encoding="utf-8"?'.'>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
 <s:Body>
  <u:'.$action.' xmlns:u="urn:'.$urn.'">
  '.$option['args'].'
  </u:'.$action.'>
 </s:Body>
</s:Envelope>';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://' . $this->GetHost() . ':55000/' . $path);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('SOAPACTION: "urn:' . $urn . '#' . $action . '"'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);

        if ($option['returnXml']) {
            return $data;
        } else {
            $xml = simplexml_load_string($data);
            if ($xml === false) {
                return false;
            }
            $ns = $xml->getNamespaces(true);
            $soap = $xml->children($ns['s']);
            $res = $soap->children($ns['u'])->children();
            return $res[0];
        }
    }

    public function GetVolume()
    {
        return $this->SoapRequest(
            'dmr/control_0',
            'schemas-upnp-org:service:RenderingControl:1',
            'GetVolume',
            array('args' => '<InstanceID>0</InstanceID><Channel>Master</Channel>',
                  'returnXml' => false)
        );
    }

    public function GetMute()
    {
        return $this->SoapRequest(
            'dmr/control_0',
            'schemas-upnp-org:service:RenderingControl:1',
            'GetMute',
            array('args' => '<InstanceID>0</InstanceID><Channel>Master</Channel>',
                  'returnXml' => false)
        );
    }

    public function SetMute(bool $enable)
    {
        $data = ($enable) ? '1' : '0';
        return $this->SoapRequest(
            'dmr/control_0',
            'schemas-upnp-org:service:RenderingControl:1',
            'SetMute',
            array('args' => '<InstanceID>0</InstanceID><Channel>Master</Channel><DesiredMute>'.$data.'</DesiredMute>',
                  'returnXml' => true)
        );
    }

    public function SetVolume(integer $volume)
    {
        $volume = intval($volume);
        if ($volume > 100 || $volume < 0)
        {
            $this->SendDebug("Panaonic TV", "Bad request to volume control. Must be between 0 and 100",0);
        }
        return $this->SoapRequest(
            'dmr/control_0',
            'schemas-upnp-org:service:RenderingControl:1',
            'SetVolume',
            array('args' => '<InstanceID>0</InstanceID><Channel>Master</Channel><DesiredVolume>'.$volume.'</DesiredVolume>',
                  'returnXml' => true)
        );
    }

    public function VolumeUp()
    {
        $key = "NRC_VOLUP-ONOFF"; // volume up
        $this->SendKey($key);
    }

    public function VolumeDown()
    {
        $key = "NRC_VOLDOWN-ONOFF";  // volume down
        $this->SendKey($key);
    }

    public function ChannelDown()
    {
        $key = "NRC_CH_DOWN-ONOFF";  // channel down
        $this->SendKey($key);
    }

    public function ChannelUp()
    {
        $key = "NRC_CH_UP-ONOFF";  // channel up
        $this->SendKey($key);
    }

    public function TV()
    {
        $key = "NRC_TV-ONOFF";  // TV
        $this->SendKey($key);
    }

    public function AV()
    {
        $key = "NRC_CHG_INPUT-ONOFF";  // AV
        $this->SendKey($key);
    }

    public function Red()
    {
        $key = "NRC_RED-ONOFF";  // red
        $this->SendKey($key);
    }

    public function Green()
    {
        $key = "NRC_GREEN-ONOFF";  // green
        $this->SendKey($key);
    }

    public function Yellow()
    {
        $key = "NRC_YELLOW-ONOFF";  // yellow
        $this->SendKey($key);
    }

    public function Blue()
    {
        $key = "NRC_BLUE-ONOFF";  // blue
        $this->SendKey($key);
    }

    public function VIERA_Tools()
    {
        $key = "NRC_VTOOLS-ONOFF";  // VIERA tools
        $this->SendKey($key);
    }

    public function Cancel()
    {
        $key = "NRC_CANCEL-ONOFF";  // Cancel / Exit
        $this->SendKey($key);
    }

    public function Option()
    {
        $key = "NRC_SUBMENU-ONOFF";  // Option
        $this->SendKey($key);
    }

    public function KeyReturn()
    {
        $key = "NRC_RETURN-ONOFF";  // Return
        $this->SendKey($key);
    }

    public function Control_Center()
    {
        $key = "NRC_ENTER-ONOFF";  // Control Center click / enter
        $this->SendKey($key);
    }

    public function Control_Right()
    {
        $key = "NRC_RIGHT-ONOFF";  // Control RIGHT
        $this->SendKey($key);
    }

    public function Control_Left()
    {
        $key = "NRC_LEFT-ONOFF";  // Control LEFT
        $this->SendKey($key);
    }

    public function Control_Up()
    {
        $key = "NRC_UP-ONOFF";  // Control UP
        $this->SendKey($key);
    }

    public function Control_Down()
    {
        $key = "NRC_DOWN-ONOFF";  // Control DOWN
        $this->SendKey($key);
    }

    public function Button_3D()
    {
        $key = "NRC_3D-ONOFF";  // 3D button
        $this->SendKey($key);
    }

    public function SD()
    {
        $key = "NRC_SD_CARD-ONOFF";  // SD-card
        $this->SendKey($key);
    }

    public function Display_Mode()
    {
        $key = "NRC_DISP_MODE-ONOFF";  // Display mode / Aspect ratio
        $this->SendKey($key);
    }

    public function Menu()
    {
        $key = "NRC_MENU-ONOFF";  // Menu
        $this->SendKey($key);
    }

    public function VIERA_Connect()
    {
        $key = "NRC_INTERNET-ONOFF";  // VIERA connect
        $this->SendKey($key);
    }

    public function VIERA_Link()
    {
        $key = "NRC_VIERA_LINK-ONOFF";  // VIERA link
        $this->SendKey($key);
    }

    public function EPG()
    {
        $key = "NRC_EPG-ONOFF";  // Guide / EPG
        $this->SendKey($key);
    }

    public function Text()
    {
        $key = "NRC_TEXT-ONOFF";  // Text / TTV
        $this->SendKey($key);
    }

    public function Subtitles()
    {
        $key = "NRC_STTL-ONOFF";  // STTL / Subtitles
        $this->SendKey($key);
    }

    public function Info()
    {
        $key = "NRC_INFO-ONOFF";  // Info
        $this->SendKey($key);
    }

    public function TTV_Index()
    {
        $key = "NRC_INDEX-ONOFF";  // TTV index
        $this->SendKey($key);
    }

    public function TTV_Hold()
    {
        $key = "NRC_HOLD-ONOFF";  // TTV hold / image freeze
        $this->SendKey($key);
    }

    public function Last_View()
    {
        $key = "NRC_R_TUNE-ONOFF";  // Last view
        $this->SendKey($key);
    }

    public function Power_Off()
    {
        $key = "NRC_POWER-ONOFF";  // Power off
        $this->SendKey($key);
    }

    public function Rewind()
    {
        $key = "NRC_REW-ONOFF";  // rewind
        $this->SendKey($key);
    }

    public function Play()
    {
        $key = "NRC_PLAY-ONOFF";  // play
        $this->SendKey($key);
    }

    public function Fast_Forward()
    {
        $key = "NRC_FF-ONOFF";  // fast forward
        $this->SendKey($key);
    }

    public function Skip_Previous()
    {
        $key = "NRC_SKIP_PREV-ONOFF";  // skip previous
        $this->SendKey($key);
    }

    public function Pause()
    {
        $key = "NRC_PAUSE-ONOFF";  // pause
        $this->SendKey($key);
    }

    public function Skip_Next()
    {
        $key = "NRC_SKIP_NEXT-ONOFF";  // skip next
        $this->SendKey($key);
    }

    public function Stop()
    {
        $key = "NRC_STOP-ONOFF";  // stop
        $this->SendKey($key);
    }

    public function Record()
    {
        $key = "NRC_REC-ONOFF";  // record
        $this->SendKey($key);
    }

    public function Key1()
    {
        $key = "NRC_D1-ONOFF";  // numeric button 1
        $this->SendKey($key);
    }

    public function Key2()
    {
        $key = "NRC_D2-ONOFF";  // numeric button 2
        $this->SendKey($key);
    }

    public function Key3()
    {
        $key = "NRC_D3-ONOFF";  // numeric button 3
        $this->SendKey($key);
    }

    public function Key4()
    {
        $key = "NRC_D4-ONOFF";  // numeric button 4
        $this->SendKey($key);
    }

    public function Key5()
    {
        $key = "NRC_D5-ONOFF";  // numeric button 5
        $this->SendKey($key);
    }

    public function Key6()
    {
        $key = "NRC_D6-ONOFF";  // numeric button 6
        $this->SendKey($key);
    }

    public function Key7()
    {
        $key = "NRC_D7-ONOFF";  // numeric button 7
        $this->SendKey($key);
    }

    public function Key8()
    {
        $key = "NRC_D8-ONOFF";  // numeric button 8
        $this->SendKey($key);
    }

    public function Key9()
    {
        $key = "NRC_D9-ONOFF";  // numeric button 9
        $this->SendKey($key);
    }

    public function Key0()
    {
        $key = "NRC_D0-ONOFF";  // numeric button 0
        $this->SendKey($key);
    }

    public function Noise_Reduction()
    {
        $key = "NRC_P_NR-ONOFF";  // Noise reduction
        $this->SendKey($key);
    }

    public function Off_Timer()
    {
        $key = "NRC_OFFTIMER-ONOFF";  // off timer
        $this->SendKey($key);
    }

    public function Data()
    {
        $key = "NRC_DATA-ONOFF";  // data
        $this->SendKey($key);
    }

    public function BD()
    {
        $key = "NRC_BD-ONOFF";  // BD
        $this->SendKey($key);
    }

    public function Favorite()
    {
        $key = "NRC_FAVORITE-ONOFF";  // Favorite
        $this->SendKey($key);
    }

    public function Game()
    {
        $key = "NRC_GAME-ONOFF";  // Game
        $this->SendKey($key);
    }

    public function VOD()
    {
        $key = "NRC_VOD-ONOFF";  // VOD
        $this->SendKey($key);
    }

    public function Eco()
    {
        $key = "NRC_ECO-ONOFF";  // Eco
        $this->SendKey($key);
    }

    /*
    "NRC_R_TUNE-ONOFF", // Seems to do the same as INFO
    "NRC_CHG_NETWORK-ONOFF",
    "NRC_CC-ONOFF",
    "NRC_SAP-ONOFF",
    "NRC_RECLIST-ONOFF",
    "NRC_DRIVE-ONOFF",
    "NRC_DIGA_CTL-ONOFF",
    "NRC_EZ_SYNC-ONOFF",
    "NRC_PICTAI-ONOFF",
    "NRC_MPX-ONOFF",
    "NRC_SPLIT-ONOFF",
    "NRC_SWAP-ONOFF",
    "NRC_R_SCREEN-ONOFF",
    "NRC_30S_SKIP-ONOFF",
    "NRC_PROG-ONOFF",
    "NRC_TV_MUTE_ON-ONOFF",
    "NRC_TV_MUTE_OFF-ONOFF",
    "NRC_DMS_CH_UP-ONOFF",
    "NRC_DMS_CH_DOWN-ONOFF"
    */

}
