<ul class="item-list">
    <?php foreach ($items as $item): ?>
        <?php if ($item['checked']): ?>
        <li class="item-li-check" id="<?=$item['id']?>">
            <i class="far fa-check-circle" id="circle"></i> 
            <?=$item['title']?>
        </li>
        <?php else:?>
        <li class="item-li" id="<?=$item['id']?>">
            <i class="far fa-circle"></i>
            <?=$item['title']?>
        </li>
        <?php endif?>
    <?php endforeach?>
</ul>
<input type="hidden" id="rate" value="<?=$rate?>">
<div class="rate-content">
    已完成 
</div>

