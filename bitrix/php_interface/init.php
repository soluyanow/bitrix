<?
$prevPrice = 0;

AddEventHandler("catalog", "OnBeforePriceUpdate", "BeforePriceUpdate");
function BeforePriceUpdate($ID, &$arFields)
{
	$GLOGALS['prevPrice'] = getPrice($ID);	
}

AddEventHandler("catalog", "OnPriceUpdate", "PriceUpdate"); 
function priceUpdate($ID, &$arFields) {
	define("MAIL_EVENT_ID"				, 159);
	define("MAIL_SUBSCRIBE_IBLOCK_ID"	, 4);
	
	$resFields 						= array();
	$fields							= array();
	
	$el 							= CIBlockElement::GetByID($ID);
	$element 						= $el->getNext();
	
	if (!empty($element)) {
		$arSelect 					= Array("ID", "IBLOCK_ID", "LID", "NAME");
		$arFilter 					= Array("ID" 				=> IntVal($element["ID"]),
											"IBLOCK_ID"			=> IntVal($element["IBLOCK_ID"]), 							  
											"ACTIVE"			=> "Y" 							  
											);					  

		$res 						= CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
		while($ob 					= $res->GetNextElement()) {
			$fields 				= $ob->getFields();	
		}	
		
		$curPrice 					= getPrice($fields["ID"]);
		$resFields["SITE_ID"] 		= $fields["LID"];
		$resFields["PRODUCT_ID"] 	= $fields["ID"];
		$resFields["PRODUCT_PRICE"] = $curPrice;
		$resFields["MAIL_EVENT_ID"]	= MAIL_EVENT_ID;
		$resFields["MAIL_SUBSCRIBE_IBLOCK_ID"]	= MAIL_SUBSCRIBE_IBLOCK_ID;
		
		if (($curPrice < $GLOGALS['prevPrice']) && !empty($resFields)) {
			sendEmail($resFields);		
		}
	}
}

function sendEmail($fields) {
	$element 			= array();		
	$arEventFields 		= array();
	
	if (!empty($fields)) {
		$arSelect 		= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_EMAIL", "PROPERTY_PRODUCT_ID", "PROPERTY_USER_LOGIN", 
								"PROPERTY_PRODUCT_IBLOCK_ID", "PROPERTY_PRODUCT_ID", "PROPERTY_PRODUCT_NAME",
								"PROPERTY_PRODUCT_ARTICLE", "PROPERTY_PRODUCT_LINK");
			
		$arFilter 		= Array("IBLOCK_ID"				=> $fields["MAIL_SUBSCRIBE_IBLOCK_ID"],
								"PROPERTY_PRODUCT_ID"	=> $fields["PRODUCT_ID"],
								"ACTIVE"				=> "Y"
								);
				
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);				
		while($ob 		= $res->GetNextElement()) {					
			$mail		= array();
			$mail 		= $ob->getFields();						
			
			if (!empty($mail)) {
				if (!empty($mail["PROPERTY_USER_EMAIL_VALUE"])) {
					$arEventFields 							= array();
					$arEventFields["USER_NAME"] 			= $mail["NAME"];				
					$arEventFields["USER_LOGIN"] 			= $mail["PROPERTY_USER_LOGIN_VALUE"];
					$arEventFields["USER_EMAIL"] 			= $mail["PROPERTY_USER_EMAIL_VALUE"];					
					$arEventFields["PRODUCT_NAME"] 		 	= $mail["PROPERTY_PRODUCT_NAME_VALUE"];
					$arEventFields["PRODUCT_ARTICLE"] 		= $mail["PROPERTY_PRODUCT_ARTICLE_VALUE"];
					$arEventFields["PRODUCT_DETAIL_LINK"] 	= $mail["PROPERTY_PRODUCT_LINK_VALUE"];			
				}
			}
				
			if (!empty($arEventFields) && !empty($fields["MAIL_EVENT_ID"])) {
				CEvent::Send($fields["MAIL_EVENT_ID"], $fields["SITE_ID"], $arEventFields, "N");
				removeSubscribe($mail["ID"]);
			}
		}
	}
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

function removeSubscribe($subscribeID) {
	CIBlockElement::Delete($subscribeID);	
}
?>