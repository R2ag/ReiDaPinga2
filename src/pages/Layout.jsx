import { Outlet } from "react-router-dom";
import Header from "../components/layout/Header";
import Footer from "../components/layout/Footer";


const Layout = () => {
  return (
    <>
        <Header />
        <div className="container mb-5 pb-5">
            <Outlet />
        </div>
        <Footer />
    </>
  )
}

export default Layout