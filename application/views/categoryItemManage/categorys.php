<div class="show-category">
    <div class="show-name">
        <span id="showName"><?= $name ?></span>
    </div>

    <ul class="category-list">
        <?php foreach ($categoryList as $category) : ?>
            <?php if ($category['id'] == 1 || $category['isUserDeptCategory'] == "T") : ?>
                <li key="<?= $category['id'] ?>" class=" category no-sort" data-dept="<?= $category['isUserDeptCategory']?>">
                    <span class="show-category-title"><?= $category['title'] ?></span>
                    <div class="show-category-description"><?= $category['description'] ?></div>  
                <li>
            <?php else: ?>
                <li key="<?= $category['id'] ?>" class="category" data-dept="<?= $category['isUserDeptCategory']?>">
                    <span class="show-category-title"><?= $category['title'] ?></span>
                    <div class="show-category-description"><?= $category['description'] ?></div>
                <li>
            <?php endif ?>
        <?php endforeach ?>
    </ul>

</div>