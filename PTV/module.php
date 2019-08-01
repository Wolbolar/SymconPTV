<?php

declare(strict_types=1);

class PTV extends IPSModule
{
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString('Host', '');
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
        $this->ConnectParent('{111046C4-5DCE-4622-9741-4A9BCCAFA92D}');

        if (IPS_GetKernelRunlevel() !== KR_READY) {
            return;
        }
        $this->RegisterVariableBoolean('STATE', $this->Translate('State'), '~Switch');
        $this->EnableAction('STATE');
        $this->RegisterVariableInteger('VOLUME', $this->Translate('Volume'), '~Intensity.100');
        $this->EnableAction('VOLUME');
        $PanasonicTV_navi_ass = [
            [0, $this->Translate('Up'), '', -1],
            [1, $this->Translate('Left'), '', -1],
            [2, $this->Translate('Right'), '', -1],
            [3, $this->Translate('Down'), '', -1],
            [4, $this->Translate('Ok'), '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Navigation', 'Move', '', '', 0, 4, 0, 0, $PanasonicTV_navi_ass);
        $this->RegisterVariableInteger('PanasonicTVNavigation', $this->Translate('Navigation'), 'PanasonicTV.Navigation', 2);
        $this->EnableAction('PanasonicTVNavigation');
        $PanasonicTV_vol_ass = [
            [0, $this->Translate('Volume Up'), '', -1],
            [1, $this->Translate('Volume Down'), '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Volume', 'Intensity', '', '', 0, 1, 0, 0, $PanasonicTV_vol_ass);
        $this->RegisterVariableInteger('PanasonicTVVolume', $this->Translate('Volume'), 'PanasonicTV.Volume', 3);
        $this->EnableAction('PanasonicTVVolume');
        $PanasonicTV_channel_ass = [
            [0, $this->Translate('Channel Up'), '', -1],
            [1, $this->Translate('Channel Down'), '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Channel', 'Execute', '', '', 0, 1, 0, 0, $PanasonicTV_channel_ass);
        $this->RegisterVariableInteger('PanasonicTVChannel', $this->Translate('Channel'), 'PanasonicTV.Channel', 4);
        $this->EnableAction('PanasonicTVChannel');
        $PanasonicTV_color_ass = [
            [0, $this->Translate('Red'), '', 16711680],
            [1, $this->Translate('Green'), '', 65280],
            [2, $this->Translate('Yellow'), '', 16776960],
            [3, $this->Translate('Blue'), '', 255]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Color', 'Paintbrush', '', '', 0, 3, 0, 0, $PanasonicTV_color_ass);
        $this->RegisterVariableInteger('PanasonicTVColor', $this->Translate('Color'), 'PanasonicTV.Color', 5);
        $this->EnableAction('PanasonicTVColor');
        $PanasonicTV_playback_ass = [
            [0, $this->Translate('Rewind'), '', -1],
            [1, $this->Translate('Pause'), '', -1],
            [2, $this->Translate('Play'), '', -1],
            [3, $this->Translate('Stop'), '', -1],
            [4, $this->Translate('Fast Forward'), '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Playback', 'Script', '', '', 0, 4, 0, 0, $PanasonicTV_playback_ass);
        $this->RegisterVariableInteger('PanasonicTVPlayback', $this->Translate('Playback'), 'PanasonicTV.Playback', 6);
        $this->EnableAction('PanasonicTVPlayback');
        $PanasonicTV_numeric_ass = [
            [0, '0', '', -1],
            [1, '1', '', -1],
            [2, '2', '', -1],
            [3, '3', '', -1],
            [4, '4', '', -1],
            [5, '5', '', -1],
            [6, '6', '', -1],
            [7, '7', '', -1],
            [8, '8', '', -1],
            [9, '9', '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Numeric', 'Calendar', '', '', 0, 9, 0, 0, $PanasonicTV_numeric_ass);
        $this->RegisterVariableInteger('PanasonicTVNumeric', $this->Translate('Numeric'), 'PanasonicTV.Numeric', 7);
        $this->EnableAction('PanasonicTVNumeric');
        $this->RegisterVariableBoolean('MUTE', $this->Translate('Mute'), '~Switch');
        $this->EnableAction('MUTE');
        $PanasonicTV_menu_ass = [
            [0, $this->Translate('Home'), '', -1],
            [1, $this->Translate('Info'), '', -1]];
        $this->RegisterProfileIntegerAss('PanasonicTV.Menu', 'Database', '', '', 0, 1, 0, 0, $PanasonicTV_menu_ass);
        $this->RegisterVariableInteger('PanasonicTVMenu', $this->Translate('Menu'), 'PanasonicTV.Menu', 8);
        $this->EnableAction('PanasonicTVMenu');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {

        switch ($Message) {
            case IM_CHANGESTATUS:
                if ($Data[0] === IS_ACTIVE) {
                    $this->ApplyChanges();
                }
                break;

            case IPS_KERNELMESSAGE:
                if ($Data[0] === KR_READY) {
                    $this->ApplyChanges();
                }
                break;

            default:
                break;
        }
    }

    public function ReceiveData($json)
    {
        $data = json_decode($json);
        if ($data->DeviceID != $this->InstanceID) {
            return;
        }

        $properties = $data->Properties;

        if (@$properties->X_ScreenState) {

            SetValueBoolean($this->GetIDForIdent('STATE'), $properties->X_ScreenState == 'on');
        }
    }

    public function RequestAction($Ident, $Value)
    {
        $this->SetValue($Ident, $Value);
        switch ($Ident) {
            case 'STATE':
                $this->Power_Off();
                break;
            case 'VOLUME':
                $this->SetVolume($Value);
                break;
            case 'MUTE':
                if ($Value) {
                    $this->SetMute(true);
                } else {
                    $this->SetMute(false);
                }
                break;
            case 'PanasonicTVNavigation':
                if ($Value == 0) {
                    $this->Up();
                } elseif ($Value == 1) {
                    $this->Left();
                } elseif ($Value == 2) {
                    $this->Right();
                } elseif ($Value == 3) {
                    $this->Down();
                } elseif ($Value == 4) {
                    $this->Ok();
                }
                break;
            case 'PanasonicTVVolume':
                if ($Value == 0) {
                    $this->VolumeUp();
                } elseif ($Value == 1) {
                    $this->VolumeDown();
                }
                break;
            case 'PanasonicTVChannel':
                if ($Value == 0) {
                    $this->ChannelUp();
                } elseif ($Value == 1) {
                    $this->ChannelDown();
                }
                break;
            case 'PanasonicTVColor':
                if ($Value == 0) {
                    $this->Red();
                } elseif ($Value == 1) {
                    $this->Green();
                } elseif ($Value == 2) {
                    $this->Yellow();
                } elseif ($Value == 3) {
                    $this->Blue();
                }
                break;
            case 'PanasonicTVPlayback':
                if ($Value == 0) {
                    $this->Rewind();
                } elseif ($Value == 1) {
                    $this->Pause();
                } elseif ($Value == 2) {
                    $this->Play();
                } elseif ($Value == 3) {
                    $this->Stop();
                } elseif ($Value == 4) {
                    $this->Fast_Forward();
                }
                break;
            case 'PanasonicTVNumeric':
                if ($Value == 0) {
                    $this->Key0();
                } elseif ($Value == 1) {
                    $this->Key1();
                } elseif ($Value == 2) {
                    $this->Key2();
                } elseif ($Value == 3) {
                    $this->Key3();
                } elseif ($Value == 4) {
                    $this->Key4();
                } elseif ($Value == 5) {
                    $this->Key5();
                } elseif ($Value == 6) {
                    $this->Key6();
                } elseif ($Value == 7) {
                    $this->Key7();
                } elseif ($Value == 8) {
                    $this->Key8();
                } elseif ($Value == 9) {
                    $this->Key9();
                }
                break;
            case 'PanasonicTVMenu':
                if ($Value == 0) {
                    $this->Menu();
                } elseif ($Value == 2) {
                    $this->Info();
                }
                break;
            default:
                $this->SendDebug('PanasonicTV', 'Invalid ident', 0);
        }
    }

    public function GetValue($Ident)
    {
        return GetValue(@IPS_GetObjectIDByIdent($Ident, $this->InstanceID));
    }

    public function SetPowerOn()
    {
        $this->SetState(true);
    }

    public function SetPowerOff()
    {
        $this->SetState(false);
    }

    public function SetState($on)
    {
        if ($this->GetValue('STATE') != $on) {
            $this->SendKey('NRC_POWER-ONOFF');
        }
    }

    public function SendKey($keyCode)
    {
        return $this->SoapRequest(
            'nrc/control_0', 'panasonic-com:service:p00NetworkControl:1', 'X_SendKey', [
                               'args'      => '<X_KeyEvent>' . $keyCode . '</X_KeyEvent>',
                               'returnXml' => true]
        );
    }

    public function Netflix()
    {
        $productid = 'App.NETFLIX';  // Netflix
        $this->LaunchApp($productid);
    }

    public function Recorded_TV()
    {
        $productid = 'App.RECORDED_TV';  // recorded tv
        $this->LaunchApp($productid);
    }

    /** Launch App
     *
     * @param $productid
     *
     * @return mixed|SimpleXMLElement
     */
    public function LaunchApp($productid)
    {
        return $this->SoapRequest(
            'nrc/control_0', 'panasonic-com:service:p00NetworkControl:1', 'X_LaunchApp', [
                               'args'      => '<X_AppType>vc_app</X_AppType><X_LaunchKeyword>product_id=' . $productid . '</X_LaunchKeyword>',
                               'returnXml' => true]
        );
    }

    private function SoapRequest($path, $urn, $action, $option = [])
    {
        $input = '<' . '?xml version="1.0" encoding="utf-8"?' . '>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
 <s:Body>
  <u:' . $action . ' xmlns:u="urn:' . $urn . '">
  ' . $option['args'] . '
  </u:' . $action . '>
 </s:Body>
</s:Envelope>';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://' . $this->GetHost() . ':55000/' . $path);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['SOAPACTION: "urn:' . $urn . '#' . $action . '"']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);

        if ($option['returnXml']) {
            return $data;
        } else {
            $xml = simplexml_load_string($data);
            if ($xml === false) {
                trigger_error('SoapRequest failed (action = ' . $action . ')');
            }
            $ns   = $xml->getNamespaces(true);
            $soap = $xml->children($ns['s']);
            $res  = $soap->children($ns['u'])->children();
            return $res[0];
        }
    }

    public function GetVolume()
    {
        return (int) $this->SoapRequest(
            'dmr/control_0', 'schemas-upnp-org:service:RenderingControl:1', 'GetVolume', [
                               'args'      => '<InstanceID>0</InstanceID><Channel>Master</Channel>',
                               'returnXml' => false]
        );
    }

    public function GetMute()
    {
        return (bool) ((int) $this->SoapRequest(
            'dmr/control_0', 'schemas-upnp-org:service:RenderingControl:1', 'GetMute', [
                               'args'      => '<InstanceID>0</InstanceID><Channel>Master</Channel>',
                               'returnXml' => false]
        ));
    }

    public function SetMute($enable)
    {
        $data = ($enable) ? '1' : '0';
        return $this->SoapRequest(
            'dmr/control_0', 'schemas-upnp-org:service:RenderingControl:1', 'SetMute', [
                               'args'      => '<InstanceID>0</InstanceID><Channel>Master</Channel><DesiredMute>' . $data . '</DesiredMute>',
                               'returnXml' => true]
        );
    }

    public function SetVolume($volume)
    {
        $volume = intval($volume);
        if ($volume > 100 || $volume < 0) {
            $this->SendDebug('Panaonic TV', 'Bad request to volume control. Must be between 0 and 100', 0);
        }
        return $this->SoapRequest(
            'dmr/control_0', 'schemas-upnp-org:service:RenderingControl:1', 'SetVolume', [
                               'args'      => '<InstanceID>0</InstanceID><Channel>Master</Channel><DesiredVolume>' . $volume . '</DesiredVolume>',
                               'returnXml' => true]
        );
    }

    public function VolumeUp()
    {
        $key = 'NRC_VOLUP-ONOFF'; // volume up
        $this->SendKey($key);
    }

    public function VolumeDown()
    {
        $key = 'NRC_VOLDOWN-ONOFF';  // volume down
        $this->SendKey($key);
    }

    /**
     * Up.
     */
    public function Up()
    {
        $key = 'NRC_UP-ONOFF';  // up
        $this->SendKey($key);
    }

    /**
     * Down.
     */
    public function Down()
    {
        $key = 'NRC_DOWN-ONOFF';  // down
        $this->SendKey($key);
    }

    /**
     * Right.
     */
    public function Right()
    {
        $key = 'NRC_RIGHT-ONOFF';  // right
        $this->SendKey($key);
    }

    /**
     * Left.
     */
    public function Left()
    {
        $key = 'NRC_LEFT-ONOFF';  // left
        $this->SendKey($key);
    }

    /**
     * Ok.
     */
    public function Ok()
    {
        $key = 'NRC_ENTER-ONOFF';  // enter
        $this->SendKey($key);
    }

    /**
     * Select.
     */
    public function Select()
    {
        $key = 'NRC_ENTER-ONOFF';  // enter
        $this->SendKey($key);
    }

    public function ChannelDown()
    {
        $key = 'NRC_CH_DOWN-ONOFF';  // channel down
        $this->SendKey($key);
    }

    public function ChannelUp()
    {
        $key = 'NRC_CH_UP-ONOFF';  // channel up
        $this->SendKey($key);
    }

    public function TV()
    {
        $key = 'NRC_TV-ONOFF';  // TV
        $this->SendKey($key);
    }

    public function AV()
    {
        $key = 'NRC_CHG_INPUT-ONOFF';  // AV
        $this->SendKey($key);
    }

    public function Red()
    {
        $key = 'NRC_RED-ONOFF';  // red
        $this->SendKey($key);
    }

    public function Green()
    {
        $key = 'NRC_GREEN-ONOFF';  // green
        $this->SendKey($key);
    }

    public function Yellow()
    {
        $key = 'NRC_YELLOW-ONOFF';  // yellow
        $this->SendKey($key);
    }

    public function Blue()
    {
        $key = 'NRC_BLUE-ONOFF';  // blue
        $this->SendKey($key);
    }

    public function VIERA_Tools()
    {
        $key = 'NRC_VTOOLS-ONOFF';  // VIERA tools
        $this->SendKey($key);
    }

    public function Cancel()
    {
        $key = 'NRC_CANCEL-ONOFF';  // Cancel / Exit
        $this->SendKey($key);
    }

    public function Option()
    {
        $key = 'NRC_SUBMENU-ONOFF';  // Option
        $this->SendKey($key);
    }

    public function KeyReturn()
    {
        $key = 'NRC_RETURN-ONOFF';  // Return
        $this->SendKey($key);
    }

    public function Control_Center()
    {
        $key = 'NRC_ENTER-ONOFF';  // Control Center click / enter
        $this->SendKey($key);
    }

    public function Control_Right()
    {
        $key = 'NRC_RIGHT-ONOFF';  // Control RIGHT
        $this->SendKey($key);
    }

    public function Control_Left()
    {
        $key = 'NRC_LEFT-ONOFF';  // Control LEFT
        $this->SendKey($key);
    }

    public function Control_Up()
    {
        $key = 'NRC_UP-ONOFF';  // Control UP
        $this->SendKey($key);
    }

    public function Control_Down()
    {
        $key = 'NRC_DOWN-ONOFF';  // Control DOWN
        $this->SendKey($key);
    }

    public function Button_3D()
    {
        $key = 'NRC_3D-ONOFF';  // 3D button
        $this->SendKey($key);
    }

    public function SD()
    {
        $key = 'NRC_SD_CARD-ONOFF';  // SD-card
        $this->SendKey($key);
    }

    public function Display_Mode()
    {
        $key = 'NRC_DISP_MODE-ONOFF';  // Display mode / Aspect ratio
        $this->SendKey($key);
    }

    public function Menu()
    {
        $key = 'NRC_MENU-ONOFF';  // Menu
        $this->SendKey($key);
    }

    public function VIERA_Connect()
    {
        $key = 'NRC_INTERNET-ONOFF';  // VIERA connect
        $this->SendKey($key);
    }

    public function VIERA_Link()
    {
        $key = 'NRC_VIERA_LINK-ONOFF';  // VIERA link
        $this->SendKey($key);
    }

    public function EPG()
    {
        $key = 'NRC_EPG-ONOFF';  // Guide / EPG
        $this->SendKey($key);
    }

    public function Text()
    {
        $key = 'NRC_TEXT-ONOFF';  // Text / TTV
        $this->SendKey($key);
    }

    public function Subtitles()
    {
        $key = 'NRC_STTL-ONOFF';  // STTL / Subtitles
        $this->SendKey($key);
    }

    public function Info()
    {
        $key = 'NRC_INFO-ONOFF';  // Info
        $this->SendKey($key);
    }

    public function TTV_Index()
    {
        $key = 'NRC_INDEX-ONOFF';  // TTV index
        $this->SendKey($key);
    }

    public function TTV_Hold()
    {
        $key = 'NRC_HOLD-ONOFF';  // TTV hold / image freeze
        $this->SendKey($key);
    }

    public function Last_View()
    {
        $key = 'NRC_R_TUNE-ONOFF';  // Last view
        $this->SendKey($key);
    }

    public function Power_Off()
    {
        $key = 'NRC_POWER-ONOFF';  // Power off
        $this->SendKey($key);
    }

    public function Rewind()
    {
        $key = 'NRC_REW-ONOFF';  // rewind
        $this->SendKey($key);
    }

    public function Play()
    {
        $key = 'NRC_PLAY-ONOFF';  // play
        $this->SendKey($key);
    }

    public function Fast_Forward()
    {
        $key = 'NRC_FF-ONOFF';  // fast forward
        $this->SendKey($key);
    }

    public function Skip_Previous()
    {
        $key = 'NRC_SKIP_PREV-ONOFF';  // skip previous
        $this->SendKey($key);
    }

    public function Pause()
    {
        $key = 'NRC_PAUSE-ONOFF';  // pause
        $this->SendKey($key);
    }

    public function Skip_Next()
    {
        $key = 'NRC_SKIP_NEXT-ONOFF';  // skip next
        $this->SendKey($key);
    }

    public function Stop()
    {
        $key = 'NRC_STOP-ONOFF';  // stop
        $this->SendKey($key);
    }

    public function Record()
    {
        $key = 'NRC_REC-ONOFF';  // record
        $this->SendKey($key);
    }

    public function Key1()
    {
        $key = 'NRC_D1-ONOFF';  // numeric button 1
        $this->SendKey($key);
    }

    public function Key2()
    {
        $key = 'NRC_D2-ONOFF';  // numeric button 2
        $this->SendKey($key);
    }

    public function Key3()
    {
        $key = 'NRC_D3-ONOFF';  // numeric button 3
        $this->SendKey($key);
    }

    public function Key4()
    {
        $key = 'NRC_D4-ONOFF';  // numeric button 4
        $this->SendKey($key);
    }

    public function Key5()
    {
        $key = 'NRC_D5-ONOFF';  // numeric button 5
        $this->SendKey($key);
    }

    public function Key6()
    {
        $key = 'NRC_D6-ONOFF';  // numeric button 6
        $this->SendKey($key);
    }

    public function Key7()
    {
        $key = 'NRC_D7-ONOFF';  // numeric button 7
        $this->SendKey($key);
    }

    public function Key8()
    {
        $key = 'NRC_D8-ONOFF';  // numeric button 8
        $this->SendKey($key);
    }

    public function Key9()
    {
        $key = 'NRC_D9-ONOFF';  // numeric button 9
        $this->SendKey($key);
    }

    public function Key0()
    {
        $key = 'NRC_D0-ONOFF';  // numeric button 0
        $this->SendKey($key);
    }

    public function Noise_Reduction()
    {
        $key = 'NRC_P_NR-ONOFF';  // Noise reduction
        $this->SendKey($key);
    }

    public function Off_Timer()
    {
        $key = 'NRC_OFFTIMER-ONOFF';  // off timer
        $this->SendKey($key);
    }

    public function Data()
    {
        $key = 'NRC_DATA-ONOFF';  // data
        $this->SendKey($key);
    }

    public function BD()
    {
        $key = 'NRC_BD-ONOFF';  // BD
        $this->SendKey($key);
    }

    public function Favorite()
    {
        $key = 'NRC_FAVORITE-ONOFF';  // Favorite
        $this->SendKey($key);
    }

    public function Game()
    {
        $key = 'NRC_GAME-ONOFF';  // Game
        $this->SendKey($key);
    }

    public function VOD()
    {
        $key = 'NRC_VOD-ONOFF';  // VOD
        $this->SendKey($key);
    }

    public function Eco()
    {
        $key = 'NRC_ECO-ONOFF';  // Eco
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

    //Profile
    protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits)
    {

        if (!IPS_VariableProfileExists($Name)) {
            IPS_CreateVariableProfile($Name, 1);
        } else {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != 1) {
                $this->SendDebug('PanasonicTV', 'Variable profile type does not match for profile ' . $Name, 0);
            }
        }

        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        IPS_SetVariableProfileDigits($Name, $Digits); //  Nachkommastellen
        IPS_SetVariableProfileValues(
            $Name, $MinValue, $MaxValue, $StepSize
        ); // string $ProfilName, float $Minimalwert, float $Maximalwert, float $Schrittweite

    }

    protected function RegisterProfileIntegerAss($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits, $Associations)
    {
        if (count($Associations) === 0) {
            $MinValue = 0;
            $MaxValue = 0;
        }
        /*
        else {
            //undefiened offset
            $MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations)-1][0];
        }
        */
        $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $Stepsize, $Digits);

        //boolean IPS_SetVariableProfileAssociation ( string $ProfilName, float $Wert, string $Name, string $Icon, integer $Farbe )
        foreach ($Associations as $Association) {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }

    }
}
