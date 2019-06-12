<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
<link href="<?= base_url() ?>css/markdownContent.css" rel="stylesheet">
<link href="<?= base_url() ?>css/learn/editCourse.css" rel="stylesheet">
<link href="<?= base_url() ?>css/categoryItemManage/categoryItem.css" rel="stylesheet">
<link href="<?= base_url() ?>css/highlight.css" rel="stylesheet">
<link href="<?= base_url() ?>css/codemirror.css" rel="stylesheet">
<link href="<?= base_url() ?>css/one-dark.css" rel="stylesheet">
<link href="<?= base_url() ?>css/markdownContent.css" rel="stylesheet">
<link href="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css">
<style>
    .write-content {
        /* height: 65vh; */
        text-align: center;
        color: #6c6c6c;
        /* width: 100vh; */
    }

    .cm-s-one-dark {
        /* pointer-events: none; */
    }

    /* .mark-show-content{
        width: 50%;
    } */
</style>
<html>
<div class="write-content">
    <input type='hidden' id="ori_content">

    <div class='edit-content'>
        <textarea id="text" key="show" class="mark-down"></textarea>
        <div id="test" class="mark-show mark-show-content"></div>
    </div>
</div>

</html>
<script src="<?= base_url() ?>js/jquery-3.3.1.js"></script>
<script src="<?= base_url() ?>js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
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
<script src="<?= base_url() ?>js/markdown.js"></script>
<script>
    var baseUrl = `<?= base_url() ?>`;

    var URL = `${baseUrl}/WelcomeFreshman/markdownData`;

    connectAPI(URL).then(function(value) {
        $("#ori_content").val(value)
        $("#ori_content").trigger("init")
    })

    // $(document).ready(function() {
    //     $("input, textarea").prop("readonly", true);
    // })

    async function connectAPI(URL) {
        try {
            let response = await fetch(URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
            });

            let rlt = await response.json();

            return rlt;
        } catch (err) {
            console.log('錯誤:', err);
        }
    }
</script>