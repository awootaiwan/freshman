<div id="item-<?=$itemId?>" class="item">
<input type="hidden" id="itemTrueId" value="<?=$itemTrueId?>">
<?php if ($itemId != ""): ?> 
    <div class="item-title">
        <?=$itemTitle?>
    </div>
    <div class="item-content">
        <input type= 'hidden' id="ori_content">
        <?= $markdown_content ?>
    </div>

    <div class="check-item">
        <?php if ($itemChecked):?>
            <input checked id="checkItem" type="checkbox" class="item-checkbox">
        <?php else: ?>
            <input id="checkItem" type="checkbox" class="item-checkbox">
        <?php endif ?>
        <label id="checkLabel" for="checkItem"></label>
    </div>
<?php else: ?>
    <div class="item-title">
        恭喜完成所有任務
    </div>
    <div class="item-content">
        接下來直接到「上手教程」學習就可以了喔 &#128521;
    </div>
<?php endif ?>
</div>