import React, { useContext } from 'react'
import Helmet from 'react-helmet'
import Context from '../contexts/Context'

export default function About() {
    window.scrollTo(0, 0)
    document.getElementsByTagName("body")[0].classList.add('is-sticky')

    return (
        <>
            <Helmet>
                <title>À propos d'AlloCar - AlloCar</title>
                <meta name="robots" content="index,follow" />
            </Helmet>
            
            <section className="section pt-70 pb-40">
                <div className="wp">
                    <h1 className="title">À propos d'AlloCar</h1>
                </div>
            </section>
        </>
    )
}
