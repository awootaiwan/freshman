const last_course = $("#last_course").val();
const next_course = $("#next_course").val();
$(document).ready(function () {
     (last_course != '') ? $(".last_course").show() : "";
     (next_course != '') ? $(".next_course").show() : "";
     let tid = $("#tutorial_id").val();
     $("#t" + tid).prop('open', 'open');
     
     let url = `${baseUrl}manageLearn/setCourse`;
     let cid = $("#course_id").val();
     let data = {cid};

     postAPI(url, data)
     .then(json => {
          $("#ori_content").val(json.content);
          $("#ori_content").trigger("init");
          checkCidToOpen();
          return json;
     })
});

$(document).keydown(function (e) {
     key = window.event ? e.keyCode : webkitCancelAnimationFrame.which
     if (key == 37) { 
          if($(".last_course").css('display') == 'block') {
               if ($(".send-message-content").css("display") != "table") {
                    $(".last_course").click();
               }
          }
     }
     else if (key == 39) { 
          if($(".next_course").css('display') == 'block') {
               if ($(".send-message-content").css("display") != "table") {
                    $(".next_course").click();
               }
          }
     }
});

$(".show-categorys").on('click', '.showContent', function () {
     let cid = $(this).parent('li').attr('id').substr(1)
     let tid = $(this).parents('details').attr('id').substr(1);
     location.replace(baseUrl + "showLearn?" + 'cid=' + cid + "&tid=" + tid)
})

$(".last_course").click(function () {
     let cid = $("#last_course").val();
     let tid = $("#tutorial_id").val();
     location.replace(baseUrl + "showLearn?" + 'cid=' + cid + "&tid=" + tid)
})

$(".next_course").click(function () {
     let cid = $("#next_course").val();
     let tid = $("#tutorial_id").val();
     location.replace(baseUrl + "showLearn?" + 'cid=' + cid + "&tid=" + tid)

})

function checkCidToOpen() {
     if ($("#course_id").val() != '') {
          tutorial_id = $("#tutorial_id").val();
          $("#t" + tutorial_id).find('li').each(function () {
               if ($(this).attr('id').substr(1) == $("#course_id").val()) {
                    $(this).find("div").first().css("color", "#77B6FF");
               }
          })
     }
}

$('#btnSearch').click(function () {
     let search = $('#search').val().trim();
     let URL = `${baseUrl}showLearn/searchTutorial`;
     let data = { search };
     postAPI(URL, data)
     .then(function (value) {
          let list = '';
          let checkopen = '';
          if (value !== null) {
               if (search != '') {
                    checkopen = 'open';
               }
               value.forEach(item => {
                    list += '<div class = learn-list-top>'
                    list += `<details id = t${item['tid']} ${checkopen}>
                                   <summary id = tutorial${item['tid']}>${item['tutorial_title']}</summary>
                              <ul>`;
                    item['course'].forEach(course => {
                         list += `<li id = c${course['id']} > <div class = showContent>${course['title']}</div>`;
                         if (course['compare'] > -1) {
                              for (let i = 0; i < course['compare']; i += 1)
                                   list += '</ul>';
                         } else {
                              list += '<ul>';
                         }
                         list += '</li>';
                    })
                    list += '</ul></details>';
                    list += '</div>';
               })
          }
          $('.show-categorys').html(list);
          let tid = $("#tutorial_id").val();
          $("#t" + tid).prop('open', 'open');
          checkCidToOpen();
     })
})


$(window).resize(function () {
     if ($(window).width() > 768) {
          $(".show-learn-left").css("left", "0");
     }

     if ($(window).width() <= 768) {
          $(".show-learn-left").css("left", "-250px");
          $(".rwd-menubtn").css("left", "0");
     }
});

$(".rwd-menubtn").click(function () {
     if ($(".show-learn-left").css("left") == "-250px") {
          $(".show-learn-left").animate({ left: '0' });
          $(".rwd-menubtn").animate({ left: '250px' });
          $(".rwd-menubtn i").css("transform", "rotate(180deg)");
     } else {
          $(".show-learn-left").animate({ left: '-250px' });
          $(".rwd-menubtn").animate({ left: '0' });
          $(".rwd-menubtn i").css("transform", "none");
     }
});