// Máscara (47) 99999-9999 para o campo de telefone.
const campoTelefone = document.getElementById('telefone_usuario');

if (campoTelefone) {
    campoTelefone.addEventListener('input', () => {
        let telefone = campoTelefone.value.replace(/\D/g, '').slice(0, 11);

        if (telefone.length > 2) {
            telefone = `(${telefone.slice(0, 2)}) ${telefone.slice(2)}`;
        } else if (telefone.length > 0) {
            telefone = `(${telefone}`;
        }

        if (telefone.length > 10) {
            telefone = `${telefone.slice(0, 10)}-${telefone.slice(10)}`;
        }

        campoTelefone.value = telefone;
    });
}
