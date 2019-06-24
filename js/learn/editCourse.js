$('.course-add').click(function () {
    if ($(".hide-course").css("display", "block")) {
        $(".topnav .active").css("display", "none");
    }
    $('#course-id').val('');
    $(".hide-course").css("display", "block");
    clearCourse();
	$("#course-text").focusToEnd();
});

$('.relation-edit ul li').click(function () {
    if ($(".hide-course").css("display", "block")) {
        $(".topnav .active").css("display", "none");
    }
});

function clearCourse() {
    $(".CodeMirror").trigger("clear");
    $("#course-text").val('');
    $("#delete-course").hide();
}

$("#storage-course").click(function () {
    var text = $(".mark-down").text();
    var title = $(".course-text-set").val().trim();
    var id = $("#course-id").val();
    if (title == '' || text == '') {
        alert('請先填寫課程名稱及課程內容');
    } else {
        if (id != '') {
            let data = { 'action': 'update', 'cid': id, 'title': title, 'content': text }
            manageCourse('manage', data).then(function (value) {
                if (value.result) {
                    alert('儲存成功');
                    $(".hide-course").css("display", "none");
                    $("#" + id)[0].firstChild.textContent = title;
                } else {
                    alert('並未更動');
                }
            });
        } else {
            let data = { 'title': title, 'content': text };
            manageCourse('add', data).then(function (value) {
                $(".relation > ul").append(`<li id = ${value.id} > ${value.title} </li>`);
                $(".hide-course").css("display", "none");
            });
        }
    }
});

$(".connectSortable ").on("click", 'li', function (event) {
    if (this == event.target) {
        var id = $(this).prop('id')
        var data = { 'cid': id }
        manageCourse('set', data).then(function (value) {
            clearCourse();
            $(".hide-course").css("display", "block");
            $("#course-text").val(value.title);
            $("#course-id").val(value.id);
            $("#ori_content").val(value.content);
            $("#ori_content").trigger("init");
            $("#delete-course").show();
            $("#course-text").focusToEnd();
        })
    }
})

$("#delete-course").click(function () {
    $(".delete-dialog").css("display", "block");
    $("#delId").val($("#course-id").val());
    $("#delType").val('delCourse');
    $("#dialogText").html(`您確定要刪除此課程 `);
});

$("#btn-dialogCancel").click(function () {
    $(".delete-dialog").css("display", "none");
});

$(".edit-course .closebtn").click(function() {
    $(".hide-course").css("display","none");
    $(".topnav .active").css("display", "block");
    clearCourse()
});

$("#btn-dialogConfirm").click(function () {
    var id = $("#delId").val();
    var data = { 'action': 'delete', 'cid': id }
    var delType = $("#delType").val();
    if (delType == 'delCourse') {
        manageCourse('manage', data)
            .then(function (value) {
                $(".delete-dialog").css("display", "none");
            if (value.result) {
                $(".hide-course").hide();
                $("#" + id).remove();
                alert('刪除成功');
            }else{
                alert('刪除失敗。教程'+value.set+'裡有此課程所以無法刪除');
            }
        });
    }
});

async function manageCourse(action, params) {
    if (action == 'add') {
        URL = `${baseUrl}manageLearn/addCourse`;
    }
    if (action == 'set') {
        URL = `${baseUrl}manageLearn/setCourse`;
    }
    if (action == 'manage') {
        URL = `${baseUrl}manageLearn/manageCourse`;
    }
    return await connectAPI(URL, params);
};

async function connectAPI(URL, data) {
    try {
        let response = await fetch(URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        let rlt = await response.json();

        return rlt;
    } catch (err) {
        console.log('錯誤:', err);
    }
} 
