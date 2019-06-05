const path = require('path');
module.exports = {
	mode: 'development',
	entry: './index.js',
	node: {
		fs: "empty"
		},
	output: {
		filename: 'markdown.js',
		path: path.resolve(__dirname, '../js'),
	}
};
