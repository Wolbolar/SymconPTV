<?php
class PTVHook extends IPSModule
{
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyString("Host", "");
        $this->RegisterPropertyString("Port", "3777");
        $this->RegisterTimer("HookSubscribe", 0, '@PTVHook_Subscribe($_IPS[\'TARGET\']);');
    }

    public function Destroy()
    {
        parent::Destroy();
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->RegisterHook("/hook/panasonictv");
        $this->Subscribe();
    }

    private function RegisterHook($WebHook)
    {
        $ids = IPS_GetInstanceListByModuleID("{015A6EB8-D6E5-4B93-B496-0D3F77AE9FE1}");
        if (sizeof($ids) > 0) {
            $hooks = json_decode(IPS_GetProperty($ids[0], "Hooks"), true);
            $found = false;
            foreach ($hooks as $index => $hook) {
                if ($hook['Hook'] == $WebHook) {
                    if ($hook['TargetID'] == $this->InstanceID) {
                        return;
                    }
                    $hooks[$index]['TargetID'] = $this->InstanceID;
                    $found = true;
                }
            }
            if (!$found) {
                $hooks[] = array("Hook" => $WebHook, "TargetID" => $this->InstanceID);
            }
            IPS_SetProperty($ids[0], "Hooks", json_encode($hooks));
            IPS_ApplyChanges($ids[0]);
        }
    }

    public function Subscribe()
    {
        $this->SetTimerInterval("HookSubscribe", 290 * 1000);
        $deviceIds = IPS_GetInstanceListByModuleID('{0B401818-DF07-489E-BD93-4DD67BE50B29}');
        foreach ($deviceIds as $deviceId) {
            $host = PTV_GetHost($deviceId);
            if ($host != '') {
                $url = "http://$host:55000/nrc/event_0";
                $hook = "http://{$this->ReadPropertyString('Host')}:{$this->ReadPropertyString('Port')}/hook/panasonictv?device_id=$deviceId";
                IPS_LogMessage('PTVHook', "Subscribe $url");

                $client = curl_init();
                curl_setopt($client, CURLOPT_URL, $url);
                curl_setopt($client, CURLOPT_USERAGENT, "SymconPanasonicTV");
                curl_setopt($client, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($client, CURLOPT_TIMEOUT, 5);
                curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($client, CURLOPT_CUSTOMREQUEST, 'SUBSCRIBE');
                curl_setopt($client, CURLOPT_HTTPHEADER, array(
          "CALLBACK: <$hook>",
          "NT: upnp:event",
          "TIMEOUT: Second-300"
        ));
                $result = curl_exec($client);
                $status = curl_getinfo($client, CURLINFO_HTTP_CODE);
                curl_close($client);
            }
        }
    }

    protected function ProcessHookData()
    {
        $input = file_get_contents("php://input");
        $properties = array();
        foreach (simplexml_load_string($input)->xpath('//e:property') as $property) {
            foreach ($property as $key => $value) {
                $properties[(string)$key] = (string)$value;
            }
        }
		$this->SendDebug("Panaonic TV", $properties,0);
        // $xml = simplexml_load_string($data)['e:propertyset'];
        $sendData = array("DataID" => "{E8F5D5E0-5B3D-42A0-843E-28DA5ED71484}", "DeviceID" => (integer)$_GET['device_id'], "Properties" => $properties);
        $this->SendDataToChildren(json_encode($sendData));
        IPS_LogMessage("PTVHook", "Event");
    }

	/**
	 * Ergänzt SendDebug um Möglichkeit Objekte und Array auszugeben.
	 *
	 * @access protected
	 * @param string $Message Nachricht für Data.
	 * @param mixed $Data Daten für die Ausgabe.
	 */
	protected function SendDebug($Message, $Data, $Format)
	{
		if (is_object($Data)) {
			foreach ($Data as $Key => $DebugData) {

				$this->SendDebug($Message . ":" . $Key, $DebugData, 0);
			}
		} else if (is_array($Data)) {
			foreach ($Data as $Key => $DebugData) {
				$this->SendDebug($Message . ":" . $Key, $DebugData, 0);
			}
		} else if (is_bool($Data)) {
			$this->SendDebug($Message, ($Data ? 'TRUE' : 'FALSE'), 0);
		} else {
			if (IPS_GetKernelRunlevel() == KR_READY) {
				parent::SendDebug($Message, (string) $Data, $Format);
			} else {
				IPS_LogMessage('SSDPTest:' . $Message, (string) $Data);
			}
		}
	}
}
