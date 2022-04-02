import React, { Component } from 'react'
import Helmet from 'react-helmet'
import { Route, Switch } from 'react-router-dom'
import Context from '../../contexts/Context'
import UserBanner from '../elements/UserBanner'
import Demand from './Demand'
import Profile from './Profile'

export default class Account extends Component {
    constructor(){
        super()
    }

    componentDidMount(){
        const { user } = this.context
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')
        if(user == null)
            this.props.history.push('/')
    }

    componentDidUpdate(prevProps){
        const { user } = this.context
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
        }
        if(user == null)
            this.props.history.push('/')
    }

    render() {
        const { user } = this.context
        return (
            user != null &&
            <>
                <section className='section pt-60 pb-40'>
                    <UserBanner />
                    <div className='wp pt-20'>
                        <Switch>
                            <Route exact path='/moncompte' component={ Profile } />
                            <Route path='/moncompte/mes-demandes.html' component={ Demand } />
                        </Switch>
                    </div>
                </section>             
            </>
        )
    }
}

Account.contextType = Context