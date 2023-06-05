import style from './loading.module.css';


function Loading() {

    return (
        <div className={style.loadingAnimation}>
            <div className={style.lds}>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    );
}

export default Loading;