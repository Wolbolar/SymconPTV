<?
class PTV extends IPSModule {

  public function Create() {
    parent::Create();
    $this->RegisterPropertyString("Host", "");
  }

  public function Destroy() {
    parent::Destroy();
  }

  public function GetHost() {
    return $this->ReadPropertyString('Host');
  }

  public function ApplyChanges() {
    parent::ApplyChanges();
    $this->ConnectParent("{111046C4-5DCE-4622-9741-4A9BCCAFA92D}");
  }

  public function ReceiveData($json) {
    $data = json_decode($json);
    if ($data->DeviceID != $this->InstanceID) return;

    $properties = $data->Properties;

    if(@$properties->X_ScreenState) {
      $stateId = $this->RegisterVariableBoolean("STATE", "Zustand", "~Switch");
      $this->EnableAction("STATE");
      SetValueBoolean($stateId, $properties->X_ScreenState == 'on');
    }

  }

  public function RequestAction($key, $value) {
    switch ($key) {
      case 'STATE':
        $this->SendKey('NRC_POWER-ONOFF');
        break;
    }
  }

  public function GetValue($key) {
   return GetValue(@IPS_GetObjectIDByIdent($key, $this->InstanceID));
  }

  public function SetPowerOn() {
    $this->SetState(true);
  }

  public function SetPowerOff() {
    $this->SetState(false);
  }

  public function SetState($on = true) {
    if($this->GetValue('STATE') != $on) $this->SendKey('NRC_POWER-ONOFF');
  }

  public function SendKey($keyCode) {
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

  private function SoapRequest($path, $urn, $action, $option = array()) {
    $input = '<'.'?xml version="1.0" encoding="utf-8"?'.'>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
 <s:Body>
  <u:'.$action.' xmlns:u="urn:'.$urn.'">
  '.$option['args'].'
  </u:'.$action.'>
 </s:Body>
</s:Envelope>';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://' . $this->ReadPropertyString('Host') . ':55000/' . $path);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('SOAPACTION: "urn:' . $urn . '#' . $action . '"'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $input);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);

    if ($option['returnXml']) {
      return $data;
    } else {
      $xml = simplexml_load_string($data);
      if ($xml === false) return false;
      $ns = $xml->getNamespaces(true);
      $soap = $xml->children($ns['s']);
      $res = $soap->children($ns['u'])->children();
      return $res[0];
    }

  }

}

