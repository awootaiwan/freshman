$(".add-dept-button").click(function () {
    $(".edit-dapt-layout").css("display", "block");
    $("#action").val("add");
    $("input[name='abbreviation']").focus();
})

$(".edit-close").click(function () {
    $(".edit-dapt-layout").css("display", "none");
    clearEdit();
})

$(".edit-dapt-close-bg").click(function () {
    $(".edit-dapt-layout").css("display", "none");
    clearEdit();
})

$(".fa-edit").click(function () {
    $(".edit-dapt-layout").css("display", "block");
    $("#action").val("edit");
    let id = $(this).data("id");
    let abbr = $(this).data("abbr");
    let name = $(this).data("name");
    $("input[name='id']").val(id);
    $("input[name='abbreviation']").val(abbr);
    $("input[name='name']").val(name);
    $("input[name='abbreviation']").focus();
})

$("#editDeptOK").click(function (e) {
    $("form").submit();
    e.preventDefault();
})

$("form").on("submit", function (e) {
    e.preventDefault();
    let id = $("input[name='id']").val();
    let abbr = $("input[name='abbreviation']").val();
    let name = $("input[name='name']").val();

    let action = $("#action").val();
    let colName = "";
    if (abbr == "") {
        colName = "代碼";
    } 
    if (name == "") {
        if (colName != "") colName += "、";
        colName += "名稱";
    }
    if (colName != "") {
        $(".error-msg").text(colName + "不得為空");
    } else {
        if (action == "edit") {
            let data = {
                id,
                abbreviation: abbr,
                name
            }
            let url = `${baseUrl}DeptManage/updDepartment`;
            postAPI(url, data)
            .then(json => {
                if (json.result) {
                    alert("更新成功！");
                    location.reload();
                } else {
                    $(".error-msg").text(json.msg);
                    console.error('msg :', json.msg);
                }
                return json;
            });
        } else {
            let data = {
                abbreviation: abbr,
                name
            }
            let url = `${baseUrl}DeptManage/insDepartment`;
            postAPI(url, data)
            .then(json => {
                if (json.result) {
                    alert("新增成功！");
                    location.reload();
                } else {
                    $(".error-msg").text(json.msg);
                    console.error('msg :', json.msg);
                }
                return json;
            });
        }
    }
})

function clearEdit() {
    $("#action").val("");
    $("input[name='id']").val("");
    $("input[name='abbreviation']").val("");
    $("input[name='name']").val("");
    $(".error-msg").text("");
}

$(".fa-trash-alt").click(function () {
    $(".delete-dialog").css("display", "block");
    let abbr = $(this).data('abbr');
    let name = $(this).data('name');
    $("#delId").val(abbr);
    $("#dialogText").text(`您確定要刪除${name}?`)
})

$("#btn-dialogCancel").click(function () {
    $(".delete-dialog").css("display", "none");
    $("#delId").val("");
    $("#dialogText").text("");
})

$("#btn-dialogConfirm").click(function () {
    let id = $("#delId").val();
    let data = {
        abbreviation: id
    }
    let url = `${baseUrl}DeptManage/delDepartment`;
    postAPI(url, data)
    .then(json => {
        if (json.result) {
            alert("刪除成功！");
            location.reload();
        }
    })
})