<div class='manager'>
    
    <div class='select-department'>
        <input id="checktype" type="hidden" value="">
        <select>
            <?php foreach ($departmentList as $department) : ?>
                <option value=<?= $department['id'] ?> 
                    <?php if ($department['id'] == $departmentId) : ?>selected="selected" <?php endif ?>>
                    <?= $department['title'] ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="user-add-area"><button class='user-add'>＋新增人員</button></div>
    <div class='editManage'>
        <?= $showAllUser ?>
    </div>
</div>

<div class='hide-freshman-member'>
    <div class="member-bg-close"></div>
    <input id="updateId" type="hidden" value="">
        <?= $freshmanMember ?>
</div>