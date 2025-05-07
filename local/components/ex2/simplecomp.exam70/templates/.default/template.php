<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
?>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<ul>
    <?php foreach ($arResult['NEWS'] as $arNew) { ?>
        <li>
            <p>
                <b><?= $arNew['NEW']['NAME'] ?></b>
                <?= $arNew['NEW']['ACTIVE_FROM'] ?>
                <?= '(' . implode(', ',$arNew['PRODUCT_SECTIONS_NAME']) . ')' ?>
            </p>
            <ul>
                <?php foreach ($arNew['PRODUCTS'] as $arProducts) { ?>
                    <li>
                        <?= $arProducts['NAME'] . ' - ' . $arProducts['PROPERTY_ARTNUMBER_VALUE'] . ' - ' . $arProducts['PROPERTY_MATERIAL_VALUE'] . ' - ' . $arProducts['PROPERTY_PRICE_VALUE'] ?>
                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>
