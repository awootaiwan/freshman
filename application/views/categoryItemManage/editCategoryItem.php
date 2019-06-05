<?php if ($category_info['id']) : ?>
    <table>
        <?php if ($category_info['description'] != "") : ?>
            <span id="categoryTitle"><?= $category_info['title'] ?></span>
            <div class="tooltip">
                <i class="far fa-question-circle"></i>
                <span class="tooltiptext"><?= $category_info['description']?></span>
            </div>
        <?php else : ?>
            <span id="categoryTitle"><?= $category_info['title'] ?></span>
        <?php endif; ?>
    </table>
    <div id="<?= $category_info['id'] ?>" class="content">
        <table>
            <ul class="drop-down-category">
                <li><a href="#"><i class="fas fa-ellipsis-h"></i></a>
                    <ul>
                        <li><a id="btnEditCategory">編輯</a></li>
                        <li><a id="btnDeleteCategory">刪除</a></li>
                    </ul>
                </li>
            </ul>
            <br>

            <ul class="content-items">

                <?php foreach ($itemList as $item) : ?>
                    <li key="<?= $item['id'] ?>" data="<?= $item['category_item_id'] ?>" class="item">
                        <span class="show-item" style="float:left;" ;><?= $item['title'] ?></span>
                        <span class="show-drop-down-item" style="float:right;">
                            <ul class="drop-down-item">
                                <li><a href="#"><i class="fas fa-ellipsis-v"></i></a>
                                    <ul>
                                        <li><a class="editItem" key="<?= $item['id'] ?>">編輯</a></li>
                                        <li><a class="deleteItem" key="<?= $item['id'] ?>">刪除</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </span>
                    <li>
                    <?php endforeach ?>
            </ul>
            <button id="btnToAddItemPage">＋新增項目</button>
        </table>
    </div>
<?php else : ?>
    <img id="awoo-img" src="<?=$img_src?>">
<?php endif; ?>


<div class="edit-category">
    <table class="edit-category-content">
        <tr>
            <td>
                <span id="close-btn" class="close-btn fas fa-times"></span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="error"></span>
            </td>
        </tr>
        <tr>
            <td class="category-name" style="width:100%;">
                <input type="hidden" id="id">
                分類名稱：<input type='text' id='title' maxlength='127' placeholder='輸入分類名稱' value="<?= $category_info['title'] ?>">
            </td>
        </tr>
        <tr>
            <td class="category-description" style="width:100%;height:150px;">
                分類描述：<textarea id="description" maxlength='127' placeholder='描述' value="<?= $category_info['description'] ?>"></textarea>
            </td>
        </tr>
        <tr>
            <td class="closeOk-btn">
                <button id="btnCloseAddCategory">取消</button>
                <button id="btnOKAddCategory">確認</button>
            </td>
        </tr>
    </table>
</div>

<?= $delete_dialog ?>