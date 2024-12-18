/** @type {import('tailwindcss').Config} */
export default {
	darkMode: 'selector',
	content: [
		"./resources/**/*.blade.php",
		"./resources/**/*.js",
	],
	theme: {
		extend: {
			boxShadow: {
				'DEFAULT': '0 0 3px 0 rgb(0 0 0 / 0.1), 0 0 2px -1px rgb(0 0 0 / 0.1)',
			},
			colors: {
				'slate': {
					750: '#283547',
				}
			}
		},
	},
	plugins: [],
}

