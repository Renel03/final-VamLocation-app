import React from 'react'
import Helmet from 'react-helmet'

export default function Exception({ code }) {
    window.scrollTo(0, 0)
    document.getElementsByTagName("body")[0].classList.add('is-sticky')
    
    return (
        <>
            <Helmet>
                <title>{ code == '404' ? 'Page introuvable' : 'Erreur inconnue' } - AlloCar</title>
                <meta name="robots" content="noindex,nofollow" />
            </Helmet>

            <section className="section pt-70 pb-40">
                <div className="container">
                    <h1 className="title">{ code == '404' ? 'Page introuvable' : 'Erreur inconnue' }</h1>
                </div>
            </section>
        </>
    )
}
