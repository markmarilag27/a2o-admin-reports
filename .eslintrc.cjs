module.exports = {
  root: true,
  env: {
    browser: true,
    es2022: true,
    node: true,
  },
  parser: 'vue-eslint-parser',
  parserOptions: {
    parser: '@typescript-eslint/parser',
    ecmaVersion: 'latest',
    sourceType: 'module',
    extraFileExtensions: ['.vue'],
  },
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-strongly-recommended',
    '@vue/eslint-config-typescript/recommended',
    '@vue/eslint-config-prettier',
  ],
  plugins: ['vue', '@typescript-eslint'],
  rules: {
    'vue/component-tags-order': [
      'error',
      {
        order: ['script', 'template', 'style'],
      },
    ],
    'vue/component-name-in-template-casing': ['error', 'PascalCase'],
    'vue/no-useless-v-bind': 'error',
    'vue/no-empty-component-block': 'error',
    'vue/no-duplicate-attr-inheritance': 'error',
    'vue/require-name-property': 'error',
    'vue/no-multiple-template-root': 'off',

    '@typescript-eslint/no-unused-vars': ['error', { argsIgnorePattern: '^_' }],
    '@typescript-eslint/explicit-module-boundary-types': 'error',
    '@typescript-eslint/ban-ts-comment': 'warn',

    'no-console': 'warn',
    'no-debugger': 'error',
  },
};
