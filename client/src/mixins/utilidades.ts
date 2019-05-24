export function borrarObjetoEnArrayPorId(array: any, objeto: any): any {
  if (array !== undefined && array !== []) {
    const index = array
      .map(function(item: any) {
        return item.id;
      })
      .indexOf(objeto.id);
    if (index >= 0) {
      return array.splice(index, 1);
    }
    return array;
  }
}

export function remplazarObjetoEnArrayPorId(array: any, objeto: any): any {
  if (array !== undefined && array !== []) {
    return array.map((item: any) => {
      if (item.id === objeto.id) {
        item = objeto;
      }
      return item;
    });
  }
  return [objeto];
}

export function agregarElementoAlArray(array: any, objeto: any): any {
  if (array !== undefined) {
    const largo = array.push(objeto);
    return array;
  } else {
    return [objeto];
  }
}
