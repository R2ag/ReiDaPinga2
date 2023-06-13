import * as yup from "yup";

const validator = yup.object().shape({
    nome: yup.string().required("Nome é obrigatório.").min(6, "Nome deve ter pelo menos 6 caracteres.").max(32, "Nome deve ter no máximo 32 caracteres."),
    descricao: yup.string().required("E-mail é obrigatório.").min(10, "Crie uma descrição detalhada do produto"),
    graduacao: yup.number("Somente números.").required("Essa é uma informação importante sobre o produto.").positive().max(99.9, "O maior percentual de alcool aceito é de 99.9%"),
    fabricacao: yup.number("Campo numérico.").required("O ano de fabricação é uma infomação essencial sobre o produto.").positive().integer().min(1900).max(2023),
    preco: yup.number("Campo numérico.").required("Preço é obrigatório.").positive()
});

export default validator;