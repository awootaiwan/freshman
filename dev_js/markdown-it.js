
var emoji = require('markdown-it-emoji');
var alerts = require('markdown-it-alerts');
var kbd = require('markdown-it-kbd');
var Plugin = require('markdown-it-regexp');
import markdownItHighlight from 'markdown-it-highlight';
if ($("#test").length > 0) {
	const messagePlugin = new Plugin(
		// regexp to match
		/\[\s*([\d\D]*?)\s*\]{%message\s*([\d\D]*?)\s*%}/,

		(match, utils) => {
			let title = match[1];
			const content = match[2];
			if (match[1] == "") {
				 title = "點我傳送訊息";
			}
			const div = $(`<div class="send-message-button">${title}</div>`)
			div.attr("data-content", content);
			return div[0].outerHTML
		}
	)

	var md = require('markdown-it')({
		html: false,
		linkify: true,
		typographer: true,
		langPrefix: 'language-',
		breaks: true,
	});

	md.use(messagePlugin);
	md.use(emoji, { shortcuts: {} })
	md.use(alerts)
	md.use(require('markdown-it-mark'));
	md.use(require('markdown-it-ins'));
	md.use(require('markdown-it-decorate'));
	md.use(kbd);
	md.use(markdownItHighlight);
	md.use(require('markdown-it-br'));
	md.use(require('markdown-it-sub'));
	md.use(require("markdown-it-sup"));
	md.use(require('markdown-it-abbr'));
	md.use(require("markdown-it-underline"));
	md.use(require('markdown-it-deflist'));
	md.use(require("markdown-it-imsize"), { autofill: true });
	md.use(require('markdown-it-task-checkbox'), {
		disabled: false,
		divWrap: false,
		divClass: 'checkbox',
		idPrefix: 'cbx_',
		ulClass: 'task-list',
		liClass: 'task-list-item'
	});
	md.use(require('markdown-it-anchor'), {
		level: 1,
		permalink: false,
		permalinkClass: 'header-anchor',
		permalinkSymbol: '¶',
		permalinkBefore: false
	})
	md.use(require("markdown-it-table-of-contents"), {
		includeLevel: [1, 2, 3]
	});
	md.use(require("markdown-it-pdf"), {
		showUrl: false,
	});
	md.use(require('markdown-it-iframe'), {
		allowfullscreen: true,
		width: 600,
		height: 600,
		frameborder: 0, // default: 0
		renderIframe: true // default: true
	});
	md.use(require('markdown-it-container'), 'spoiler', {

		validate: function (params) {
			return params.trim().match(/^spoiler\s+(.*)$/);
		},

		render: function (tokens, idx) {
			var m = tokens[idx].info.trim().match(/^spoiler\s+(.*)$/);

			if (tokens[idx].nesting === 1) {
				// opening tag
				return '<details><summary>' + md.utils.escapeHtml(m[1]) + '</summary>\n';

			} else {
				// closing tag
				return '</details>\n';
			}
		}
	});
	md.use(require("markdown-it-block-embed"), {
		containerClassName: "video-embed"
	});

	md.renderer.rules.blockquote_open = function (tokens, idx, options, env, self) {
		tokens[idx].attrJoin('class', 'raw')
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}
	md.renderer.rules.table_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}
	md.renderer.rules.bullet_list_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}
	md.renderer.rules.list_item_open = function (tokens, idx, options, env, self) {
		tokens[idx].attrJoin('class', 'raw')
		if (tokens[idx].map) {
			const startline = tokens[idx].map[0] + 1
			const endline = tokens[idx].map[1]
			tokens[idx].attrJoin('data-source-line', startline)
			tokens[idx].attrJoin('data-endline', endline)
		}
		return self.renderToken(...arguments)
	}
	md.renderer.rules.ordered_list_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}
	md.renderer.rules.link_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}
	md.renderer.rules.paragraph_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}

	md.renderer.rules.heading_open = function (tokens, idx, options, env, self) {
		addPart(tokens, idx)
		return self.renderToken(...arguments)
	}

	function addPart(tokens, idx) {
		if (tokens[idx].map && tokens[idx].level === 0) {
			const startline = tokens[idx].map[0] + 1
			const endline = tokens[idx].map[1]
			tokens[idx].attrJoin('class', 'line')
			tokens[idx].attrJoin('data-source-line', startline)
			tokens[idx].attrJoin('data-endline', endline)
		}
	}

	md.use(require('markdown-it-link-attributes'), {
		attrs: {
			target: '_blank',
			rel: 'noopener'
		}
	});
}






export {
	md
}