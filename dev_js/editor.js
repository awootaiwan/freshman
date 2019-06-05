import jquery from 'jquery';
import 'jquery-textcomplete';
var editor = null;
if ($("#text").length > 0) {
    var onlyshow = ($("#text").attr('key') == 'show') ? true : false;
    var editor = CodeMirror.fromTextArea($("#text")[0], {
        readOnly: onlyshow,
        placeholder: `⬆︎⬆︎請在上方欄位輸入標題名稱\n查看markdown教學請按上面？按鈕`,
        autofocus: false,
        keyMap: 'sublime',
        viewportMargin: 20,
        styleActiveLine: true,
        lineWrapping: true,
        matchBrackets: true,
        mode: "gfm",
        theme: "one-dark",
        lineNumbers: true,
        foldGutter: true,
        gutters: ['CodeMirror-linenumbers',
            'authorship-gutters',
            'CodeMirror-foldgutter'],
        indentUnit: 4,
        showCursorWhenSelecting: true,
        inputStyle: 'textarea',
        highlightSelectionMatches: true,
        matchTags: {
            bothTags: true
        },
        autoCloseBrackets: true,
        autoCloseTags: true,
        flattenSpans: true,
        addModeClass: true,
        autoRefresh: true,
        otherCursors: true
    });
    editor.setOption("extraKeys", {
        'Cmd-S': function () {
            return false
        },
        'Ctrl-S': function () {
            return false
        },
        F10: function (cm) {
            cm.setOption('fullScreen', !cm.getOption('fullScreen'))
        },
        Esc: function (cm) {
            if (cm.getOption('fullScreen') && !(cm.getOption('keyMap').substr(0, 3) === 'vim')) {
                cm.setOption('fullScreen', false)
            } else {
                return CodeMirror.Pass
            }
        },
        Enter: 'newlineAndIndentContinueMarkdownList',
        "Shift-Tab": "autoUnindentMarkdownList",
        Tab: function (cm) {
            var tab = '\t'

            // contruct x length spaces
            var spaces = Array(parseInt(cm.getOption('indentUnit')) + 1).join(' ')

            // auto indent whole line when in list or blockquote
            var cursor = cm.getCursor()
            var line = cm.getLine(cursor.line)

            // this regex match the following patterns
            // 1. blockquote starts with "> " or ">>"
            // 2. unorder list starts with *+-
            // 3. order list starts with "1." or "1)"
            var regex = /^(\s*)(>[> ]*|[*+-]\s|(\d+)([.)]))/

            var match
            var multiple = cm.getSelection().split('\n').length > 1 ||
                cm.getSelections().length > 1

            if (multiple) {
                cm.execCommand('defaultTab')
            } else if ((match = regex.exec(line)) !== null) {
                var ch = match[1].length
                var pos = {
                    line: cursor.line,
                    ch: ch
                }
                if (cm.getOption('indentWithTabs')) {
                    cm.replaceRange(tab, pos, pos, '+input')
                } else {
                    cm.replaceRange(spaces, pos, pos, '+input')
                }
            } else {
                if (cm.getOption('indentWithTabs')) {
                    cm.execCommand('defaultTab')
                } else {
                    cm.replaceSelection(spaces)
                }
            }
        },
    })
}
var isInCode = false

var supportHeaders = [
    {
        text: '# h1',
        search: '#'
    },
    {
        text: '## h2',
        search: '##'
    },
    {
        text: '### h3',
        search: '###'
    },
    {
        text: '#### h4',
        search: '####'
    },
    {
        text: '##### h5',
        search: '#####'
    },
    {
        text: '###### h6',
        search: '######'
    },
    {
        text: '###### tags: `example`',
        search: '###### tags:'
    }
]
const videoReferrals = [
    {
        text: '@[youtube](youtube url)',
        search: '@[]'
    },
    {
        text: '@[youtube](youtube id)',
        search: '@[]'
    },
    {
        text: '@[vimeo](vimeo url)',
        search: '@[]'
    }, {
        text: '@[vimeo](vimeo id)',
        search: '@[]'
    },
    {
        text: '@[pdf](pdf url)',
        search: '@[]'
    }
]
const supportReferrals = [
    {
        text: '[link text](https:// "title")',
        search: '![]()'
    },
    {
        text: '![image alt](https:// "title")',
        search: '![]()'
    },
    {
        text: '![image alt](https:// "title" =WidthxHeight)',
        search: '![]()'
    },
    {
        text: '[[TOC]]',
        search: '![]'
    }
]
if (editor != undefined) {
    jquery(editor.getInputField())
        .textcomplete([
            { // header
                match: /(?:^|\n)(\s{0,3})(#{1,6}\w*)$/,
                search: function (term, callback) {
                    callback($.map(supportHeaders, function (header) {
                        return header.search.indexOf(term) === 0 ? header.text : null
                    }))
                },
                replace: function (value) {
                    return '$1' + value
                },
                context: function (text) {
                    return !isInCode
                }
            },
            { // referral
                match: /(^\s*|\n|\s{2})((\[\]|\[\]\[\]|\[\]\(\)|!|!\[\]|!\[\]\[\]|!\[\]\(\))\s*\w*)$/,
                search: function (term, callback) {
                    callback($.map(supportReferrals, function (referral) {
                        return referral.search.indexOf(term) === 0 ? referral.text : null
                    }))
                },
                replace: function (value) {
                    return '$1' + value
                },
                context: function (text) {
                    return !isInCode
                }
            },
            { // video
                match: /(^\s*|\n|\s{2})((\[\]|\[\]\[\]|\[\]\(\)|@|@\[\]|@\[\]\[\]|@\[\]\(\))\s*\w*)$/,
                search: function (term, callback) {
                    callback($.map(videoReferrals, function (referral) {
                        return referral.search.indexOf(term) === 0 ? referral.text : null
                    }))
                },
                replace: function (value) {
                    return '$1' + value
                },
                context: function (text) {
                    return !isInCode
                }
            }
        ])
        .on({
            'textComplete:beforeSearch': function (e) {
                // NA
            },
            'textComplete:afterSearch': function (e) {
            },
            'textComplete:select': function (e, value, strategy) {
                // NA
            },
            'textComplete:show': function (e) {
                $(this).data('autocompleting', true)
                editor.setOption('extraKeys', {
                    'Up': function () {
                        return false
                    },
                    'Right': function () {
                        editor.doc.cm.execCommand('goCharRight')
                    },
                    'Down': function () {
                        return false
                    },
                    'Left': function () {
                        editor.doc.cm.execCommand('goCharLeft')
                    },
                    'Enter': function () {
                        return false
                    },
                    'Backspace': function () {
                        editor.doc.cm.execCommand('delCharBefore')
                    }
                })
            },
            'textComplete:hide': function (e) {
                $(this).data('autocompleting', false)
                editor.setOption('extraKeys', editor.defaultExtraKeys)
            }
        })

}


export {
    editor
}