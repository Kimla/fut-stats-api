module.exports = {
    root: true,
    env: {
        node: true
    },
    extends: [
        'plugin:vue/recommended',
        '@vue/standard'
    ],
    parserOptions: {
        parser: 'babel-eslint'
    },
    plugins: [
        'vue'
    ],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        indent: ['error', 4],
        'import/prefer-default-export': 'off',
        'vue/no-v-html': 'off',
        'vue/html-indent': ['error', 4],
        'vue/html-self-closing': [
            'error',
            {
                html: {
                    normal: 'any'
                }
            }
        ],
        'vue/max-attributes-per-line': [
            'error',
            {
                singleline: 1,
                multiline: {
                    max: 1,
                    allowFirstLine: false
                }
            }
        ],
        'import/no-extraneous-dependencies': [
            'error',
            { devDependencies: true, optionalDependencies: true, peerDependencies: true }
        ],
        'no-plusplus': 'off',
        'linebreak-style': 0,
        semi: [2, 'always']
    },
    overrides: [
        {
            files: [
                '**/__tests__/*.{j,t}s?(x)',
                '**/tests/unit/**/*.spec.{j,t}s?(x)'
            ],
            env: {
                jest: true
            }
        }
    ]
};
