import _ from 'lodash';
import { editor } from "./editor.js";
import { syncResultScroll, syncSrcScroll, mdToHtml } from "./asyncScroll.js";

if (editor != null) {
	editor.on("change", _.debounce(mdToHtml, 500, { maxWait: 500 }));
	$(".CodeMirror").on("clear", function () {
		editor.setValue("");
		editor.refresh();
		editor.focus();
	});

	$("#ori_content").on("init", function() {
		if ($("#ori_content").val()) {
			editor.setValue($("#ori_content").val());
			mdToHtml();
		}
	});
}




$('.CodeMirror').on('touchstart mouseover', function () {
	$('.mark-show').off('scroll');
	$(".CodeMirror-scroll").on("scroll", _.throttle(syncResultScroll, 5));
});

$('.mark-show').on('touchstart mouseover', function () {
	$(".CodeMirror-scroll").off('scroll');
	$(".mark-show").on("scroll", _.throttle(syncSrcScroll, 5));
});

$('.mark-show').on("click", ".send-message-button", function() {
	$("#messageType").val(1);
	$('.messageContent').val(($(this).data("content")));
	$('.messageContent').focus();
	$(".show-send-message-btn").click();
});
