import React, { Component } from 'react'
import { Helmet } from 'react-helmet'
import Context from '../../contexts/Context'

export default class Profile extends Component {
    render() {
        const { user } = this.context

        return (
            <>
                <Helmet>
                    <title>Mon profil - AlloCar</title>
                    <meta name="robots" content="noindex,follow"/>
                </Helmet>
                <h1 className='title'>Mon profil</h1>
                <button className='btn btn-primary'>test</button>
            </>
      )
    }
}

Profile.contextType = Context