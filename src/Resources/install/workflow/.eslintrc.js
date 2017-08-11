module.exports = {
  'root': true,
  'extends': ['eslint:recommended', 'airbnb-base'],
  'env': {
    'browser': true,
    'es6': true,
    'jquery': true,
  },
  'globals': { 'UIkit': true },
  'parser': 'babel-eslint',
  'rules': {
    'no-extra-semi': 'error',
    'no-template-curly-in-string': 'error',
    'no-caller': 'error',
    'global-require': 'off',
    'no-extra-bind': 'warn',
    'no-empty': ['error', { 'allowEmptyCatch': true }],
    'no-process-exit': 'warn',
  }
};
