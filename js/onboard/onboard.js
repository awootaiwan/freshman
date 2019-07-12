$(document).ready(function () {
    let postUrl = "";
    if ($("#loginUserId").val() == 0) {
        postUrl = `${baseUrl}api/getItemById`;
    } else {
        postUrl = `${baseUrl}onboardAdmin/getItemById`;
    }
    data = {
        id: $("#itemTrueId").val()
    };
    postAPI(postUrl, data)
    .then(json => {
        $("#ori_content").val(json.content);
        $("#ori_content").trigger("init");
    });
});

if ($(".item").attr("id").split("-")[1] != "") {
    $(`#${($(".item").attr("id")).split("-")[1]}`).css("color", "#4ca1e7");
    $(`#${$(".item").attr("id").split("-")[1]}`)[0].scrollIntoView();
}

if ($("#loginUserId").val() == 0) {
    $("#checkLabel").css('display', "none");
    $(".rate-content").css('display', "none");

    $(".item-list").find("li").each(function () {
        $(this).click(function () {
            let id = $(this).attr("id");
            location.href = `${baseUrl}onboard/touristJoin?id=${id}`;
        })
    });

} else {
    $(".item-list").find("li").each(function () {
        $(this).click(function () {
            let id = $(this).attr("id");
            location.href = `${baseUrl}onboard?id=${id}`;
        })
    });

    $(".item-checkbox").click(function () {
        let isChecked = document.getElementById("checkItem").checked;
        let id = $(".item").attr("id").split("-")[1];
        let checked = "";
        let postUrl = "";
        let data;

        checked = (isChecked) ? "1" : "0";

        data = {
            id,
            checked
        }
        postUrl = `${baseUrl}onboard/updRouteCheck`;

        postAPI(postUrl, data)
        .then(json => {
            if (json.result) {
                if (isChecked) {
                    if (json.nextUnCheckItemId != "") {
                        location.href = `${baseUrl}onboard?id=${json.nextUnCheckItemId}`;
                    } else {
                        location.href = `${baseUrl}onboard`;
                    }
                } else {
                    $(`#${id}`).attr("class", "item-li");
                    $(`#${id} i`).attr("class", "far fa-circle");
                    countToNumber($('.rate-content'), $('.item-li-check').length, all, 0);
                }
            }
            return json;
        })
    });

    var countToNumber = function (element, number, suffix, duration) {
        $({ count: parseInt(element.text().split("+")[0].replace(/\,/g, '')) }).animate({ count: number }, {
            duration: duration ? duration : 1000,
            easing: 'swing',
            step: function (now) {
                element.text(("已完成" + Math.floor(now) + suffix).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
            },
            complete: function () {
                countingFromZero = false;
            }
        });
    }

    var all = '/' + $('.item-list>li').length;
    countToNumber($('.rate-content'), $('.item-li-check').length, all, 0);
}

$(".rwd-menubtn").click(function () {
    if ($(".show-learn-left").css("left") == "-250px") {
        $(".show-learn-left").animate({ left: '0px' });
        $(".rwd-menubtn").animate({ left: '251px' });
    } else {
        $(".show-learn-left").animate({ left: "-250px" });
        $(".rwd-menubtn").animate({ left: '1px' });
    }
});

$(window).resize(function () {
    if ($(window).width() > 768) {
        $(".rwd-menubtn").css("display", "none");
        $(".show-learn-right").css("margin-left", "300px");
        $(".show-learn-left").css("left", "0px");
        $(".rwd-menubtn").css("left", "251px");
    }

    if ($(window).width() < 768) {
        $(".show-learn-right").css("margin-left", "50px");
        $(".show-learn-left").css("left", "-250px");
        $(".rwd-menubtn").css("display", "block");
        $(".rwd-menubtn").css("left", "1px");
    }
});