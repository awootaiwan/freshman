<div class="show">
    <div class="rwd-menubtn"><i class="fas fa-angle-right"></i></div>
    <div class="show-menu">
        <div class="search-category">
            <input type="search" id="search" placeholder="Search..." value="">
            <span id="btnSearch" class="search-icon"><i class="fa fa-search"></i></span>
        </div>
        <div class="show-categorys">

            <ul>
                <?php if (isset($title)) : ?>
                    <?php foreach ($title as $category) : ?>
                        <li class="getTutorial" id="<?= $category['id'] ?>">
                            <span><?= $category['name'] ?></span>
                        </li>
                    <?php endforeach ?>
                <?php endif ?>
            </ul>
        </div>
        <div class="add-category" id="add-tutorial">
            <span id="btnAddCategory">＋新增教程</span>
        </div>
    </div>
    <?= $delete_dialog ?>
    <div class="show-tutorial">
        <?php if ($content) : ?>
            <?= $content ?>
        <?php else : ?>
            <?= $imgSrc ?>
        <?php endif ?>
    </div>
</div>

<div class="edit-category">
    <table class="edit-category-content" style="text-align: center;">
        <tr>
            <td class="addTutorial">
                <span id="addTutorial">新增教程</span>
            </td>
        </tr>
        <tr>
            <td class="tutorial-name" style="width:100%;">
                教程名稱
                <input type='text' id='tutorialTitle' maxlength='127' placeholder='輸入教程名稱' value="">
            </td>
        </tr>
        <tr>
            <td class="closeOk-btn">
                <button id="btnCloseAddTutorial">取消</button>
                <button id="btnOKAddTutorial">確認</button>
            </td>
        </tr>
    </table>
</div>