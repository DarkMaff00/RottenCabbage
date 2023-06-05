import React, {useEffect, useState} from 'react';
import UserTab from "../../components/UserTab/UserTab";
import style from './Following.module.css';
import Page from '../../components/Page/Page';
import FollowingTab from "../../components/FollowingTab/FollowingTab";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";
import axios from "axios";
import {API_BASE_URL} from "../../index";
import {useCookies} from "react-cookie";
import Loading from "../../components/Loading/loading";


function Following() {

    const [users, setUsers] = useState([]);
    const [cookie] = useCookies(['jwt']);
    const [following, setFollowing] = useState([]);
    const [dataLoaded, setDataLoaded] = useState(false);

    const handleSubmit = async (value) => {
        return await axios.get(`${API_BASE_URL}users`, {params: {search: value}});
    }

    const fetchFollowing = async () => {
        return await axios.get(`${API_BASE_URL}following`, {
            headers: {
                Authorization: `Bearer ${cookie.jwt}`,
            }
        });
    }

    useEffect(() => {
        fetchFollowing().then(r => {
            if (r.status === 200) return r.data;
        })
            .then((data) => {
                setFollowing(data);
                setDataLoaded(true);
            })
    }, []);


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
            {dataLoaded ? (
                <div className={style.followingDiv}>
                    <div className={style.followingInfo}>
                        {following.map((follow) => (
                            <FollowingTab
                                key={follow.email}
                                id={follow.id}
                                name={follow.name}
                                lastName={follow.surname}
                                email={follow.email}
                            />
                        ))}
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
                                    <UserTab key={user.email} user={user}/>
                                ))
                            }
                        </FormBox>
                    </div>
                </div>
            ) : (
                <Loading/>
            )}
        </Page>
    );
}

export default Following;