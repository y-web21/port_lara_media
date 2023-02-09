/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.js',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Nunito', ...defaultTheme.fontFamily.sans],
      },
      width: {
        '1/7': '14.2857143%',
        '2/7': '28.5714286%',
        '3/7': '42.8571429%',
        '4/7': '57.1428571%',
        '5/7': '71.4285714%',
        '6/7': '85.7142857%',
        '6/7': '85.7142857%',
        '1/15': '6.666666666666667%',
        '2/15': '13.333333333333334%',
        '3/15': '20%',
        '4/15': '26.666666666666668%',
        '5/15': '33.33333333333333%',
        '6/15': '40%',
        '7/15': '46.666666666666664%',
        '8/15': '53.333333333333336%',
        '9/15': '60%',
        '10/15': '66.66666666666666%',
        '11/15': '73.33333333333333%',
        '12/15': '80%',
        '13/15': '86.66666666666667%',
        '14/15': '93.33333333333333%',
        '1/3': '33.33333333333333%',
        '2/3': '66.66666666666666%',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/line-clamp'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/typography')
  ],
};
