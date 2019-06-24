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
        let url = `${baseUrl}deptCategoryManage/updDeptRouteSort`;
        let i = 1;
        $(".userCategorys .show-category .category-list").children().each(function() {
            let categoryId = $(this).attr("key");
            let departmentId = $("#departmentId").val();
            if (categoryId) {
                let data = {
                    categoryId,
                    departmentId,
                    "sort": i
                }
                postAPI(url, data)
                .then(json=> {
                    return json;
                });
                i += 1;
            }
        });
    },
    update: function(e, ui) {
        let url = `${baseUrl}deptCategoryManage/updDeptRouteSort`;
        let i = 1;
        $(".userCategorys .show-category .category-list").children().each(function() {
            let categoryId = $(this).attr("key");
            let departmentId = $("#departmentId").val();
            if (categoryId) {
                let data = {
                    categoryId,
                    departmentId,
                    "sort": i
                }
                postAPI(url, data)
                .then(json=> {
                    return json;
                });
                i += 1;
            }
        });
    },
    receive: function(e, ui) {
        let parent = ui.sender[0].parentElement.parentElement.className;
        if (parent == "categorys") {
            let url = `${baseUrl}deptCategoryManage/insDeptRoute`;
            let categoryId = ui.item.attr("key");
            let departmentId = $("#departmentId").val();
            let data = {
                categoryId,
                departmentId,
                "sort": ""
            }
            postAPI(url, data).then(json=> {
                return json;
            });
        } else {
            let url = `${baseUrl}deptCategoryManage/delDeptRoute`;
            let categoryId = ui.item.attr("key");
            let departmentId = $("#departmentId").val();

            let data = {
                categoryId,
                departmentId
            }
            postAPI(url, data)
            .then(json=> {
                return json;
            });
        }

    }
}).disableSelection();

$(".select-department select").change(function() {
    let departmentId = $(".select-department select").val();
    
    if (departmentId) {
        location.href = `${baseUrl}deptCategoryManage/deptRoute?departmentId=${departmentId}`;
    }
});

if($("#departmentId").val()) {
    $(".select-department select").children().each(function() {
        if ($(this).val() == $("#departmentId").val()){
            $(this).attr("selected", "true");
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
