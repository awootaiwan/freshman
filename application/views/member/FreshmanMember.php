<link href="<?php echo base_url();?>css/freshman/reset.css" rel="stylesheet" type="text/css" media="all">
<link href="<?php echo base_url();?>css/freshmanMember/freshmanMember.css" rel="stylesheet" type="text/css" media="all">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<div class="member">
    <h1>個人資訊</h1>
    <div class="content">
        <div><i class="fas fa-user-alt"></i>名稱<input id="user_name" type="text" value="<?= $name?>" readonly></div>
        <div><i class="fas fa-envelope"></i>Gmail<input id="user_gmail" type="text" value="<?= $mail?>" readonly></div>
        <div><i class="fas fa-sitemap"></i>部門
        <select id="user_dept">
            <option value="">請選擇</option>
            <?php foreach($department as $dept):?>
                <option value="<?= $dept['abbreviation']?>"><?= $dept['name']?></option>
            <?php endforeach?>
        </select></div>
    </div>
    <div class="button">
        <div class='btnOKAddUser'>確定</div>
        <div class='btnCancelAddUser'>取消</div>
    </div>
</div>

<?php if(!empty($js_src)):?>
    <?php foreach($js_src as $js):?>
        <script src="<?= $js ?>"></script>
    <?php endforeach?>
<?php endif?>
