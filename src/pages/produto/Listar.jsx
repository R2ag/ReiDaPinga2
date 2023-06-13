import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import estilos from "./Listar.module.css"
import axios from "axios";


const Listar = () => {
	const [produtos, setProdutos] = useState([]);
	const [loading, setLoading] = useState(true);

	const carregarAlunos = () => {
		axios
			.get("http://localhost:3001/produtos")
			.then((resp) => {
				setProdutos(resp.data);
				setLoading(false);
			});
	}

	useEffect(() => {
    carregarAlunos();
  }, []);


	return (
		<>
      <div className="d-flex justify-content-between align-items-center">
        <h1>Listagem de Produtos</h1>
        <Link className="btn btn-primary" to="cadastrar">Novo</Link>
      </div>
      <hr />
      {loading &&
        (<div className="text-center">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Carregando...</span>
          </div>
        </div>)}
      {!loading && (
        <table className={`table table-striped ${estilos.tabela}`}>
          <thead>
            <tr>
              <th>Id</th>
              <th>Nome</th>
              <th>Ano de Fabricação</th>
              <th>Preço</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            {
              produtos.map((produto) =>
                <tr key={produto.id}>
                  <td>{produto.id}</td>
                  <td>{produto.nome}</td>
                  <td>{produto.fabricacao}</td>
                  <td>R$ {produto.preco}</td>
                  <td>
                    <Link className="btn btn-sm btn-success me-1" to={`/produtos/alterar/${produto.id}`}>
                      <i className="bi bi-pen" title="Alterar"></i>
                    </Link>
                    <Link className="btn btn-sm btn-danger" to={`/produtos/excluir/${produto.id}`}>
                      <i className="bi bi-trash" title="Excluir"></i>
                    </Link>
                  </td>
                </tr>
              )
            }
          </tbody>
        </table>
      )}
    </>
	)
}

export default Listar