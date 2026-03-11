import js from '@eslint/js'
import pluginVue from 'eslint-plugin-vue'
import prettier from 'eslint-config-prettier'

export default [
  js.configs.recommended,
  ...pluginVue.configs['flat/recommended'],
  prettier,
  {
    files: ['resources/js/**/*.{js,vue}'],
    rules: {
      'vue/multi-word-component-names': 'off',
      'vue/no-unused-vars': 'warn',
      'vue/no-reserved-component-names': 'off',
      'vue/no-unused-components': 'warn',
      'no-unused-vars': 'warn',
      'no-undef': 'off',
    },
  },
  {
    ignores: ['node_modules/', 'public/', 'vendor/', 'storage/'],
  },
]
