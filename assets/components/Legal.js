import React, { useContext } from 'react'
import Helmet from 'react-helmet'
import Context from '../contexts/Context'

export default function Legal() {
    window.scrollTo(0, 0)
    document.getElementsByTagName("body")[0].classList.add('is-sticky')

    return (
        <>
            <Helmet>
                <title>Mentions légales - AlloCar</title>
                <meta name="robots" content="noindex,nofollow" />
            </Helmet>

            <section className="section bg-gray pt-70 pb-40">
                <div className="wp">
                    <div className='page'>
                        <h1 className="title">Mentions légales</h1>
                        <div className='page-body'>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez. Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez. Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez. Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                            <p>Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez. Nous connaissons chacun de nos membres et de nos partenaires de bus. Comment ? Nous vérifions profils, avis, et pièces d'identité. Vous savez ainsi avec qui vous voyagez.</p>
                        </div>
                    </div>
                </div>
            </section>
        </>
    )
}
