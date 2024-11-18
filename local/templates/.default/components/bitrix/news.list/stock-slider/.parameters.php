<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
    die();
}

$arTemplateParameters  = [
    "PREVIEW_IMAGE_WIDTH" => [
        "PARENT" => "BASE",
        "NAME" => "Ширина картинки",
        "TYPE" => "STRING",
        "DEFAULT" => "100",
    ],
    "PREVIEW_IMAGE_HEIGHT" => [
        "PARENT" => "BASE",
        "NAME" => "Высота картинки",
        "TYPE" => "STRING",
        "DEFAULT" => "100",
    ],
];
