module.exports = {
  testRegex: 'tests/JavaScript/.*.spec.js$',
  coveragePathIgnorePatterns: ['/node_modules/', 'tests'],
  transformIgnorePatterns: ['/node_modules/(?!nonono-validator)'],
  verbose: true,
  silent: false,
  moduleFileExtensions: [
    'js',
    'd.ts',
    'json',
    'vue',
  ],
  moduleDirectories: [
    'node_modules'
  ],
  transform: {
    '^.+\.js$': '<rootDir>/node_modules/babel-jest',
    '.*\.(vue)$': '<rootDir>/node_modules/vue-jest',
  },
  setupFilesAfterEnv: ["jest-expect-message"],
}
