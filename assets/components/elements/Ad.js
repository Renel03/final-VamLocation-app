import React, { Component } from 'react'

export default class Ad extends Component {
    render() {
        return (
            <div className='ads aside-item'>
                <h4 className='aside-title'>Publicité</h4>
                <a href="" target="_blank">
                    <img src="/images/LaCentrale_C-AnnonceGratuite_300x375.jpg" alt="" />
                </a>
            </div>
        )
    }
}
