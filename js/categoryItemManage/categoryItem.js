$(".show-categorys").find("li").each(function () {
    $(this).click(function () {
        let id = $(this).attr("id");
        location.href = `${baseUrl}onboardAdmin?id=${id}`;
    })
});

if ($('.content').attr("id") == 1 || $('.content').attr("id") == 2) {
    $("#btnDeleteCategory").parent().empty();
}

if ($('.content').attr("id")) {
    $(`#${$('.content').attr("id")}`)[0].scrollIntoView();
}

$("#btnAddCategory").click(function () {
    $(".edit-category").css("display", "block");
    $("#description").val("");
    $("#title").val("");
    $("#id").val("");
    document.getElementById("title").focus();
});

$("#btnCloseAddCategory").click(function () {
    $(".edit-category").css("display", "none");
    $("#title").val("");
    $("#description").val("");
});

$("#btnOKAddCategory").click(function () {
    let id = $("#id").val();
    let title = $("#title").val();
    let description = $("#description").val();
    let postUrl = "";
    let data;
    if(title.length < 1) {
        $(".error").html("標題不可為空");
    } else {
        if (id.length > 0) {
            data = {
                id,
                title,
                description
            }
            postUrl = `${baseUrl}onboardAdmin/editCategory`;
        } else {
            data = {
                title,
                description
            }
            postUrl = `${baseUrl}onboardAdmin/insCategory`;
        }
    
        postAPI(postUrl, data)
        .then(json => {
            if (json.result) {
                location.href = `${baseUrl}onboardAdmin?id=${json.id}`
            }
            return json;
        })
    }
    
});

$("#btnEditCategory").click(function () {
    $(".edit-category").css("display", "block");
    $("#title").val($("#categoryTitle").html());
    $("#description").val($(".tooltiptext").html());
    $("#id").val($(".content").attr("id"));

});

$("#btnDeleteCategory").click(function () {
    $(".delete-dialog").css("display", "block");
    $("#delType").val("分類");
    $("#delId").val($(".content").attr("id"));
    $("#dialogText").html(`您確定要刪除分類 <br> ${$("#categoryTitle").html()}`);
});

$("#btn-dialogCancel").click(function () {
    $(".delete-dialog").css("display", "none");
});

$("#close-btn").click(function () {
    $(".edit-category").css("display", "none");
    $("#title").val("");
    $("#description").val("");
});

$("#btn-dialogConfirm").click(function () {
    let id = $("#delId").val();
    let data = {id}
    let delUrl = "";
    let newCategoryIds = [];
    $(".tags").each(function() {
        newCategoryIds.push(`${$(this).attr("key")}`);
    })
    if($("#delType").val() == "分類") {
        delUrl = `${baseUrl}onboardAdmin/delCategory`;
        postAPI(delUrl, data)
        .then(json => {
            if (json.result) {
                alert("刪除成功！！！！");
                location.href = `${baseUrl}onboardAdmin`;
            }
            return json;
        }) 
    } else if($("#delType").val() == "項目") {
        let categoryId = $(".content").attr("id");
        if (categoryId != undefined) {
            data["categoryId"] = categoryId;
        }
        let newCategoryIds = [];
        $(".tags").each(function() {
            newCategoryIds.push(`${$(this).attr("key")}`);
        })
        delUrl = `${baseUrl}onboardAdmin/delItem`
        postAPI(delUrl, data)
        .then(json => {
            if (json.result) {
                alert("刪除成功！！！！");
                if (newCategoryIds.length > 0) {
                    location.href = `${baseUrl}onboardAdmin?id=${newCategoryIds[0]}`;
                } else {
                    location.reload();
                }
            }
            return json;
        })
    }
});

$(".deleteItem").click(function() {
    $(".delete-dialog").css("display", "block");
    $("#delType").val("項目");
    $("#delId").val($(this).attr("key"));
    $("#dialogText").html(`您確定要刪除項目 \n ${$(this).parents(".item").find(".show-item").html()}`);
});

$("#btnToAddItemPage").click(function () {
    let id =$(".content").attr("id");
    location.href = `${baseUrl}onboardAdmin/addItemManage?categoryId=${id}`;
});

$(".editItem").click(function () {
    let id = $(this).attr("key");
    location.href = `${baseUrl}onboardAdmin/editItemManage?id=${id}`;
});

$("#btnSearch").click(function () {
    let keyword = $("#search").val();
    if (keyword == "0") {
        keyword = "!0";
    }
    location.href = `${baseUrl}onboardAdmin?keyword=${keyword}`;
});

$(".content-items").sortable({
    placeholder: "placeholder",
    start: function(e, ui){
        ui.item.css('transform', 'rotate(5deg)');
        ui.item.css('cursor', 'grabbing');
    },
    stop: function(e, ui){
        ui.item.css('transform', '');
        ui.item.css('cursor', 'pointer');
    },
    update: function () {
        let i = 1;
        $(".content-items").find("li").each(function () {
            let id = $(this).attr("data");
            if (id != null) {
                let data = {
                    id,
                    "sort": i
                };
                let url = `${baseUrl}onboardAdmin/updCategoryItemSort`;
                postAPI(url, data)
                i += 1;
            }
        });
    }
});
$(".content-items").disableSelection();

$(".content-items").find("li").each(function () {
    $(this).click(function (e) {
        let id = $(this).attr("key");
        if (e.target.className != "drop-down-item" && e.target.className != "deleteItem") {
            location.href = `${baseUrl}onboardAdmin/editItemManage?id=${id}`;
        }
    });
});

$("#search").keydown(function(event){
    if( event.which == 13 ) {
        $("#btnSearch").click();
    }
});

$(`.show-categorys #${$(".content").attr("id")}`).css({'background-color':'rgb(70, 156, 255)','color':'white'});

$(window).resize(function () {
    if ($(window).width() > 768) {
        $(".show-menu").css({left:'0'});
        $("#btnAddCategory").css({left:'0'});
        $("#add-tutorial").css({left:'-255px'});
        $(".rwd-menubtn").css({left:'250px'});
    }
    
    if ($(window).width() < 768) {
        $(".show-menu").css({left:'-255px'});
        $("#btnAddCategory").css({left:'-255px'});
        $("#add-tutorial").css({left:'-255px'});
        $(".rwd-menubtn").css({left:'0px'});
    }
});

$(".rwd-menubtn").click(function() {
    if ($(".show-menu").css("left") == "-255px") {
        $(".show-menu").animate({left:'0'});
    } else {
        $(".show-menu").animate({left:'-255px'});
    }

    if ($("#btnAddCategory").css("left") == "-255px") {
        $("#btnAddCategory").animate({left:'0'});
    } else {
        $("#btnAddCategory").animate({left:'-255px'});
    }

    if ($("#btnAddCategory").css("left") == "-255px") {
        $(".rwd-menubtn").animate({left:'250px'});
    } else {
        $(".rwd-menubtn").animate({left:'0'});
    }

    if ($(".rwd-menubtn i").css("transform") == "none") {
        $(".rwd-menubtn i").css("transform", "rotate(180deg)");
    } else {
        $(".rwd-menubtn i").css("transform", "none");
    }
});

if ($("#search").val()) {
    $(".show-menu").css({left:'0'});
    $("#btnAddCategory").css({left:'0'});
    $(".rwd-menubtn").css({left:'250px'});
}