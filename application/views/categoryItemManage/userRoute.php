<?php if ($userId == "") { ?>
    <div class="user-route">
<?php } else { ?>
    <div class="user-route-data">
<?php }?>
    <div class="show">
        <i class="fas fa-sitemap"></i>
        <div class="show-department">
        <div class="select-department">
                <select>
                    <option value="ALL">全部</option>
                    <?php foreach ($departmentList as $department) : ?>
                        <option value=<?= $department['abbreviation'] ?>><?= $department['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>
        <div class="show-user">
            <div class="select-user">
                <select>
                    <option value="">請選擇員工(E-mail)</option>
                </select>
            </div>
        </div>
    </div>
    <?php if ($userId != "") { ?>
        <div class="categorys-list">
            <div class="categorys">
                <?= $categorys ?>
            </div>
            <div class="userCategorys">
                <?= $userCategorys ?>
            </div>
        </div>
    <?php }?>
    
</div>

<?= $itemList ?>

<input id="departmentId" type="hidden" value="<?=$departmentId?>">
<input id="userId" type="hidden" value="<?=$userId?>">
