/** @type {import('vite').UserConfig} */
export default {
    build: {
      assetsDir: '',
      manifest: true,
      rollupOptions: {
        input: ['src/Resources/js/app.js', 'src/Resources/scss/app.scss'],
      },
    },
  };