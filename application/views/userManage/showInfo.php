<table class='user-info'>
    <thead>
        <?php if($_SESSION['isAdmin']):?>
            <th>設定權限</th>
        <?php endif ?>
        <th>姓名</th> 
        <th>gmail 信箱</th>
        <th>部門</th>
    </thead>

    <?php foreach($userInfo as $info) :?>
    <tr class='user-idx' data-id = <?=$info['idx']?>>
        <?php if($_SESSION['isAdmin']):?>
            <td class='isManager'>
                <input type = checkbox 
                    <?php if($info['isManager']):?>checked<?php endif?>
                    <?php if($info['isAdmin']):?>disabled<?php endif?>>
            </td>
        <?php endif ?>
        <input id="checktype" type="hidden" value="">
        <td class='user-name'><?=$info['name']?></td>
        <td class='user-gmail'><?=$info['gmail']?></td>
        <td class='user-dept' data-id = <?=$info['abbreviation_id']?>><?=$info['abbreviation']?></td>
    </tr>
    <?php endforeach ?>
</table>