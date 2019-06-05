<input type="hidden" id="course_id" name="cid" value="<?= $course_id ?>">
<input type="hidden" id="tutorial_id" name="tid" value="<?= $tutorial_id ?>">

<div class="show-learn">
    <div class="show-learn-left">
    <div class="rwd-menubtn"><i class="fas fa-angle-right"></i></div>
        <div class="search-category">
            <input type="search" id="search" placeholder="Search..." value="">
            <span id="btnSearch" class="search-icon"><i class="fa fa-search"></i></span>
        </div>
        <div class="show-categorys">
        <?php foreach ($tutorials as $value) : ?>
            <div class="learn-list-top">
                <details id="t<?= $value['tid'] ?>">
                    <summary id="tutorial<?= $value['tid'] ?>"><?= $value['tutorial_title'] ?></summary>
                    <ul>
                        <?php foreach ($value['course'] as $course) : ?>
                            <li id="c<?= $course['id'] ?>">
                                <div class="showContent"><?= $course['title'] ?></div>
                            <?php if ($course['compare'] > -1) : ?>
                                <?php for ($i = 0; $i < $course['compare']; $i += 1) : ?>
                                </ul>
                                <?php endfor ?>
                            <?php else : ?>
                            <ul>
                            <?php endif ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </details>
            </div>
        <?php endforeach ?>
        </div>
    </div>
    <?php if ($imgSrc == '') : ?>
        <div class="show-learn-right">
            <div class="item-title">
                <?=$course_title?>
            </div>
            <div style="display:none">
                <input type= 'hidden' id="ori_content">
                <textarea id="text" key="show"></textarea>
            </div>
            <div id="test" class="mark-show-content"></div>
            <div class="change-chap">
                <input type="hidden" id="last_course" value="<?= $last_course['id'] ?>">
                <input type="hidden" id="next_course" value="<?= $next_course['id'] ?>">
                <div class="last_course" style="float:left;display:none"><i class="fas fa-chevron-left"></i></div>
                <div class="next_course" style="float:right;display:none"><i class="fas fa-chevron-right"></i></div>
            </div>
        </div>
    <?php else : ?>
        <img id='awoo-img' src='<?=$imgSrc?>'>
    <?php endif; ?>
</div>