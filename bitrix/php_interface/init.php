<?
$prevPrice = 0;

AddEventHandler("catalog", "OnBeforePriceUpdate", "BeforePriceUpdate");
function BeforePriceUpdate($ID, &$arFields)
{
	$GLOGALS['prevPrice'] = getPrice($ID);	
}

AddEventHandler("catalog", "OnPriceUpdate", "PriceUpdate"); 
function priceUpdate($ID, &$arFields)
{		
	$curPrice = getPrice($ID);
	
	if ($curPrice < $GLOGALS['prevPrice']) {
		sendEmail($ID, "PRICE_UPDATE", "s1", 159, $curPrice);
		removeSubscribe($ID);
	}
}

function sendEmail($ID, $emailStatus, $site, $emailnum, $price) {
		
	$arEventFields 							= array();
	
	$res 									= CIBlockElement::GetByID($ID);
	if($ar_res 								= $res->GetNext()) {		
		$arEventFields["ID"] 				= $ID;
		$arEventFields["EMAIL"] 			= $USER->GetEmail();
		$arEventFields["NAME"] 				= $ar_res['NAME'];
		$arEventFields["ARTNUMBER"] 		= getProdValues(2, $ID, 'ARTNUMBER');
		$arEventFields["CURRENT_PRICE"] 	= $price;
		$arEventFields["DETAIL_PAGE_URL"] 	= $ar_res['DETAIL_PAGE_URL'];	
	}	
	
	CEvent::SendImmediate($emailStatus, $site, $arEventFields, "N", $emailnum);
}

function getPrice($ID) {
	$db_res = CPrice::GetList(
		array(),
		array(
            "PRODUCT_ID" => $ID,
            "CATALOG_GROUP_ID" => 1
        )
	);
	
	if ($ar_res = $db_res->Fetch()) {
		return $ar_res["PRICE"];
	} else {
		return false;
	}	
	return false;
}

function getProdValues($ibID, $ID, $valName) {
	$db_props = CIBlockElement::GetProperty($ibID, $ID, array("sort" => "asc"), Array("CODE"=>$valName));
	if($ar_props = $db_props->Fetch()) {
		return $ar_props["VALUE"];
	} else {
		return false;
	}	
	return false;
}

function removeSubscribe($IBLOCK_ID, $ID) {
	CIBlockElement::Delete($ID);	
}
?>