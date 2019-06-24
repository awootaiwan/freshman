$(".btnCancelAddUser").on('click', function() {
    window.location.href = baseUrl + "WelcomeFreshman"
})

$(".btnOKAddUser").on('click', function() {
    let url = baseUrl + "Login/setFreshmanUser"
    let data = {
        "action" : "insert",
        "name" : $("#user_name").val(),
        "gmail" : $("#user_gmail").val(),
        "abbreviation" : $("#user_dept").val()
    }
    if(data['abbreviation'] != '') {
        postAPI(url, data)
        .then(function (rlt) {
            if(rlt.errno){
                alert(rlt.errmsg)
            }else{
                window.location.href = baseUrl + rlt.loc
            }
        })
    } else {
        alert("請選擇部門")
    }
})