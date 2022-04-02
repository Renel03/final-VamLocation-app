import React, { Component } from 'react'
import Helmet from 'react-helmet'

export default class Contact extends Component {
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
                    <title>Contact - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>

                <section className="section pt-70 pb-40">
                    <div className="wp">
                        <h1 className="title">Contact</h1>
                        <div className='row'>
                            <div className='col-12 col-md-8'>
                                A
                            </div>
                            <div className='col-12 col-md-4'>
                                B
                            </div>
                        </div>
                    </div>
                </section>
            </>
        )
    }
}
