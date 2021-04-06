module.exports = {
  'env': {
    'browser': true,
    'es2020': true,
    'jest': true
  },
  'parser': 'babel-eslint',
  'extends': [
    'eslint:recommended',
    // 'google',
    // 'airbnb',
    'plugin:react/recommended',
    'prettier'
  ],
  'parserOptions': {
    'ecmaFeatures': {
      'jsx': true
    },
    'sourceType': 'module'
  },
  'settings': {
    'react': {
      'version': 'detect',
    },
  },
  'globals': {
    'global': true,
    'USER': true,
    'BACKOFFICE_API_URL': true,
    'APP_VERSION': true
  }
}
