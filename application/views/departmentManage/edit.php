<div class="edit-dapt-layout">
    <div class="edit-dapt-close-bg"></div>
    <div class="edit-dapt-content">
        <i class="edit-close fas fa-times fa-lg"></i>
        <form autocomplete="off">
            <div class="error-area">
                <span class="error-msg"></span>
            </div>
            <input type="hidden" value="" id="action">
            <input type="hidden" value="" name="id" id="deptId">
            <div class="dept-input-area">
                <div class="dept-area">
                    <h4>代碼</h4>
                    <input type="input" name="abbreviation" required="required" class="edit-input">
                </div>
                <div class="dept-area">
                    <h4>名稱</h4>
                    <input type="input" name="name" required="required" class="edit-input">
                </div>
            </div>
            <div class="dept-button-area">
                <input type="reset" class="edit-button" value="清除">
                <input type="submit" id="editDeptOK" class="edit-button" value="確認">
            </div>
        </form>
    </div>
</div>