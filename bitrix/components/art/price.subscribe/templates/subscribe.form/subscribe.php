<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent(
	"art:price.subscribe", 
	".default", 
	array(
		"IBLOCK_ID" => "4",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "undefined",		
		"SUBSCRIBE_ALREADY_ADDED_MESSAGE" => "������������ ��� �������� �� �����",
		"SUBSCRIBE_ADDED_MESSAGE" => "������������ �������� �� �����",
		"SUBSCRIBE_ERROR_MESSAGE" => "������ ���������� ��������",
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>