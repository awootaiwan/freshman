<div class='tutorial-set'>
    <div class='tutorial-edit'>
        <div class='tutorial-title'>
            <div class='all-text'>
                <input class='text-set' type='text' placeholder="輸入教程名稱..." value="<?= $title ?>">
            </div>
        </div>
        <div class='tutorial-change'>
        <button class='course-add'>新增課程</button>
            <?php if (!empty($id)) : ?>
            <button id='tutorial-delete'>刪除教程</button>
            <?php endif ?>
            <button id='tutorial-save'>完成</button>
        </div>
    </div>
    <div class='relation-edit'>
        <div class='courses'>
            <div class = 'tutorial-titleList'>課程列表</div>
            <div class='search'>
                <input type='search' id='course-search' placeholder="Search...">
                <i id="search-courses" class="fas fa-search"></i>
            </div>
            <ul id="sortable1" class="connectSortable">
                <?php if(isset($courses)):?>
                <?php foreach ($courses as $course) : ?>
                    <li id=<?= $course['id'] ?> class="mjs-nestedSortable-no-nesting"> <?= $course['title'] ?></li>
                <?php endforeach ?>
                <?php endif?>
                
            </ul>
        </div>

        <div <?php if (!empty($id)) : ?> data-id=<?= $id ?> <?php endif ?>class='relation'>
            <div class = 'tutorial-titleList'>教程目錄列表</div>
            <ul id="sortable2" class="connectSortable">
                <?php foreach ($tutorials as $tutorial) : ?>
                    <li id=<?= $tutorial['id'] ?>> <?= $tutorial['title'] ?>
                        <?php if ($tutorial['compare'] > -1) : ?>
                            <?php for ($i = 0; $i < $tutorial['compare']; $i += 1) : ?>
                        </ul>
                    <?php endfor ?>
                <?php else : ?>
                    <ul>
                    <?php endif ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    
        

    <div class='hide-course'>
        <?= $hide_course ?>
    </div>
</div>