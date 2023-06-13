import { useState } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import AppContext from './components/AppContext';
import Layout from './pages/Layout';
import ListarProdutos from './pages/produto/Listar'
import ExcluirProduto from './pages/produto/Excluir';
import AlterarProduto from './pages/produto/Alterar';
import CadastrarProduto from './pages/produto/Cadastrar';
import NotFound from './pages/NotFound';
import Home from './pages/Home';
import Sobre from './pages/Sobre';

function App() {
  const [tema, setTema] = useState("light");

  return (
    <div data-bs-theme={tema}>
      <AppContext.Provider value={{ tema, setTema}}>
        <Router>
          <Routes>
            <Route path="/" element={<Layout />}>
              <Route inde index element={<Home />} />
              <Route path="sobre" element={<Sobre />} />
              <Route path="Produtos">
                <Route index element={<ListarProdutos />} />
                <Route path="cadastrar" element={<CadastrarProduto />} />
                <Route path="alterar/:id" element={<AlterarProduto />} />
                <Route path="excluir/:id" element={<ExcluirProduto />} />
              </Route>
              <Route path="*" element={<NotFound />} />
            </Route>
          </Routes>
        </Router>      
      </AppContext.Provider>
    </div>
  )
}

export default App
