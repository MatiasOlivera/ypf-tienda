/**
 * Validar si una variable de entorno existe o si el tiene un valor válido
 *
 * @param nombre El nombre de la variable
 * @param valor El valor de la variable
 */
function validarVariable(nombre: string, valor: string): string | null {
  return [undefined, null, ''].includes(valor)
    ? `La variable de entorno ${nombre} debe ser especificada o el valor es incorrecto"`
    : null;
}

/**
 * Validar las variables de entorno definidas en Node.js
 *
 * @export
 * @param env Variables de entorno de Node.js
 * @param variables Las variables que deben estar definidas en env
 */
export function validarVariables(
  env = process.env,
  variables: Array<string>
): Array<string> {
  if (variables.length === 0) return [];

  const mensajes: Array<string> = variables
    .map((variable) => validarVariable(variable, env[variable]))
    .filter((mensaje): mensaje is string => mensaje !== null);

  return mensajes;
}

export default {
  validarVariables
};
