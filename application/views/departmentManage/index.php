<div class="dept-list-layout">
    <div class="add-dept-area">
        <button class="add-dept-button"><i class="fas fa-plus"></i>新增部門</button>
    </div>
    <table class="dept-list" rules="all">
        <thead>
            <tr>
                <th>代碼</th>
                <th>名稱</th>
                <th>功能</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departmentList as $dept):?>
            <tr>
                <td><?=$dept['abbreviation']?></td>
                <td><?=$dept['name']?></td>
                <td>
                    <?php if ($dept['id'] != "999"): ?>
                    <i data-id="<?=$dept['id']?>" data-abbr="<?=$dept['abbreviation']?>" data-name="<?=$dept['name']?>"
                        class="dept-icon fas fa-edit"></i>
                    <i data-id="<?=$dept['id']?>" data-abbr="<?=$dept['abbreviation']?>" data-name="<?=$dept['name']?>"
                        class="dept-icon fas fa-trash-alt"></i>
                    <?php endif;?>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>

    </table>

</div>

<?=$editDept?>
<?=$deleteDept?>