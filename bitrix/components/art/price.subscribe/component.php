<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (!empty($_REQUEST['email'])) {
	/*
	iblock fields
	NAME, 
	USER_EMAIL,
	USER_LOGIN,	
	PRODUCT_IBLOCK_ID,
	PRODUCT_ID,
	PRODUCT_NAME,
	PRODUCT_ARTICLE,
	PRODUCT_LINK
	*/	
	
	$arResult = Array(
		'USER_NAME' 						=> $_REQUEST['username'],
		'USER_EMAIL' 						=> $_REQUEST['email'],
		'IBLOCK_ID' 						=> $_REQUEST['ibid'],
		'PRODUCT_ID' 						=> $_REQUEST['productid'],
		'PRODUCT_IBLOCK_ID' 				=> $_REQUEST['productibid'],		
		'SUBSCRIBE_ALREADY_ADDED_MESSAGE' 	=> $arParams['SUBSCRIBE_ALREADY_ADDED_MESSAGE'],
		'SUBSCRIBE_ADDED_MESSAGE' 			=> $arParams['SUBSCRIBE_ADDED_MESSAGE']	
	);
	
	foreach ($_POST as $key => $value) {
		unset($_POST['key']);
	}
	
	$isProdSubscribe = false;

	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_EMAIL");
	$arFilter = Array("IBLOCK_ID"			=>IntVal($arResult['IBLOCK_ID']), 
					  "ACTIVE"				=>"Y", 
					  "PROPERTY_PRODUCT_ID" => $arResult['PRODUCT_ID'],
					  "PROPERTY_USER_EMAIL" => $arResult['USER_EMAIL']
					 );					  
					  
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields 							= $ob->GetFields();
		$isProdSubscribe 					= true;
	}

	if (!$isProdSubscribe) {
		$el 								= new CIBlockElement;
		
		$propArray 							= array();
		$propArray['NAME'] 					= $USER->GetFullName();
		$propArray['IBLOCK_ID'] 			= $arResult['IBLOCK_ID'];	
		
		$propVals 							= array();
		$propVals['USER_LOGIN'] 			= $arResult['USER_NAME'];
		$propVals['USER_EMAIL'] 			= $arResult['USER_EMAIL'];		
		$propVals['PRODUCT_ID'] 			= $arResult['PRODUCT_ID'];
		$propVals['PRODUCT_IBLOCK_ID'] 		= $arResult['PRODUCT_IBLOCK_ID'];		
		$propVals['PRODUCT_NAME'] 			= $arResult['PRODUCT_NAME'];
		$propVals['PRODUCT_ARTICLE'] 		= $arResult['PRODUCT_ARTICLE'];
		$propVals['PRODUCT_LINK'] 			= $arResult['PRODUCT_LINK'];			
		
		$propArray['PROPERTY_VALUES'] 		= $propVals;		
		
		$el->Add($propArray);
	}
}

$this->IncludeComponentTemplate();?>
