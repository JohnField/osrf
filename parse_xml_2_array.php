<?php
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}
?>


<?php
$xmlUrl = "http://server/reports/fm_IDL.xml";
$xmlStr = file_get_contents($xmlUrl);
$xmlObj = simplexml_load_string($xmlStr);
$arrXml = objectsIntoArray($xmlObj);
$class = array();
$field = array();
for ($i= 0 ; $arrXml['class'][$i]['@attributes']['id'] != NULL ; $i++) {
	$class[] = $arrXml['class'][$i]['@attributes']['id'];
	$class_id = $arrXml['class'][$i]['@attributes']['id'];
	$inner = NULL;
	$field[$class_id] = array();
	for ($j= 0 ; $arrXml['class'][$i]['fields']['field'][$j]['@attributes']['name'] != NULL ; $j++) {
		$field[$class_id][] = $arrXml['class'][$i]['fields']['field'][$j]['@attributes']['name'];
	}
}
echo "<pre>"; print_r ($field); echo "</pre> <br />";
?>