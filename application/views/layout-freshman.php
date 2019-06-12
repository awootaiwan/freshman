<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="<?= base_url() ?>css/freshman/reset.css" rel="stylesheet" type="text/css" media="all">
    <link href="<?= base_url() ?>css/freshman/basic.css" rel="stylesheet" type="text/css" media="all">
    <link href="<?= base_url() ?>css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <script src="<?= base_url() ?>js/jquery-3.3.1.js"></script>
    <link href="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
    <script src="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.js"></script>

    <?php foreach ($css_src as $css) : ?>
        <link href="<?= $css ?>" rel="stylesheet">
    <?php endforeach ?>
    <link href="<?= base_url() ?>css/fullscreen.css" rel="stylesheet" type="text/css" media="all">
    <link href="<?= base_url() ?>js/fold/foldgutter.css" rel="stylesheet" type="text/css" media="all">
    <script>
        var baseUrl = `<?= base_url() ?>`;
    </script>
    <title><?= $title ?></title>
</head>

<body>
    <ul class="header">
    <input type="hidden" id="loginUserId" value="<?= $userId ?>">

        <!-- freshman-logo li -->
        <li class="freshman-logo">
            <img src="<?= base_url() ?>images/freshman-logo.png">
        </li>
        <!-- right menu -->
        <!-- user -->
        <ul class="rwd-user">
            <li><?= $logtype?></li>
            <span class="g-signin2" data-onsuccess="onSignIn" hidden></span>
            <li class="user"><?= $userName ?></li>
            <li class="userIcon"><i class="fas fa-user"></i></li>
            
        </ul>
        <ul class="rwd-hide">

            <?php foreach ($header_menu as $menu) : ?>
                <li><a href="<?= $menu['route'] ?>"><i class="<?= $menu['icon'] ?>"></i><?= $menu['menu_name']; ?></a></li>
            <?php endforeach ?>
            <?php if (!empty($backend_menu)) : ?>
            <div class="dropdown">
                <button class="dropbtn"><i class="fas fa-user-cog"></i>後台</button>
                <div class="dropdown-content">
                    <?php foreach ($backend_menu as $menu) : ?>
                        <a href="<?= $menu['route'] ?>"><?= $menu['menu_name']; ?></a>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif?>
            
        </ul>
    </ul>

    <div class="topnav">
        <div class="active">
            <img src="<?= base_url() ?>images/freshman-logo.png">
            <div class="rwd-user2">
                <i class="fas fa-user"></i>
                <?= $userName ?>
            </div>
        </div>
        <ul id="myLinks">
            <?php foreach ($header_menu as $menu) : ?>
                <li><a href="<?= $menu['route'] ?>"><i class="<?= $menu['icon'] ?>"></i><?= $menu['menu_name']; ?></a></li>
            <?php endforeach ?>
            <?php if (!empty($backend_menu)) : ?>
            <li class="back"><a href="#"><i class="fas fa-user-cog"></i>後台</a></li>
                <?php foreach ($backend_menu as $menu) : ?>
                   <li class="back-dropdown"><a href="<?= $menu['route'] ?>"><?= $menu['menu_name']; ?></a></li>
                <?php endforeach ?>
            <?php endif?>
            <li><?= $logtype?></li>
        </ul>
        <a href="javascript:void(0);" class="icon" onclick="showMenu()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
    

    <div class="header-top">
    </div>
    
    <div id="main">
    <div class="loading" id="loading">
        <?= $main_components; ?>
    </div>
    <div class="send-message">
        <div class="bg-close"></div>
        <div class="show-send-message-btn">
            <i class="far fa-comment"></i>
            <i class="fas fa-times"></i>
        </div>
        <table class="send-message-content">
            <tr>                
                <td class="message-type">
                    回報類型：
                    <div class="select-type">
                        <select id='messageType'>
                            <option value=''>請選擇...</option>
                            <option value='1'>通知管理員</option>
                            <option value='2'>系統問題/回饋</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="message-content" style="width:100%;height:150px;">
                    內容訊息：<textarea class='messageContent' placeholder="說明..."></textarea>
                <td>
            </tr>
            <tr>
                <td class="send-message-error">
                    <span class="sendMessageError"></span>
                </td>
            </tr>
            <tr>
                <td class="btn-cancel-message">
                    <div class="messageBtn">
                        <button id="btnCancelMessage">清除</button><button id="btnSendMessage">傳送</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
<script src="<?= base_url() ?>js/codemirror.js"></script>
<script src="<?= base_url() ?>js/sublime.js"></script>
<script src="<?= base_url() ?>js/modeMD.js"></script>
<script src="<?= base_url() ?>js/overlay.js"></script>
<script src="<?= base_url() ?>js/gfm.js"></script>
<script src="<?= base_url() ?>js/xml.js"></script>
<script src="<?= base_url() ?>js/xml-fold.js"></script>
<script src="<?= base_url() ?>js/placeholder.js"></script>
<script src="<?= base_url() ?>js/fullscreen.js"></script>
<script src="<?= base_url() ?>js/edit/continuelist.js"></script>
<script src="<?= base_url() ?>js/edit/closebrackets.js"></script>
<script src="<?= base_url() ?>js/edit/closetag.js"></script>
<script src="<?= base_url() ?>js/edit/matchbrackets.js"></script>
<script src="<?= base_url() ?>js/edit/matchtags.js"></script>
<script src="<?= base_url() ?>js/edit/indentlist.js"></script>
<script src="<?= base_url() ?>js/fold/brace-fold.js"></script>
<script src="<?= base_url() ?>js/fold/comment-fold.js"></script>
<script src="<?= base_url() ?>js/fold/foldcode.js"></script>
<script src="<?= base_url() ?>js/fold/foldgutter.js"></script>
<script src="<?= base_url() ?>js/fold/indent-fold.js"></script>
<script src="<?= base_url() ?>js/fold/markdown-fold.js"></script>
<script src="<?= base_url() ?>js/fold/xml-fold.js"></script>



<script src="<?= base_url() ?>js/command/command.js"></script>

<script src="https://apis.google.com/js/platform.js" async defer></script>

<meta name="google-signin-scope" content="profile email">
<meta name="google-signin-client_id" content="153023794012-7oso7r9dfk4h7tbhff42s61kpujlv434.apps.googleusercontent.com">
<?php foreach ($js_src as $js) : ?>
    <script src="<?= $js ?>"></script>
<?php endforeach ?>

</html>
