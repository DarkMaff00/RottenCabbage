import style from './Page.module.css';
import NavBar from "../NavBar/NavBar";

const Page = props => {
    return (
        <div className={style.app}>
            <NavBar key="NavBar" type={props.subpage}></NavBar>
            {props.children}
        </div>
    );
}

export default Page;