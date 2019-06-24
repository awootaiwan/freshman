$( ".category-list" ).sortable({
    items: "li:not(.no-sort)",
    placeholder: "placeholder",
    connectWith: ".category-list",
    start: function(e, ui){
        ui.item.css('transform', 'rotate(5deg)');
        ui.item.css('cursor', 'grabbing');
    },
    stop: function(e, ui){
        ui.item.css('transform', '');
        ui.item.css('cursor', 'pointer');
        let url = `${baseUrl}onboardAdmin/updUserRouteSort`;
        let i = 1;
        $(".userCategorys .show-category .category-list").children().each(function() {
            let categoryId = $(this).attr("key");
            let userId = $("#userId").val();
            let isDept = $(this).attr("data-dept");
            if (isDept == "F") {
                if (categoryId) {
                    let data = {
                        categoryId,
                        userId,
                        "sort": i
                    }
                    postAPI(url, data).then(json=> {
                        return json;
                    });
                    i += 1;
                }
            }
        });
    },
    update: function(e, ui) {
        let url = `${baseUrl}onboardAdmin/updUserRouteSort`;
        let i = 1;
        $(".userCategorys .show-category .category-list").children().each(function() {
            let categoryId = $(this).attr("key");
            let userId = $("#userId").val();
            let isDept = $(this).attr("data-dept");
            if (isDept == "F") {
                if (categoryId) {
                    let data = {
                        categoryId,
                        userId,
                        "sort": i
                    }
                    postAPI(url, data).then(json=> {
                        return json;
                    });
                    i += 1;
                }
            }
        });
    },
    receive: function(e, ui) {
        let parent = ui.sender[0].parentElement.parentElement.className;
        //加入分類
        if (parent == "categorys") {
            let url = `${baseUrl}onboardAdmin/insUserRoute`;
            let categoryId = ui.item.attr("key");
            let userId = $("#userId").val();
            let data = {
                categoryId,
                userId,
                "sort": ""
            }
            postAPI(url, data).then(json=> {
                return json;
            });
        } else {   //移除分類
            let url = `${baseUrl}onboardAdmin/delUserRoute`;
            let categoryId = ui.item.attr("key");
            let userId = $("#userId").val();

            let data = {
                categoryId,
                userId
            }
            postAPI(url, data).then(json=> {
                return json;
            });
        }
    }
  }).disableSelection();

$(".select-department select").change(function() {
    let departmentId = $(".select-department select").val();
    
    if (departmentId) {
        let url = `${baseUrl}onboardAdmin/getUserRouteUserList`;
        let data = {
            departmentId
        }
        $(".select-user select").empty();
        $(".select-user select").append(`<option value="">請選擇員工(E-mail)</option>`);
        postAPI(url, data)
        .then(json => {
            json.forEach(user=> {
                $(".select-user select").append(`<option value="${user.userId}">${user.name}</option>`);
            })
            return json;
        })
    }
});

$(".select-user select").change(function() {
    let departmentId = $(".select-department select").val();
    let userId = $(".select-user select").val();
    if (!departmentId) {
        alert("請選擇部門");
    }
    if(departmentId && userId) {
        location.href = `${baseUrl}onboardAdmin/userRoute?departmentId=${departmentId}&userId=${userId}`;
    }
})

if($("#departmentId").val()) {
    $(".select-department select").children().each(function() {
        if ($(this).val() == $("#departmentId").val()){
            $(this).attr("selected", "true");
            let url = `${baseUrl}onboardAdmin/getUserRouteUserList`;
            let data = {
                departmentId: $("#departmentId").val()
            }

            $(".select-user select").empty();
            $(".select-user select").append(`<option value="">請選擇員工(E-mail)</option>`);
            postAPI(url, data)
            .then(json => {
                json.forEach(user=> {
                    $(".select-user select").append(`<option value="${user.userId}">${user.name}</option>`);
                })
                return json;
            }).then(json => {
                if($("#userId").val()) {
                    $(".select-user select").children().each(function() {
                        if ($(this).val() == $("#userId").val()){
                            $(this).attr("selected", "true");
                        }
                    });
                }
                return json;
            })
        }
    });
}
$(".category").each(function() {
    let id = $(this).attr("key");

    $(this).click(function() {
        let title = $(this).find(".show-category-title").text();
        let description = $(this).find(".show-category-description").text();
        $("#showCategoryName").empty();
        $(".tooltiptext").empty();
        $(".item-list").empty();
        $(".item-dialog").css("display", "block");
        
        let url = `${baseUrl}onboardAdmin/getUserRouteCategoryItems`;
        let data = {
            id
        };
        postAPI(url, data)
        .then(json => {
            $("#showCategoryName").html(title);
            $(".tooltiptext").html(description);
            json.forEach(item => {
                html = `<li key=${item.item_id} class="item">
                            <span class="show-item-title" ;>${item.item_title}</span>
                        <li>`;
                $(".item-list").append(html);
            })
        })
    });
});

$("#close-btn").click(function() {
    $(".item-dialog").css("display", "none");
})

$(".item-dialog").click(function(e) {
    if (e.target == $(".item-dialog")[0]) {
        $(".item-dialog").css("display", "none");
    }
})
