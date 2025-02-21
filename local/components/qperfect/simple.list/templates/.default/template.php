<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult['NEWS'])) : ?>
<div class="news-list">
    <?php foreach($arResult['NEWS'] as $arItem) : ?>
        <p class="news-item">
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
                        class="preview_picture"
                        border="0"
                        width="100"
                        height="100"
                        src="<?=$arItem["PREVIEW_PICTURE_SRC"]?>"
                        alt="<?=$arItem["NAME"]?>"
                        title="<?=$arItem["NAME"]?>"
                        style="float:left"
                /></a>

            <span class="news-date-time"><?echo $arItem["ACTIVE_FROM"]?></span>
            <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a><br />

            <?echo $arItem["PREVIEW_TEXT"];?>
        </p>
    <?php endforeach;?>
</div>
<?php endif;?>