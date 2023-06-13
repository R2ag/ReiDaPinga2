import { useEffect, useState } from 'react'
import { useNavigate, useParams } from "react-router-dom";
import axios from "axios";
import { handleChange, validar } from '../../lib/FormUtils';
import validator from '../../lib/ValidatorProduto';
import FormProduto from '../../components/produtos/Form'

const Alterar = () => {
  const [inputs, setInputs] = useState({});
	const [errors, setErrors] = useState({});
	const navigate = useNavigate();

	const id = useParams().id;
	if (!id) {
		navigate("/listagem");
	}

	function carregarDados(){
		axios.get(`http://localhost:3001/produtos/${id}`)
			.then((resp) => {
				if(resp.status === 200){
					setInputs(resp.data);
				}else if(resp.status === 404){
					navigate("/produtos");
				}else{
					console.log(resp);
				}
			})
			.catch((error) => {
				console.log(error);
			});
	}

	useEffect(() => {
		carregarDados();
	}, [id]);

	function validarLocal(callbackAction){
		validar(callbackAction, inputs, setErrors, validator);
	}

	function handleChangeLocal(e){
		handleChange(e, setInputs, inputs);
	}

	function handleSubmit(e){
		e.preventDefault();
		validarLocal(() => {
			axios
				.put(`http://localhost:3001/produtos/${id}`, inputs)
				.then((resp) => {
					if(resp.status ===200){
						alert("Alteração realizada com sucesso!");
						navigate("/produtos")
					}
				});
		});
	}

	useEffect(() => {
		validarLocal();

	}, [inputs])

		
  return (
    <>
			<h1>Alteração de Produto </h1>
			<hr />
			<FormProduto handleSubmit={handleSubmit} handleChange={handleChangeLocal} inputs={inputs} errors={errors} />
		</>
  )
}

export default Alterar