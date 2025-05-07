<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент (ex2-70)");
?>

<?php $APPLICATION->IncludeComponent(
	"ex2:simplecomp.exam70", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PRODUCTS_IBLOCK_ID" => \helpers\IblockHelper::getIdByCode(PRODUCTS_IBLOCK_CODE),
		"NEWS_IBLOCK_ID" => \helpers\IblockHelper::getIdByCode(MEWS_IBLOCK_CODE),
		"PRODUCTS_IBLOCK_ID_PROPERTY" => UF_NEWS_LINK_PROPERTY_CODE
	),
	false
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>
