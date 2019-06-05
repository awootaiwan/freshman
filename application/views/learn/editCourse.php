<div class="edit-course">
    <div class="edit-course-top">
        <div class="course-title">
            <input id="course-text" class="course-text-set" placeholder="課程名稱" type="text" name="course-name" />
            <input id="course-id" type="hidden">
        </div>
        <div class="markdown-learn"><i class="markdown-learn-QA far fa-question-circle"></i>
            <div class="markdown-learn-description">
                <i class="markdown-learn-close fas fa-times"></i>
                <iframe class="markdown-learn-iframe" src="<?php echo base_url()?>WelcomeFreshman/markdownLearn" frameborder="0" scrolling="auto"></iframe>
            </div>
        </div>
        <div class="closebtn fas fa-times"></div>
    </div>
    <input type='hidden' id="ori_content">
    <?= $markdownContent ?>

    <div class="course-check">
        <button id="delete-course" style="display:none;">刪除課程</button>
        <button id="storage-course">儲存</button>
    </div>
</div>