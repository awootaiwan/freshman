checkMenu();

const tutorial_id = $(".relation").data('id');

if (tutorial_id) {
    $(".getTutorial").each(function () {
        if ($(this).attr('id') == tutorial_id) {
            $(this).css({'background-color':'rgb(70, 156, 255)','color':'white'});
        }
    })
}
$(".delete-tutorial").click(function () {
    $(".delete-dialog").css("display", "block");
    $("#delId").val($(this).attr("key"));
    $("#delType").val("deltutorial");
    $("#dialogText").html(`您確定要刪除教程 - ${$("#name" + $(this).attr("key")).html()}`);
});

$("#btn-dialogCancel").click(function () {
    $(".delete-dialog").css("display", "none");
});

$("#add-tutorial").click(function () {
    $(".edit-category").css("display", "block");
    document.getElementById("tutorialTitle").focus();
})

$("#btnCloseAddTutorial").click(function () {
    $(".edit-category").css("display", "none");
    $("#tutorialTitle").val('');
})

$("#btnOKAddTutorial").click(function () {
    let title = $("#tutorialTitle").val().trim();
    if (title) {
        let data = {
            'action': "add",
            title
        }
        let url = `${baseUrl}ManageLearn/manageTutorial`
        postAPI(url, data)
        .then(function (value) {
            if (value.result) {
                $("#tutorialTitle").val('');
                $(".edit-category").css("display", "none");
                location.replace(baseUrl + '/ManageLearn?action=edit&id=' + value.tutorial_id)
            }
        });
    } else {
        alert("教程名稱沒有輸入喔！！");
    }
})

$("#btn-dialogConfirm").click(function () {
    var delType = $("delType").val();
    if (delType == 'deltutorial') {
        let id = $("#delId").val();
        let data = {
            'action': "delete",
            'tutorial_id': id
        }
        fetch(`${baseUrl}ManageLearn/manageTutorial`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        }).then((response) => {
            return response.json();
        }).then((jsonData) => {
            let status = jsonData;
            if (status) {
                alert('刪除成功');
                location.reload();
            }
        }).catch((err) => {
            console.log('錯誤:', err);
        })
    }
});

$(".show-categorys").on('click', 'li', function () {
    location.replace(baseUrl + '/ManageLearn?action=edit&id=' + $(this).attr('id'))
})

$(".rwd-menubtn").click(function () {
    if ($(".show-menu").css("left") == "-255px") {
        $(".show-menu").animate({ left: '0' });
    } else {
        $(".show-menu").animate({ left: '-255px' });
    }

    if ($("#add-tutorial").css("left") == "-255px") {
        $("#add-tutorial").animate({ left: '0' });
    } else {
        $("#add-tutorial").animate({ left: '-255px' });
    }

    if ($("#add-tutorial").css("left") == "-255px") {
        $(".rwd-menubtn").animate({ left: '250px' });
    } else {
        $(".rwd-menubtn").animate({ left: '0' });
    }

    if ($(".rwd-menubtn i").css("transform") == "none") {
        $(".rwd-menubtn i").css("transform", "rotate(180deg)");
    } else {
        $(".rwd-menubtn i").css("transform", "none");
    }
});


function checkMenu() {
    $("#ori_content").trigger("init");
    if ($('.show-tutorial').children().is('div')) {
        $('.show-menu').css('left', '-255px');
        $('#add-tutorial').css('left', '-255px');
        $('.rwd-menubtn').css('display', 'block');
    }
}
