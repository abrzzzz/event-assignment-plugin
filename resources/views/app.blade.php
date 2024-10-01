<!DOCTYPE html>
<html >
  <head>
    <title>Events app</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script type="module">
      import RefreshRuntime from 'http://localhost:1337/@react-refresh'
      RefreshRuntime.injectIntoGlobalHook(window)
      window.$RefreshReg$ = () => {}
      window.$RefreshSig$ = () => (type) => type
      window.__vite_plugin_react_preamble_installed__ = true
    </script>
    <script type="module" src="http://localhost:1337/@@vite/client"></script>
    <script type="module" src="http://localhost:1337/resources/scripts/src/app.tsx"></script>
    @inertiaHead
  </head>
  <body>
    @inertia

  </body> 
</html>