import _ from 'lodash';
import { editor } from "./editor";
import { md } from "./markdown-it.js";

export function mdToHtml() {
	editor.refresh();
	let text = editor.getValue();
	$("#text").text(text);
	editor.save();
	scrollMap = null;
	$("#test").html(md.render(text));
}

var scrollMap = null;
var syncResultScroll = _.debounce(function () {
	var textarea = $('.CodeMirror-scroll'),
		lineHeight = parseFloat($(".CodeMirror-lines").css('line-height')),
		lineNo, posTo;
	const scrollInfo = editor.getScrollInfo();
	const textHeight = editor.defaultTextHeight()
	lineNo = Math.floor(scrollInfo.top / textHeight);
	if (!scrollMap) { scrollMap = buildScrollMap() }
	posTo = scrollMap[lineNo];
	$('.mark-show').stop(true).animate({
		scrollTop: posTo
	}, 100, 'linear');
}, 50, { maxWait: 50 });

var syncSrcScroll = _.debounce(function () {
	if (!scrollMap) { scrollMap = buildScrollMap() }
	let editArea = $(".CodeMirror-scroll");
	const scrollTop = $(".mark-show")[0].scrollTop
	let lineIndex = 0
	for (let i = 0, l = scrollMap.length; i < l; i++) {
		if (scrollMap[i] > scrollTop) {
			break
		} else {
			lineIndex = i
		}
	}
	let lineNo = 0
	let lineDiff = 0
	for (let i = 0, l = lineHeightMap.length; i < l; i++) {
		if (lineHeightMap[i] > lineIndex) {
			break
		} else {
			lineNo = lineHeightMap[i]
			lineDiff = lineHeightMap[i + 1] - lineNo
		}
	}

	let posTo = 0
	let topDiffPercent = 0
	let posToNextDiff = 0
	const scrollInfo = editor.getScrollInfo()
	const textHeight = editor.defaultTextHeight()
	const preLastLineHeight = scrollInfo.height - scrollInfo.clientHeight - textHeight
	const preLastLineNo = Math.round(preLastLineHeight / textHeight)
	const preLastLinePos = scrollMap[preLastLineNo]
	let viewBottom = $(".mark-show")[0].scrollHeight - $(".mark-show").height();
	if (scrollInfo.height > scrollInfo.clientHeight && scrollTop >= preLastLinePos) {
		posTo = preLastLineHeight
		topDiffPercent = (scrollTop - preLastLinePos) / (viewBottom - preLastLinePos)
		posToNextDiff = textHeight * topDiffPercent
		posTo += Math.ceil(posToNextDiff)
	} else {
		posTo = lineNo * textHeight
		topDiffPercent = (scrollTop - scrollMap[lineNo]) / (scrollMap[lineNo + lineDiff] - scrollMap[lineNo])
		posToNextDiff = textHeight * lineDiff * topDiffPercent
		posTo += Math.ceil(posToNextDiff)
	}

	const posDiff = Math.abs(scrollInfo.top - posTo)
	var duration = posDiff / 50
	duration = duration >= 100 ? duration : 100
	editArea.stop(true, true).animate({
		scrollTop: posTo
	}, duration, 'linear')

}, 50, { maxWait: 50 });

var lineHeightMap = [];
function buildScrollMap() {
	var i, offset, nonEmptyList, pos, a, b, _lineHeightMap, linesCount,
		acc, sourceLikeDiv, textarea = $(".CodeMirror-scroll"),
		_scrollMap;
	const textHeight = editor.defaultTextHeight()
	sourceLikeDiv = $('<div />').css({
		position: 'absolute',
		visibility: 'hidden',
		height: 'auto',
		width: textarea[0].clientWidth,
		'font-size': $(".cm-s-one-dark").css('font-size'),
		'font-family': $(".cm-s-one-dark").css('font-family'),
		'line-height': textHeight,
		'white-space': textarea.css('white-space')
	}).appendTo('body');

	offset = $('.mark-show').scrollTop() - $('.mark-show').offset().top;
	_scrollMap = [];
	nonEmptyList = [];
	_lineHeightMap = [];

	acc = 0;
	editor.getValue().split('\n').forEach(function (str) {
		var h, lh;

		_lineHeightMap.push(acc);

		if (str.length === 0) {
			acc++;
			return;
		}

		sourceLikeDiv.text(str);
		h = parseFloat(sourceLikeDiv.css('height'));
		lh = parseFloat(sourceLikeDiv.css('line-height'));
		acc += Math.round(h / lh);
	});
	sourceLikeDiv.remove();
	_lineHeightMap.push(acc);
	linesCount = acc;

	for (i = 0; i < linesCount; i++) { _scrollMap.push(-1); }

	nonEmptyList.push(0);
	_scrollMap[0] = 0;

	$('.line').each(function (n, el) {
		var $el = $(el), t = $el.attr('data-source-line') - 1;
		if (t === '') { return; }
		t = _lineHeightMap[t];
		if (t !== 0 && t !== nonEmptyList[nonEmptyList.length - 1]) { nonEmptyList.push(t); }
		_scrollMap[t] = Math.round($el.offset().top + offset);
	});

	nonEmptyList.push(linesCount);
	_scrollMap[linesCount] = $('.mark-show')[0].scrollHeight;

	pos = 0;
	for (i = 1; i < linesCount; i++) {
		if (_scrollMap[i] !== -1) {
			pos++;
			continue;
		}

		a = nonEmptyList[pos];
		b = nonEmptyList[pos + 1];
		_scrollMap[i] = Math.round((_scrollMap[b] * (i - a) + _scrollMap[a] * (b - i)) / (b - a));
	}
	lineHeightMap = _lineHeightMap;
	return _scrollMap;
}

export  {
    syncResultScroll,
    syncSrcScroll,
    scrollMap
}