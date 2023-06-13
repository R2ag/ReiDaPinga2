import { useContext } from "react";
import AppContext from "./AppContext";

const Footer = () => {
  const { tema, } = useContext(AppContext);
  return (
    <>
      <footer className={`fixed-bottom bg-body-tertiary ${tema === "dark" ? "text-light" : "text-dark"} text-center p-3`}>
        Copyright &copy; Ricardo Maroquio 2023 <br />
        Todos os direitos reservados.
      </footer>
    </>
  )
}

export default Footer