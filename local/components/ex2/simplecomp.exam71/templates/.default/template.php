<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
?>

<p><b><?=GetMessage("SIMPLECOMP_EXAM2_71_CAT_TITLE")?></b></p>
<ul>
    <?php foreach ($arResult['COMPANIES'] as $arCompany) { ?>
        <li>
            <p>
                <b><?= $arCompany['NAME'] ?></b>
            </p>
            <?php if (!empty($arCompany['PRODUCTS'])) { ?>
                <ul>
                    <?php foreach ($arCompany['PRODUCTS'] as $arProduct) { ?>
                        <li>
                            <a href="<?= $arProduct['DETAIL_PAGE_URL'] ?>">
                                <?= $arProduct['NAME'] . ' - ' . $arProduct['PROPERTY_PRICE_VALUE'] . ' - ' . $arProduct['PROPERTY_MATERIAL_VALUE'] . ' - ' . $arProduct['PROPERTY_ARTNUMBER_VALUE'] ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php }?>
        </li>
    <?php } ?>
</ul>
