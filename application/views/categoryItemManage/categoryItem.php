<div class="show">
    <div class="rwd-menubtn"><i class="fas fa-angle-right"></i></div>
    <div class="show-menu">
    <div class="search-category">
            <input type="search" id="search" placeholder="Search..." value="<?= $keyword ?>">
            <span id="btnSearch" class="search-icon"><i class="fa fa-search"></i></span>
        </div>
        <div class="show-categorys">
            <ul>
                <?php foreach ($categoryList as $category) : ?>
                    <li id="<?= $category['id'] ?>">
                        <?php if ($category['description'] != "") : ?>
                            <span><?= $category['title'] ?></span>
                        <?php else : ?>
                            <span><?= $category['title'] ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="add-category" id="btnAddCategory">
            <span id="btnAddCategory">＋新增分類</span>
        </div>
    </div>
    <div class="show-content">
        <?= $content ?>
    </div>
</div>