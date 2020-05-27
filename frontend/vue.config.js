module.exports = {
    devServer: {
        proxy: 'http://127.0.0.1:8000',
    },

    outputDir: '../public',

    indexPath: process.env.NODE_ENV === 'production' ? '../public/index.html' : 'index.html',

    chainWebpack: (config) => {
        const svgRule = config.module.rule('svg');

        svgRule.uses.clear();

        svgRule
            .use('babel-loader')
            .loader('babel-loader')
            .end()
            .use('vue-svg-loader')
            .loader('vue-svg-loader')
            .options({
                svgo: false
            });
    },

    pwa: {
        name: 'FutStats',
        themeColor: '#667eea',
        msTileColor: '#667eea',
        appleMobileWebAppCapable: 'yes',
        appleMobileWebAppStatusBarStyle: '#667eea',
        workboxPluginMode: 'GenerateSW',
        workboxOptions: {
            exclude: /api\.php$/
        }
    }
};
