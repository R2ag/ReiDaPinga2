import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import validator from "../../lib/ValidatorProduto";
import { handleChange, validar } from "../../lib/FormUtils";
import FormProduto from '../../components/produtos/Form'

const Cadastrar = () => {

	const [inputs, setInputs] = useState({});
	const [errors, setErrors] = useState({});
	const navigate = useNavigate();

	function validarLocal(callbackAction) {
		validar(callbackAction, inputs, setErrors, validator);
	}

	function handleChangeLocal(e) {
		handleChange(e, setInputs, inputs);
	}

	function handleSubmit(e) {
		e.preventDefault();
		validarLocal(() => {
			axios
				.post("http://localhost:3001/produtos", inputs)
				.then((resp) => {
					if (resp.status == 201) {
						alert("Produto inserido com sucesso!");
						navigate("/produtos")
					}
				});
			console.log("Enviou dados para a API.");
		});
	}

	useEffect(() => {
		validarLocal();
	}, [inputs])


	return (
		<>
			<h1>Cadastro de Produtos</h1>
			<hr />
			<FormProduto handleSubmit={handleSubmit} handleChange={handleChangeLocal} inputs={inputs} errors={errors} />
		</>

	)
}

export default Cadastrar