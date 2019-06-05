<div class="show-edit-item">
    <th>
        <span class="error"></span>
    </th> 
    <div class='write-title'>
        <input type='hidden' id="id" value="<?= $item_info['id'] ?>">
        <input type='text' id="title" placeholder="標題" value="<?= $item_info['title'] ?>" maxlength='127'>
        <div class="top-btn">
            <div class="markdown-learn"><i class="far fa-question-circle"></i>
                <div class="markdown-learn-description">
                    <i class="markdown-learn-close fas fa-times"></i>
                    <iframe  class="markdown-learn-iframe" src="<?php echo base_url()?>WelcomeFreshman/markdownLearn" frameborder="0" scrolling="auto"></iframe>
                </div>
            </div>
            <?php if (!empty($item_info['id'])) : ?>
            <div class="button-delete" >
                <button id="deleteItem">刪除項目</button>
            </div>
            <?php endif ?>
            <button id="btnCancel">上一頁</button>
            <?php if (!empty($item_info['id'])) : ?>
                <button id="btnUpdateDone">完成</button>
            <?php else : ?>
                <button id="btnInsertDone">完成</button>
            <?php endif; ?>
        </div>
    </div> 

    <div class="show-dropdown-category">
        <ul class="drop-down-category">
            <li><a href="#">選擇分類名稱 <i class="fas fa-angle-down"></i></a>
                <ul>
                    <li>
                        <table>
                            <tr class='all-tag'>
                            <?php
                                $categoryIds = explode(",", $item_info['categoryIds']);
                                $tdNum = 4;
                                for ($t = 0; $t < $tdNum; $t++) {
                                    echo '<td>';
                                    for ($c = 0; $c < count($categoryList); $c++) {
                                        if ($c % $tdNum == $t) {
                                            $showDiv = "<div class='unsel-tag' id='{$categoryList[$c]['id']}'>{$categoryList[$c]['title']}</div>";
                                            foreach ($categoryIds as $seqno => $cId) {
                                                if($categoryList[$c]['id'] == $cId) {
                                                    $showDiv = "<div class='sel-tag' id='{$categoryList[$c]['id']}'>{$categoryList[$c]['title']}</div>";
                                                }
                                            }
                                        echo $showDiv;
                                        }
                                    }
                                    echo '</td>';
                                }
                            ?>
                            </tr>
                        </table>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="drop-sel-category">
            <input type='hidden' id="oriCategoryIds" value="<?= $item_info['categoryIds'] ?>">
            <?php foreach ($categoryIds as $seqno => $cId) : ?>
                <?php foreach ($categoryList as $category) : ?>
                    <?php if ($category['id'] == $cId) : ?>
                        <span key= <?=$category['id']?> class='tags'><i class='fas fa-times-circle' ></i><?=$category['title']?></span>
                    <?php endif ?>
                <?php endforeach ?>
            <?php endforeach ?>
        </div>
        
    </div>
    
    <div class="write-content">
        <hr>
        <input type= 'hidden' id="ori_content" value='<?=$item_info['content']?>'>
        <?= $markdown_content ?>
    </div>
</div>

<?= $delete_dialog ?>