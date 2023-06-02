import React, {useState} from 'react';
import UserTab from "../../components/UserTab/UserTab";
import style from './Following.module.css';
import Page from '../../components/Page/Page';
import FollowingTab from "../../components/FollowingTab/FollowingTab";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";
import axios from "axios";
import {API_BASE_URL} from "../../index";


function Following() {

    const [users, setUsers] = useState([]);

    const handleSubmit = async (value) => {
        return await axios.get(`${API_BASE_URL}users`, {params: {search: value}});
    }


    const searchUsers = (value) => {
        handleSubmit(value).then(r => {
            if (r.status === 200) return r.data;
            throw r;
        })
            .then((data) => {
                setUsers(data);
            })
            .catch(() => {
                console.log("Error");
            });
    };

    return (
        <Page subpage="following">
            <div className={style.followingDiv}>
                <div className={style.followingInfo}>
                    <FollowingTab/>
                    <FollowingTab/>
                    <FollowingTab/>
                    <FollowingTab/>
                </div>
                <div className={style.searchUser}>
                    <FormBox width="80%">
                        <h2 className={style.title}>Search users</h2>
                        <Input
                            type="text"
                            placeholder="search..."
                            onChange={searchUsers}
                        />
                        <hr style={{width: '80%'}}/>
                        {
                            users.map((user) => (
                                <UserTab key={user.email} user={user} />
                            ))
                        }
                    </FormBox>
                </div>
            </div>
        </Page>
    );
}

export default Following;