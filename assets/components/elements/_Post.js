import React from 'react'

export default function _Post({ post }) {
    return (
        <div className='_post'>
            <div className='_post-media'>
                <a href="">
                    <img src="/images/E108997455_STANDARD_0.jpg" alt="" />
                </a>
                <span className='_post-nb-photos'><svg width="12" height="12" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M16.02 3.005c.314 0 .437.583.572.86l1.357 3.138h4.988c.587 0 1.063.476 1.063 1.063v12.052a.881.881 0 0 1-.879.877H.763A.765.765 0 0 1 0 20.232V8.044c0-.572.467-1.041 1.04-1.041h4.885l1.312-3.139c.105-.304.313-.859.628-.859h8.154zM12 8.603c2.482 0 4.5 2.014 4.5 4.497a4.501 4.501 0 0 1-9.002 0A4.501 4.501 0 0 1 12 8.603z"></path></g></svg> 4</span>
            </div>
            <div className='_post-info'>
                <h4 className='_post-title'><a href="">Toyota Prado RX <span className='is-pro'>Pro</span></a></h4>
                <div className='_post-price'>200 000 000 Ar</div>
                <div className="_post-localization">
                    <a href=""><i className='fa fa-map-marker-alt' style={{ fontSize: "18px" }}></i> Antananarivo (101)</a>
                </div>
            </div>
        </div>
    )
}
