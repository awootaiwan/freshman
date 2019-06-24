$("#tutorial-delete").click(function () {
    $(".delete-dialog").css("display", "block");
    $("#dialogText").html(`您確定要刪除教程`);
    $("#delType").val('delTutorial');
});

$('#btn-dialogConfirm').click(function () {
    var delType = $("#delType").val();
    if (delType == 'delTutorial') {
        manageTutorial('delete')
        .then(function (value) {
            if (value.result) {
                let url = `${baseUrl}manageLearn`;
                window.location.href = url;
            } else {
                alert("刪除出錯囉！！");
            }
        });
    }
})


$("#btn-dialogCancel").click(function () {
    $(".delete-dialog").css("display", "none");
});

async function manageTutorial(action, title = '') {
    let tutorial_id = $('.relation').data('id').toString();
    let data = { action, tutorial_id, title };
    let URL = `${baseUrl}manageLearn/manageTutorial`;

    return await postAPI(URL, data);
};

$('#tutorial-save').click(function () {
    let row = listData($('.relation li'));
    let title = $('.text-set').val();
    let tutorial_id = "";
    if (row.length != 0) {
        if ($('.relation').data('id') != undefined) {
            tutorial_id = $('.relation').data('id').toString();
            action = 'modify';
        }

        let data = { action, tutorial_id, title, row };
        let URL = `${baseUrl}manageLearn/setTutorialCourse`;

        postAPI(URL, data)
        .then(function (value) {
            if (value.result) {
                alert('儲存成功');
            }
        });
    } else {
        alert('請先新增課程');
    }

    manageTutorial('update', title)
    .then(function (value) {
        if (value.result) {
            tutorial_id = $('.relation').data('id').toString();
            $('#' + tutorial_id + "> span").text(title);
        } else {
            console.log('title不變／儲存出錯');
        }
    });
})

function listData(htmlData) {
    let checkLevel = [0];
    let row = [];
    for (let i = 0; i < htmlData.length; ++i) {
        let infoData = $(htmlData[i]);
        let len = infoData.parents('ul').length;
        if (len > checkLevel.length) {
            checkLevel.push(1);
        } else if (len == checkLevel.length) {
            checkLevel[len - 1] = parseInt(checkLevel[len - 1]) + 1;
        } else {
            let loop = checkLevel.length - len;
            for (let j = 0; j < loop; ++j) {
                checkLevel.pop();
            }
            checkLevel[len - 1] = parseInt(checkLevel[len - 1]) + 1;
        }
        let obj = { 'course_id': infoData.prop('id').toString(), 'level': checkLevel.join('-') };
        row.push(obj)
    }
    return row;
}

$('#search-courses').click(function () {
    let search = $('#course-search').val().trim();
    let cid = [];

    $('.relation li').each(function () {
        cid.push(parseInt($(this).prop('id')));
    })
    cid = cid.join(',');

    let data = { 
        cid, 
        search 
    };

    let URL = `${baseUrl}manageLearn/searchCourse`;

    postAPI(URL, data)
    .then(function (value) {
        let list = '';
        if (value.result !== false) {
            value.forEach(item => {
                list += `<li id = ${item['id']} class = 'mjs-nestedSortable-no-nesting'>${item['title']}</li>`;
            })
        }
        $('.courses ul').html(list)
    })
})

$('#btnSearch').click(function () {
    let search = $('#search').val().trim();
    let URL = `${baseUrl}manageLearn/searchTutorial`;
    let data = { search };
    postAPI(URL, data)
    .then(function (value) {
        let list = '';
        if (value !== null) {
            value.forEach(item => {
                list += `<li class = 'getTutorial' id = ${item['tid']}><span>${item['tutorial_title']}</span></li>`;
            })
        }
        $('.show-categorys ul').html(list)
    })
})

$(document).ready(function () {
    let scrollingSensitivity = 40, scrollingSpeed = 20;
    $('#sortable1').nestedSortable({
        forcePlaceholderSize: true,
        items: 'li',
        placeholder: 'item-selected',
        listType: 'ul',
        connectWith: "#sortable2",
        cursor: "grabbing",
        scrollSensitivity: scrollingSensitivity,
        scrollingSpeed: scrollingSpeed,
        sort: function (event, ui) {
            let scrollContainer = ui.placeholder[0].parentNode;
            let overflowOffset = $(scrollContainer).offset();
            if ((overflowOffset.top + scrollContainer.offsetHeight) - event.pageY < scrollingSensitivity) {
                scrollContainer.scrollTop = scrollContainer.scrollTop + scrollingSpeed;
            }
            else if (event.pageY - overflowOffset.top < scrollingSensitivity) {
                scrollContainer.scrollTop = scrollContainer.scrollTop - scrollingSpeed;
            }
            if ((overflowOffset.left + scrollContainer.offsetWidth) - event.pageX < scrollingSensitivity) {
                scrollContainer.scrollLeft = scrollContainer.scrollLeft + scrollingSpeed;
            }
            else if (event.pageX - overflowOffset.left < scrollingSensitivity) {
                scrollContainer.scrollLeft = scrollContainer.scrollLeft - scrollingSpeed;
            }
        },
        remove: function (event, ui) {
            ui.item.removeClass('mjs-nestedSortable-no-nesting');
        },
        activate: function (event, ui) {
            ui.item.css('background-color', "#f8f8f8");
        },
        stop: function (event, ui) {
            ui.item.css('background-color', "#ffffff");
        }
    });
    $('#sortable2').nestedSortable({
        forcePlaceholderSize: true,
        items: 'li',
        placeholder: 'item-selected',
        listType: 'ul',
        connectWith: ".connectSortable",
        cursor: "grabbing",
        scrollSensitivity: scrollingSensitivity,
        scrollingSpeed: scrollingSpeed,
        sort: function (event, ui) {
            let scrollContainer = ui.placeholder[0].parentNode;
            let overflowOffset = $(scrollContainer).offset();
            if ((overflowOffset.top + scrollContainer.offsetHeight) - event.pageY < scrollingSensitivity) {
                scrollContainer.scrollTop = scrollContainer.scrollTop + scrollingSpeed;
            }
            else if (event.pageY - overflowOffset.top < scrollingSensitivity) {
                scrollContainer.scrollTop = scrollContainer.scrollTop - scrollingSpeed;
            }
            if ((overflowOffset.left + scrollContainer.offsetWidth) - event.pageX < scrollingSensitivity) {
                scrollContainer.scrollLeft = scrollContainer.scrollLeft + scrollingSpeed;
            }
            else if (event.pageX - overflowOffset.left < scrollingSensitivity) {
                scrollContainer.scrollLeft = scrollContainer.scrollLeft - scrollingSpeed;
            }
        },
            remove: function(event, ui) {
                let tags = ui.item.find('li');
                tags.each(function () {
                    $(this).css('background-color', "#ffffff");
                    $(this).children().remove('ul');
                    $(this).addClass('mjs-nestedSortable-no-nesting');
                })
                ui.item.children().remove('ul');
                ui.item.addClass('mjs-nestedSortable-no-nesting');
                ui.item.after(tags);
            },
            activate: function (event, ui) {
                ui.item.find('li').each(function () {
                    $(this).css('background-color', "#f8f8f8");
                })
                ui.item.css('background-color', "#f8f8f8");
            },
            stop: function (event, ui) {
                ui.item.find('li').each(function () {
                    $(this).css('background-color', "#ffffff");
                })
                ui.item.css('background-color', "#ffffff");
            }
        });
});

$('.search').keypress(function (e) {
    var key = window.event ? e.keyCode : e.which;
    if (key == 13) {
        $('search-courses').click();
    }
});

$(document).ready(function () {
    $('#course-search').focus();
});

$('#course-search').keypress(function (e) {
    if (e.which == 13) {
        $('#search-courses').focus().click();
    }
});