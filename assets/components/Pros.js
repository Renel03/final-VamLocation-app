import React, { Component } from 'react'
import Helmet from 'react-helmet'

export default class Pros extends Component {
    render() {
        return (
            <>
                <Helmet>
                    <title>Professionnels - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>

                <section className="section pt-70 pb-40">
                    <div className="wp">
                        <h1 className="title">Professionnels</h1>
                    </div>
                </section>
            </>
        )
    }
}
