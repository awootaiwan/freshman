<!DOCTYPE html>
<html>

<head>
    <title>awoo Freshman Welocme</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link href="<?= base_url() ?>css/freshman/reset.css" rel="stylesheet" type="text/css" media="all">
    <link href="<?= base_url() ?>css/welcome/welcome.css" rel="stylesheet" type="text/css" media="all">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script>
        var baseUrl = `<?= base_url() ?>`;
    </script>
</head>

<body>
    <div class="beautiful-line">
        <div class="top">
            <div class="top1"></div>
            <div class="top2"></div>
            <div class="top3"></div>
            <div class="top4"></div>
        </div>
    </div>
    <div class="welocome">
        <img src="<?= base_url() ?>images/working.png">
        <img src="<?= base_url() ?>images/freshman-logo.png">
    </div>
    <div class="login-frist">這位菜逼八請先登入吶(っ◔◡◔)っ
        <div class="g-signin2" onclick="clickLogin();" data-onsuccess="onSignIn"></div>
    </div>
    
    <div class="login-tourist">只是來逛一逛(っ・ω・)っ
    <a href="<?= base_url() ?>onboard/touristJoin">
        <button class="login-tourist-button">遊客登入</button>
    </a>
    </div>

    <div class="rwd-login-frist">這位菜逼八請先登入吶</div>
    <div class="rwd-face-font">
            (っ◔◡◔)っ<div class="g-signin2" onclick="clickLogin();" data-onsuccess="onSignIn"></div>
    </div>

    <div class="rwd-login-tourist">只是來逛一逛(っ・ω・)っ
    <a href="<?= base_url() ?>onboard/touristJoin">
        <button class="rwd-login-tourist-button">遊客登入</button>
    </a>
    </div>

</body>
<script src="<?= base_url() ?>js/jquery-3.3.1.js"></script>
<link href="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<script src="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<meta name="google-signin-client_id" content="153023794012-7oso7r9dfk4h7tbhff42s61kpujlv434.apps.googleusercontent.com">
<script src="<?= base_url() ?>js/command/command.js" async defe></script>

</html>