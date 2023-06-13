/* eslint-disable react/prop-types */
import FormButtons from "../forms/FormButtons"
import FormInput from "../forms/FormInput"


const Form = ({ handleSubmit, handleChange, errors, inputs }) => {
    return (
        <form onSubmit={handleSubmit} noValidate autoComplete="off">
            <FormInput type="text" field="nome" label="Nome" placeholder="Nome do Produto" error={errors?.nome} onChange={handleChange} value={inputs?.nome} />
            <FormInput type="text" field="descricao" label="Descrição" placeholder="Descrição do produto" error={errors?.descricao} onChange={handleChange} value={inputs?.descricao} />
            <FormInput type="text" field="graduacao" label="Teor Alcoolico" placeholder="15.0%" error={errors?.graduacao} onChange={handleChange} value={inputs?.graduacao} />
            <FormInput type="text" field="fabricacao" label="Ano de Fabricação" placeholder="1997" error={errors?.fabricacao} onChange={handleChange} value={inputs?.fabricacao} />
            <FormInput type="text" field="preco" label="Preço" placeholder="49.9" error={errors?.preco} onChange={handleChange} value={inputs?.preco} />
            <FormButtons cancelTarget="/produtos" />
        </form>
    )
}

export default Form

