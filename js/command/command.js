var _LoadingHtml = `
                    <div 
                        id="loadingDiv" 
                        style="
                        position:absolute;
                        left:0;
                        width:100%;
                        height:100vh;
                        top:0;
                        background:#fff;
                        opacity:0.9;
                        filter:alpha(opacity=80);
                        z-index:10000;
                    ">
                        <div 
                            style="position: absolute;
                            left: 50%;
                            top: 50%;
                            transform: translate(-30%,-50%);
                            width: 30%;
                            font-size:3vw;" >加載中請稍候...
                        </div>
                    </div>`;
document.write(_LoadingHtml);

window.onload = function () {
    var loadingMask = document.getElementById('loadingDiv');
    if ($("#loadingDiv").length > 0) {
        loadingMask.parentNode.removeChild(loadingMask);
    }
};



async function postAPI(url, data) {
    return await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(res => {
        return res.json();
    }).catch((err) => {
        console.log('錯誤:', err);
    })
}

function showMenu() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}

$("#myLinks .back").click(function () {
    if ($("#myLinks .back-dropdown").css("display") == 'none') {
        $("#myLinks .back-dropdown").css("display", "block");
    } else {
        $("#myLinks .back-dropdown").css("display", "none");
    }
});

$('.search').keypress(function (e) {
    var key = window.event ? e.keyCode : e.which;
    if (key == 13)
        $('btnSearch').click();
});

$(document).ready(function () {
    $('#search').focus();
});

$('#search').keypress(function (e) {
    if (e.which == 13) {
        $('#btnSearch').focus().click();
    }
});

var clickedLogin = false;

function clickLogin() {
    clickedLogin = true;
}

function onSignIn(googleUser) {
    if (clickedLogin) {
        var profile = googleUser.getBasicProfile();
        if (profile) {
            var data = {
                "google_idx": profile.getId(),
                "name": profile.getName(),
                "gmail": profile.getEmail(),
                "photoUrl": profile.getImageUrl(),
            };
            postAPI(`${baseUrl}/Login/checkMember`, data)
                .then(function (rlt) {
                    if (rlt.errno) {
                        alert(rlt.errmsg);
                    }
                    if (rlt.loc) {
                        window.location.href = rlt.loc;
                    }
                })
        }
    }
}



function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    var data = {}
    postAPI(`${baseUrl}/Login/clearLoginData`, data)
        .then(function (rlt) {
            if (rlt) {
                auth2.signOut().then(function () {
                    window.location.href = `${baseUrl}/WelcomeFreshman`;
                });
            }
        })
}

$(document).ready(function () {
    cheangecolor()
})
async function cheangecolor() {
    $(".sss").css({
        "background-color": "blue",
        "font-size": "200%",
    });
}

$("#btnSendMessage").click(function () {
    let messageType = $("select[id='messageType']").val();
    let messageContent = $(".messageContent").val();

    let error = "";
    if (messageType.length < 1) {
        error += "請選擇回報類型 <br>";
    }
    if (messageContent.length < 1) {
        error += "訊息內容不可為空 <br>";
    }

    if (error == "") {
        let data = {
            "messageType": messageType,
            "messageContent": messageContent
        }
        let postUrl = `${baseUrl}/CurlManage/sendMessage`;
        $(".sendMessageError").css("color", "#999");
        $(".sendMessageError").html("訊息傳送中...");
        $(".messageBtn").css('display', "none");
        postAPI(postUrl, data)
        .then(json => {
            if (json.result) {
                alert("訊息傳送成功～\n我們將儘快為您處理，謝謝！");
                $("#btnCancelMessage").click();
                $(".messageBtn").css('display', "block");
            } else {
                alert("傳送失敗：".json.msg);
            }
            return json
        })
    } else {
        $(".sendMessageError").css("color", "#f81d47");
        $(".sendMessageError").html(error);
    }
});

$("#btnCancelMessage").click(function () {
    $("select[id='messageType']").val("");
    $(".messageContent").val("");
    $(".sendMessageError").html("");
});

$(".show-send-message-btn").click(function () {
    $(".send-message .bg-close").css("display", "block");
    if ($('.send-message-content').css("display") == "none") {
        $(".show-send-message-btn i:nth-child(1)").css("display", "none");
        $(".show-send-message-btn i:nth-child(2)").fadeIn("fast");
        $(".send-message-content").fadeIn("fast");
    } else {
        $(".show-send-message-btn i:nth-child(2)").css("display", "none");
        $(".show-send-message-btn i:nth-child(1)").fadeIn("fast");
        $(".send-message-content").css('display', 'none');
        $(".send-message .bg-close").css("display", "none");
    }
});

$(".send-message .bg-close").click(function () {
    $(".send-message .bg-close").css("display", "none");
    $(".send-message-content").css('display', 'none');
    $(".show-send-message-btn i:nth-child(2)").css("display", "none");
    $(".show-send-message-btn i:nth-child(1)").fadeIn("fast");
});

$(".markdown-learn").click(function () {
    if ($(".markdown-learn-description").css("visibility") == "visible") {
        $(".markdown-learn-description").css("visibility", "hidden");
    } else {
        $(".markdown-learn-description").css("visibility", "visible");
    }
})

$.fn.focusToEnd = function () {
    return this.each(function () {
        var v = $(this).val();
        $(this).focus().val("").val(v);
    });
}; 