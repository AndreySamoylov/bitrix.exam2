<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php /*<script>
	$().ready(function(){
		$(function(){
			$('#slides').slides({
				preload: false,
				generateNextPrev: false,
				autoHeight: true,
				play: 4000,
				effect: 'fade'
			});
		});
	});
</script>
<div class="product-list" id="slides">
	<div class="slides_container">
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<div>
			<div>
				<?if(is_array($arItem["PREVIEW_PICTURE"])):?>
				<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="" />
				<?endif;?>
				<h2><a href="<?=$arItem["PROPERTIES"]['LINK']['VALUE']?>"><?echo $arItem["NAME"]?></a></h2>
				<p><?echo $arItem["PREVIEW_TEXT"];?></p>
				<a href="<?=$arItem["PROPERTIES"]['LINK']['VALUE']?>" class="sl_more">Подробнее &rarr;</a>
			</div>
		</div>
		<?endforeach;?>
	</div>
</div> */?>
<?php
//echo '<pre>';
//print_r($arParams);
//print_r($arResult);
//echo '</pre>';
//?>
<div>
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <div>
            <div>
                <?if(is_array($arItem["PREVIEW_PICTURE"])):?>
                    <img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" alt="" />
                <?endif;?>
                <? // название товара + «всего за » + стоимость товара + «руб» ?>
                <h2><a href="<?=$arItem["PROPERTY_LINK_DETAIL_PAGE_URL"]?>"><?echo $arItem["PROPERTY_LINK_NAME"]?></a></h2>
                <p>
                    <?php
                        echo "{$arItem["PROPERTY_LINK_NAME"]} всего за {$arItem["PROPERTY_LINK_PROPERTY_PRICE_VALUE"]} руб" ;
                    ?>
                </p>
                <a href="<?=$arItem["PROPERTY_LINK_DETAIL_PAGE_URL"]?>" class="sl_more">Подробнее &rarr;</a>
            </div>
        </div>
    <?endforeach;?>
</div>>

