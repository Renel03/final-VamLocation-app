import React, { Component } from 'react'
import Helmet from 'react-helmet'

export default class Sell extends Component {
    constructor(){
        super()
    }

    componentDidMount(){
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')
    }

    componentDidUpdate(prevProps){
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
        }
    }
    
    render() {
        return (
            <>
                <Helmet>
                    <title>Vente de véhicule - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>

                <section className="section pt-70 pb-40">
                    <div className="wp">
                        <h1 className="title">Vente de véhicule</h1>
                    </div>
                </section>
            </>
        )
    }
}
