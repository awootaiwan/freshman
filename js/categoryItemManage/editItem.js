$(document).ready(function() {
    let postUrl = `${baseUrl}onboardAdmin/getItemById`;
    data = {
        id : $("#id").val()
    };
    postAPI(postUrl, data)
    .then(json => {
        $("#ori_content").val(json.content);
        $("#ori_content").trigger("init");
        return json;
    }).then(json => {
        $("#title").focusToEnd();
        return json;
    });
});

$(".drop-sel-category").on("click", ".fa-times-circle", function() {
    let id = $(this).parent().attr("key");
    $(".all-tag div").each(function() {
        if($(this).attr("id") == id) {
            $(this).attr("class", "unsel-tag");
        }
    });
    $(this).parent().remove();
})


$("#deleteItem").click(function() {
    $(".delete-dialog").css("display", "block");
    $("#delType").val("項目");
    $("#delId").val($("#id").val());
    $("#dialogText").html(`您確定要刪除項目 <br> ${$("#title").val()} <br><br> 這將會把所有分類的項目都刪除！`);
});

$("#btnInsertDone").click(function() {
    let title = $("#title").val();
    let content = $(".mark-down").text();
    let categoryIds = [];
    let error = "";
    $(".tags").each(function() {
        categoryIds.push(`${$(this).attr("key")}`);
    })
    if (categoryIds.length < 1) {
        error += "請至少選擇一個分類 <br>";
    } else if (title.length < 1) {
        error += "標題不可為空 <br>";
    } else if (content.length < 1) {
        error += "內容不可為空 <br>";
    } else {
        categoryIds = categoryIds.join(",");
        let data = {
            title,
            content,
            categoryIds
        }
        let postUrl = `${baseUrl}onboardAdmin/insItem`;
        postAPI(postUrl, data)
        .then(json => {
            if (json.result) {
                alert("新增完成！！");
                $("#btnCancel").click();
            }
            return json
        })
    }
    $(".error").html(error);
});

$("#btnUpdateDone").click(function() {
    let id = $("#id").val();
    let title = $("#title").val();
    let content = $(".mark-down").text();
    let newCategoryIds = [];
    let oldCategoryIds = $("#oriCategoryIds").val();
    let error = "";
    $(".tags").each(function() {
        newCategoryIds.push(`${$(this).attr("key")}`);
    })
    if (newCategoryIds.length < 1) {
        error = "請至少選擇一個分類 <br>";
    } else if (title.length < 1) {
        error = "標題不可為空 <br>";
    } else if (title.length > 40) {
        error = "標題不可超過40個字 <br>"
    } else if (content.length < 1) {
        error = "內容不可為空 <br>";
    } else {
        newCategoryIds = newCategoryIds.join(",");
        let data = {
            id,
            title,
            content,
            newCategoryIds,
            oldCategoryIds
        }
        
        let postUrl = `${baseUrl}onboardAdmin/editItem`;
        postAPI(postUrl, data)
        .then(json => {
            if (json.result) {
                alert("修改完成！");
                $("#btnCancel").click();
            }
            return json
        })
    }
    $(".error").html(error);
});

$(".all-tag div").each(function() {
    $(this).click(function() {
        if ($(this).attr("class") == "unsel-tag") {
            $(this).attr("class", "sel-tag");
            let html = `<span key='${$(this).attr("id")}' class='tags'><i class='fas fa-times-circle' ></i>${$(this).html()}</span>`;
            $(".drop-sel-category").append(html);
        } else {
            let self = $(this);
            $(this).attr("class", "unsel-tag");
            $(".drop-sel-category span").each(function() {
                if($(this).attr("key") == self.attr("id")) {
                    $(this).remove();
                }
            })
        }

    })
});

$("#btnCancel").click(function() {
    let newCategoryIds = [];
    $(".tags").each(function() {
        newCategoryIds.push(`${$(this).attr("key")}`);
    })
    location.href = `${baseUrl}onboardAdmin?id=${newCategoryIds[0]}`;
})