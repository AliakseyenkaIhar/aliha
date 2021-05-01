const defaultTheme    = require('tailwindcss/defaultTheme');

module.exports = {
	darkMode: false, // or 'media' or 'class'
	theme: {
		extend: {
			 backgroundImage: theme => ({
				'no-thumb': "url('../img/no-image.jpg')",
			}),
			fontFamily: {
				sans: [
					'Montserrat',
					...defaultTheme.fontFamily.sans,
				],
			},
			fontSize: {
				'25px': ['1.5625rem', '2rem'], // font-size and line-height
			},
			height: {
				'150px': '9.375rem',
				'50': '12.5rem', // 200px
				'300px': '18.75rem',
				'100': '25rem', // 400px
				'125': '31.25rem', // 500px
				'150': '37.5rem', // 600px
				'200': '50rem', // 800px
				'mobile-screen': 'calc(var(--vh, 1vh) * 100)',
			},
			screens: {
				'xl': '1234px', // 1170px (2 paddings 32px (32*2 + 1170 = 1234))
				'2xl': '1234px',
			},
			spacing: {
				'25px': '1.5625rem', // padding, margin, width, height, maxHeight, gap, inset, space, and translate.
				'30px': '1.875rem',
				'50px': '3.125rem',
				'75px': '4.6875rem',
				'25': '6.25rem', // 100px
			},
			zIndex: {
				'-10': '-10',
				'1': '1',
			},
		},
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem',
				sm: '2rem',
			},
		},
	},
};
