<?php if ($departmentId == "") : ?>
    <div class="user-route">
<?php else : ?>
    <div class="user-route-data">
<?php endif ?>
        <div class="show">
            <i class="fas fa-sitemap"></i>
            <div class="show-department">
                <div class="select-department">
                    <select>
                        <option value="">請選擇部門</option>
                        <?php foreach ($departmentList as $department) : ?>
                            <option value=<?= $department['abbreviation'] ?>><?= $department['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
        </div>
        <?php if ($departmentId != "") : ?>
            <div class="categorys-list">
                <div class="categorys">
                    <?= $categorys ?>
                </div>
                <div class="userCategorys">
                    <?= $deptCategorys ?>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?= $itemList ?>

    <input id="departmentId" type="hidden" value="<?= $departmentId ?>">