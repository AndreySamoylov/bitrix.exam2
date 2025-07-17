<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новости по интересам (ex2-97)");
?>

<?php
$APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam97", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_ID" => NEWS_IBLOCK_ID,
		"AUTHOR_PROPERTY_CODE" => "PROPERTY_AUTHOR",
		"AUTHOR_TYPE_CODE" => "UF_AUTHOR_TYPE",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y"
	),
	false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
