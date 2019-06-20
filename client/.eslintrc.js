// TODO: actualizar eslint-plugin-import cuando lancen una nueva versión
const jsExtensions = ['.js', '.jsx'];
const tsExtensions = ['.ts', '.tsx'];
const allExtensions = jsExtensions.concat(tsExtensions);

module.exports = {
  root: true,
  env: {
    node: true
  },
  extends: [
    'plugin:vue/recommended',
    '@vue/airbnb',
    '@vue/typescript',
    'plugin:import/errors',
    'plugin:import/warnings',
    'plugin:prettier/recommended'
  ],
  plugins: ['import'],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-use-before-define': 'off',

    // Vue.js
    'vue/max-attributes-per-line': 'off',
    'vue/html-self-closing': 'off',

    // Import
    'import/named': 'off',
    'import/no-unresolved': 'off',
    'import/extensions': 'off'
  },
  parserOptions: {
    parser: '@typescript-eslint/parser'
  },
  settings: {
    'import/extensions': allExtensions,
    'import/parsers': {
      '@typescript-eslint/parser': tsExtensions
    },
    'import/resolver': {
      node: {
        extensions: allExtensions
      }
    }
  }
};
