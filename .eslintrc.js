module.exports = {
  parser: 'babel-eslint',
  parserOptions: {
    'ecmaVersion': 2020,
    'sourceType': 'module'
  },
  extends: [
    'eslint:recommended',
    'plugin:prettier/recommended'
  ],
  env: {
    'browser': true,
    'node': true,
    'es6': true,
  },
  globals: {
    'barba': false,
  },
  overrides: [
    {
      files: ['src/**/*.ts'],
      parser: '@typescript-eslint/parser',
      parserOptions: {
        project: './tsconfig.json'
      },
      plugins: [
        '@typescript-eslint',
        'prettier'
      ],
      extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/eslint-recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:@typescript-eslint/recommended-requiring-type-checking',
        'prettier/@typescript-eslint',
        'plugin:prettier/recommended',
      ],
      env: {},
      rules: {},
    }
  ],
}
