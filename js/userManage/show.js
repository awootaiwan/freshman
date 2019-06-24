// var baseUrl = `${location.protocol}//${location.host}/freshman`;

$(".select-department select").change(function () {
    let url = `${baseUrl}userManage?`;
    location.href = url + "dept=" + this.value;
});

$(".isManager").on("click", function(e){
    if( ! $(e.target).is('input')) {
        let isManager = $(this).find("input[type='checkbox']");
        if (! isManager.prop('disabled')) {
            flag = isManager[0].checked;
            isManager[0].checked = !flag;
            isManager.trigger('change');
        }
    }
});

$('.isManager > input').change(function setManager () {
    let url = `${baseUrl}userManage/setManager`;
    if (this.checked) {
        method = 'add';
    } else {
        method = 'delete';
    }

    let uid = $(this).parents('tr').data('id');
    let data = new FormData();
    data.append("method", method);
    data.append("uid", uid);

    connectAPI(url, data)
    .then(function(value){
        if( !value.result) {
            alert('變更錯誤')
        }
    });
})

async function connectAPI (url, data) {
    return await fetch(url, {
        method: 'POST',
        body: data
    }).then(res => {
        return res.json();
    }).catch((err) => {
        console.log('錯誤:', err);
    })
}

$(".user-add").click(function (){
    $("#checktype").val("insert");
    $("#user_dept").val("");
    $(".hide-freshman-member").css("display", "block");
    $(".content input").removeAttr("readonly");
    $("#user_name").val("");
    $('#user_gmail').val("");
    $("#user_name").focus();
})

$(".user-name,.user-gmail,.user-dept").click(function (){
    $("#checktype").val("update");
    $(".hide-freshman-member").css("display", "block");
    $(".content input").removeAttr("readonly");
    let name = "";
    let gmail = "";
    let dept = "";

    if ($(this).hasClass('user-name')) {
        name = $(this).text();
        gmail = $(this).siblings(".user-gmail").text();
        dept = $(this).siblings(".user-dept").data('id');
    } else if ($(this).hasClass('user-gmail')) {
        gmail = $(this).text();
        name = $(this).siblings(".user-name").text();
        dept = $(this).siblings(".user-dept").data('id');
    } else if ($(this).hasClass('user-dept')) {
        gmail = $(this).siblings(".user-gmail").text();
        name = $(this).siblings(".user-name").text();
        dept = $(this).data('id');
    } 
    $("#updateId").val($(this).parents().data('id'));
    $("#user_name").val(name);
    $('#user_gmail').val(gmail);
    $("#user_dept").val(dept);
})

$(".btnCancelAddUser").click(function () {
    $(".hide-freshman-member").css("display", "none");
})

$(".member-bg-close").click(function () {
    $(".hide-freshman-member").css("display", "none");
})

$(".btnOKAddUser").click(function () {
    let url = baseUrl + "Login/setFreshmanUser"
    let data = {
        "action" : $("#checktype").val(),
        "name" : $("#user_name").val(),
        "gmail" : $("#user_gmail").val(),
        "abbreviation" : $("#user_dept").val(),
        "id" : $("#updateId").val()
    }
    
    if (data['name'] == "") {
        alert("請輸入姓名");
    } else if (data['gmail'] == "") {
        alert("請輸入信箱");
    } else if (data['abbreviation'] == "") {
        alert("請選擇隸屬部門");
    } else {
        postAPI(url, data)
        .then(function (rlt) {
            if(rlt.errno){
                alert(rlt.errmsg)
            } else {
                $(".hide-freshman-member").css("display", "none");
                if (data['action'] == "insert") {
                    window.location.reload()
                    alert('新增完成');
                }   
                if (data['action'] == "update") {
                    alert('修改完成');
                    window.location.reload()
                }
            }
        })   
    }
})