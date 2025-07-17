<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_97_IBLOCK_ID"),
            "PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "AUTHOR_PROPERTY_CODE" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_97_AUTHOR_PROPERTY_CODE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "AUTHOR_TYPE_CODE" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_97_AUTHOR_TYPE_CODE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "CACHE_TIME" => ["DEFAULT" => 36000000],
        "CACHE_GROUPS" => [
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_97_CACHE_GROUPS"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
	),
);
