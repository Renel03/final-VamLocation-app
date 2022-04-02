import axios from 'axios'
import React, { Component } from 'react'
import Helmet from 'react-helmet'
import Aside from './elements/Aside'

export default class FAQ extends Component {
    constructor(){
        super()
        this.state = {
            faqs: [],
            meta: [],
            loading: true,
            hasError: false,
            faqsError: null
        }
    }

    componentDidMount(){
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')    
        this.fetchFaqs()
    }
    
    componentDidUpdate(prevProps){
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
        }
    }

    async fetchFaqs(){
        const { location: { search } } = this.props
        await axios     .get(`/api/v1/faqs${search}`)
                        .then(({ data: data }) => {
                            this.setState({
                                faqs: data.data,
                                meta: data.meta,
                                loading: false,
                            })
                        })
                        .then((err) => {
                            this.setState({
                                hasError: true,
                                faqsError: err,
                                loading: false,
                            })
                        })
    }

    render() {
        return (
            this.state.loading ? (
                <div className='loader'></div>
            ) : (
            <>
                <Helmet>
                    <title>Foire Aux Questions - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>

                <section className="section pt-70 pb-40">
                    <div className="wp">
                        <div className='row'>
                            <div className='col-12 col-md-8 col-lg-9'>
                                <h1 className="title">Foire Aux Questions</h1>
                                <div className='faqs'>
                                { this.state.faqs.map(faq => {
                                    return(
                                        <div className='faq' key={ faq.id }>
                                            <button className='faq-open clearfix' data-body={ faq.content } data-head={ faq.title } data-updated-at={ faq.updatedAt }><span className='float-right'><i className='fa fa-plus'></i></span><h3>{ faq.title }</h3></button>
                                        </div>
                                    )
                                }) }
                                </div>
                            </div>
                            <div className='col-12 col-lg-3'>
                                <Aside />
                            </div>
                        </div>
                    </div>
                </section>

                <div className='faq-modal'>
                    <div className='faq-container'>
                        <h3 className='faq-head'></h3>
                        <div className='faq-body'></div>
                        <div className='faq-foot'></div>
                    </div>
                </div>
            </>
            )
        )
    }
}
