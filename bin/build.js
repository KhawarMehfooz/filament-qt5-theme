import esbuild from 'esbuild'

esbuild.build({
    entryPoints: ['./resources/css/theme.css'],
    outfile: './resources/dist/theme.css',
    bundle: true,
    minify: true,
}).catch(() => process.exit(1))
