const path = require('path');

module.exports = {
    mode: "production",
    resolve: {
        extensions: ['.tsx', '.ts', '.js'],
    },
    entry: {
        'frontend': './assets/ts/src/frontend.ts',
        'admin': './assets/ts/src/admin.ts'
    },
    output: {
        path: path.resolve(__dirname, 'assets/js/dist')
    },
    module: {
        rules: [
            {
                test: /.tsx?$/,
                exclude: /node_modules/,
                loader: 'ts-loader'
            }
        ]
    }
};