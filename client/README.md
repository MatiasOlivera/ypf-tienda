# Cliente web

[![Estado dependencias](https://david-dm.org/MatiasOlivera/ypf-tienda/status.svg?path=client)](https://david-dm.org/MatiasOlivera/ypf-tienda?path=client)
[![Estado dependencias de desarrollo](https://david-dm.org/MatiasOlivera/ypf-tienda/dev-status.svg?path=client)](https://david-dm.org/MatiasOlivera/ypf-tienda?path=client&type=dev)
[![Estilo de código](https://badgen.net/badge/code%20style/airbnb/ff5a5f)](https://github.com/airbnb/javascript)
[![Formateador de código](https://img.shields.io/badge/code_style-prettier-ff69b4.svg)](https://github.com/prettier/prettier)

[Volver a inicio](../README.md)

## Estructura del proyecto

```bash
|   .browserslistr                     # Compatibilidad con navegadores
|   .editorconfig                      # Configuración del estilo del código (EditorConfig)
|   .eslintrc.js                       # Configuración del linting (ESlint)
|   .gitignore                         # Archivos y directorios ignorados por Git
|   .prettierrc.json                   # Configuración de formateo del código (Prettier)
|   babel.config.js                    # Configuración de la compilación (Babel)
|   cypress.json                       # Configuración de tests de integración (Cypress)
|   jest.config.js                     # Configuración del framework de testing (Jest)
|   package-lock.json                  # Instantánea del árbol de dependencias
|   package.json                       # El manifiesto, scripts de desarrollo y dependencias
|   postcss.config.js                  # Configuración de PostCSS
|   README.md                          # Documentación
|   tsconfig.json                      # Configuración de Typescript
|   vue.config.js                      # Configuración de Vue CLI
|
+---node_modules                       # Módulos de Node.js
|
+---public                             # Archivos estáticos (cuando compila son copiados)
|
+---src
|   |   App.vue                        # Componente principal
|   |   main.ts                        # Archivo de entrada
|   |   shims-tsx.d.ts                 # Definir el tipo para poder usar JSX
|   |   shims-vue.d.ts                 # Definir la extensión .vue para poder usarla con Typescript
|   |
|   +---components                     # Componentes
|   |   \---__tests__                  # Tests de los componentes
|   |
|   +---config                         # Configuración / constantes
|   |
|   +---mixins                         # Mixins de Vue
|   |   \---__tests__                  # Tests de los mixins
|   |
|   +---plugins                        # Plugins para Vue
|   |   |   index.ts                   # Archivo barrel
|   |   |   tipos-plugins.ts           # Tipos de Typescript para los plugins
|   |   \---__tests__                  # Tests de los plugins
|   |
|   +---router                         # Router de Vue
|   |       index.ts                   # Instancia del router
|   |       rutas.ts                   # Definición de las rutas
|   |
|   +---services                       # Servicios externos
|   |   +---api                        # Comunicación con el servidor
|   |   |       nombre-api.ts          # Ejemplo de módulo
|   |   peticion.ts                    # Función que se encarga de hacer las peticiones
|   |
|   +---static                         # Archivos estáticos (cuando compila son procesados por Webpack)
|   |   +---fonts                      # Fuentes
|   |   +---images                     # Imágenes
|   |   \---styles                     # Estilos CSS
|   |           fuentes.scss           # Fuentes personalizadas
|   |           index.scss             # Archivos principal
|   |           personalizado.scss     # Estilos personalizados (no Bootstrap)
|   |           variables.scss         # Variables de Bootstrap
|   |
|   +---store                          # Vuex store
|   |   |---index.ts                   # Instancia de Vuex
|   |   |---acciones.ts                # Acciones raíz
|   |   |---mutaciones.ts              # Mutaciones raíz
|   |   |---modules                    # Módulos
|   |   |       nombre-modulo.ts       # Módulo de ejemplo
|   |   \---types                      # Tipos de la store
|   |           acciones-tipos.ts      # Nombre de las acciones
|   |           mutaciones-tipos.ts    # Nombre de las mutaciones
|   |
|   +---types                          # Deficiones de tipos para Typescript
|   |
|   \---views                          # Páginas
|       EjemploView.vue                # Página de ejemplo
|
\---tests                              # Tests
    +---e2e                            # Tests de integración
    \---unit                           # Tests unitarios
```

## CLI

| Comando             | Descripción                                |
| ------------------- | ------------------------------------------ |
| `npm install`       | Instala las dependencias                   |
| `npm run serve`     | Compila e inicia un servidor de desarrollo |
| `npm run build`     | Compila y minifica para producción         |
| `npm run lint`      | Linting y correción en los archivos        |
| `npm run test`      | Corre los tests unitarios (alias)          |
| `npm run test:e2e`  | Corre los tests de integración (e2e)       |
| `npm run test:unit` | Corre los tests unitarios                  |
