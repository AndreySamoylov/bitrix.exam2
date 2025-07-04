<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог товаров (ex2-71)");
?>

<?php
$APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam71", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PRODUCTS_IBLOCK_ID" => \helpers\IblockHelper::getIdByCode(PRODUCTS_IBLOCK_CODE),
		"COMPANY_MAKER_IBLOCK_ID" => \helpers\IblockHelper::getIdByCode(COMPANY_MAKER_IBLOCK_CODE),
		"PRODUCTS_IBLOCK_ID_PROPERTY" => PRODUCTS_PROPERTY_COMPANY_MAKER,
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y"
	),
	false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
