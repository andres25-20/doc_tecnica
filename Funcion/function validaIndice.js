function validaIndice(nums, objetivo) {
    let validador = {};

    for (let i = 0; i < nums.length; i++) {
        let num = nums[i];
        let complemento = objetivo - num;

        if (validador[complemento] !== undefined) {
            return [validador[complemento], i];
        }

        validador[num] = i;
    }

    return null;
}

// Datos de ejemplo
let nums = [2, 7, 11, 15];
let objetivo = 9;
let resultado = encontrarIndices(nums, objetivo);
console.log(resultado);  // Salida: [0, 1] (porque nums[0] + nums[1] = 9)



