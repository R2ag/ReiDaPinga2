import { useNavigate, useParams } from "react-router-dom";
import FormButtons from "../../components/forms/FormButtons";
import axios from "axios";
import { useEffect, useState } from "react";

const Excluir = () => {

	const [produto, setProduto] = useState({});
	const id = useParams().id;

	const navigate = useNavigate();

	function carregarDados() {
		axios.get(`http://localhost:3001/produtos/${id}`)
			.then((resp) => {
				if (resp.status === 200) {
					setProduto(resp.data);
				} else if (resp.status === 404) {
					navigate("/produtos");
				} else {
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

	function handleDelete() {
		axios.delete(`http://localhost:3001/produtos/${id}`)
			.then((resp) => {
				if (resp.status === 200) {
					alert("Produto excluído com sucesso!");
					navigate("/produtos")
				} else {
					console.log(resp);
				}
			})
			.catch((error) => {
				console.log(error);
			});
	}


	return (
		<>
			<h1>Exclusão de Produtos</h1>
			<hr />
			<p className="lead">Deseja realmente excluir o produto {produto.nome}?</p>
			<FormButtons cancelTarget="/produtos" negativeTitle="Não" positiveTitle="Sim" positiveAction={handleDelete} buttonType="button" />
		</>
	)
}

export default Excluir