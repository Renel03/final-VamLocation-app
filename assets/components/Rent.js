import React, { Component } from 'react'
import Helmet from 'react-helmet'

export default class Rent extends Component {
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
                    <title>Location de voiture - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>

                <section className="section pt-70 pb-40">
                    <div className="wp">
                        <h1 className="title">Location de voiture</h1>
                    </div>
                </section>
            </>
        )
    }
}
