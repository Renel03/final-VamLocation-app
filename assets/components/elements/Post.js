import React from 'react'

function getPrice(price)
{
    return new Intl.NumberFormat('fr-FR').format(price)
}

export default function Post({ post }) {
    return (
        <div className='post-line'>
            <div className="post">
                <div className="post-media">
                    <a href="">
                        <img src={ post.photos[0].photo } alt=""/>
                    </a>
                    <span className="post-media-nb-photos"><svg width="12" height="12" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M16.02 3.005c.314 0 .437.583.572.86l1.357 3.138h4.988c.587 0 1.063.476 1.063 1.063v12.052a.881.881 0 0 1-.879.877H.763A.765.765 0 0 1 0 20.232V8.044c0-.572.467-1.041 1.04-1.041h4.885l1.312-3.139c.105-.304.313-.859.628-.859h8.154zM12 8.603c2.482 0 4.5 2.014 4.5 4.497a4.501 4.501 0 0 1-9.002 0A4.501 4.501 0 0 1 12 8.603z"></path></g></svg> 4</span>
                </div>
                <div className="post-infos">
                    <div className="post-header d-flex align-items-center">
                        <h3 className="post-title">
                            <a href="">{ post.trademark.name } { post.model.name } { post.version.name } { post.user.type == 1 && <span className='is-pro'>Pro</span> }</a>
                        </h3>
                        <div className="post-author">
                            <a href="">
                                <img src="/images/logo.svg" alt=""/>
                            </a>
                        </div>
                    </div>
                    <div className="post-price">{ getPrice(post.price) } Ar</div>
                    <div className="post-localization">
                        <a href=""><svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M12 0c2.138 0 4.147.874 5.658 2.462 2.797 2.937 3.144 8.464.743 11.827L12.001 24l-6.41-9.725C3.2 10.926 3.547 5.399 6.344 2.462 7.854.874 9.864 0 12 0zm.105 1c-1.86 0-3.744.879-5.06 2.23-2.434 2.502-2.651 7.223-.517 10.557L12 22.19l5.734-8.724c1.893-3.011 1.66-7.08-.58-9.946C15.942 1.966 13.965 1 12.105 1zM12 4.456a3.877 3.877 0 0 1 3.872 3.872c0 2.134-1.738 3.871-3.872 3.871s-3.872-1.737-3.872-3.871A3.877 3.877 0 0 1 12 4.456zm0 1.066a2.81 2.81 0 0 0-2.806 2.806A2.809 2.809 0 0 0 12 11.133a2.809 2.809 0 0 0 2.806-2.805A2.81 2.81 0 0 0 12 5.522z"></path></g></svg> Antananarivo (101)</a>
                    </div>
                    <ul className="post-characteristics">
                        <li>{ post.year }</li>
                        <li>{ post.fuel }</li>
                        <li>{ post.mileage }km</li>
                        <li>{ post.consummation }L/100km</li>
                        <li>{ post.power }CV</li>
                        <li>{ post.nbDoor } portes</li>
                        <li>{ post.nbPlace } places</li>
                        <li>Boîte { post.speedType == 1 ? "automatique" : "mécanique" }</li>
                    </ul>
                    <div className="post-btns">
                        <a href="" className="btn btn-primary">Voir le détail</a>
                    </div>
                </div>
            </div>
        </div>
    )
}
