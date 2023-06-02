import style from './UserTab.module.css';
import axios from "axios";
import {API_BASE_URL} from "../../index";
import {useNavigate} from "react-router-dom";


function UserTab(props) {
    const navigate = useNavigate();

    const viewProfile = async (e) => {
        e.preventDefault();
        const response = await axios.get(`${API_BASE_URL}${props.user.email}`);
        const id = response.data
        navigate('/profile/' + id);
    };

    return (
        <div className={style.userTab} onClick={viewProfile}>
            <p className={style.names}>{props.user.firstName + " " + props.user.lastName}</p>
            <p>{props.user.email}</p>
        </div>
    );
}

export default UserTab;