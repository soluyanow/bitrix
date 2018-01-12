<?
$prevPrice = 0;

AddEventHandler("catalog", "OnBeforePriceUpdate", "BeforePriceUpdate");
function BeforePriceUpdate($ID, &$arFields)
{
	$GLOGALS['prevPrice'] = getPrice($ID);	
}

AddEventHandler("catalog", "OnPriceUpdate", "PriceUpdate"); 
function priceUpdate($ID, &$arFields) {
	define("SITE_ID", "s1");
	
	$fields 						= array();
	
	$el 							= CIBlockElement::GetByID($ID);
	$element 						= $el->getNext();
	
	if (!empty($element)) {
		$arSelect 					= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_MAIL_SUBSCRIBE_IBLOCK_ID");
		$arFilter 					= Array("ID" 				=> IntVal($element["ID"]),
											"IBLOCK_ID"			=> IntVal($element["IBLOCK_ID"]), 							  
											"ACTIVE"			=> "Y" 							  
											);					  
						  
		$res 						= CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
		while($ob 					= $res->GetNextElement()) {
			$fields 				= $ob->getFields();	
		}	
		
		$curPrice 					= getPrice($ID);
		$fields["SITE_ID"] 			= SITE_ID;
		$fields["PRODUCT_ID"] 		= IntVal($ID);
		$fields["PRODUCT_PRICE"] 	= $curPrice;		
		
		if (($curPrice < $GLOGALS['prevPrice']) && !empty($fields)) {
			sendEmail($fields);		
		}
	}
}

function sendEmail($fields) {
	$element 			= array();		
	$arEventFields 		= array();
	
	if (!empty($fields)) {
		$arSelect 		= Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_EMAIL", "PROPERTY_PRODUCT_ID", "PROPERTY_USER_LOGIN", 
								"PROPERTY_MAIL_EVENT_ID", "PROPERTY_PRODUCT_IBLOCK_ID", "PROPERTY_PRODUCT_ID", "PROPERTY_PRODUCT_NAME",
								"PROPERTY_PRODUCT_ARTICLE", "PROPERTY_PRODUCT_LINK");
			
		$arFilter 		= Array("IBLOCK_ID"				=> IntVal($fields["PROPERTY_MAIL_SUBSCRIBE_IBLOCK_ID_VALUE"]),
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
				
			if (!empty($arEventFields) && !empty($mail["PROPERTY_MAIL_EVENT_ID_VALUE"])) {
				CEvent::Send($mail["PROPERTY_PRODUCT_ID_VALUE"], $fields["SITE_ID"], $arEventFields, "N");
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