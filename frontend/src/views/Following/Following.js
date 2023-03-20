import React from 'react';
import style from './Following.module.css';
import Page from '../../components/Page/Page';
import FollowingTab from "../../components/FollowingTab/FollowingTab";
import FormBox from "../../components/FormBox/FormBox";
import Input from "../../components/Input/Input";


function Following() {

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
                        <Input placeholder="search..."/>
                        <hr style={{width: '80%'}}/>
                    </FormBox>
                </div>
            </div>
        </Page>
    );
}

export default Following;