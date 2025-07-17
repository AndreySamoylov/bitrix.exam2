<?php

use	Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
?>

<p><b><?=Loc::getMessage("SIMPLECOMP_EXAM2_97_HEADER")?></b></p>
<ul>
    <?php foreach ($arResult['USERS'] as $arUser) { ?>
        <li>
            <p>
                [<?= $arUser['ID'] ?>] <?= $arUser['LOGIN'] ?>
            </p>
            <?php if (is_array($arUser['NEWS']) && count($arUser['NEWS']) > 0) { ?>
                <ul>
                    <?php foreach ($arUser['NEWS'] as $arNew) { ?>
                        <li>
                            <p>
                                <?= $arNew['NAME'] ?>
                            </p>
                        </li>
                    <?php } ?>
                </ul>
            <?php }?>
        </li>
    <?php } ?>
</ul>
