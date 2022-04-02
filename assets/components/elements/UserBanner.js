import React, { useContext } from 'react'
import { NavLink, Link } from 'react-router-dom'
import Context from '../../contexts/Context'

export default function UserBanner() {
    const { user } = useContext(Context)
    return (
        <div className='user-banner'>
            <div className='wp'>
                <div className='user-banner-info d-flex align-items-center'>
                    <div className='user-banner-avatar'>
                        <img src='/images/avatar.jpg' alt='' />
                        <Link to='/'><i className='fa fa-camera'></i></Link>
                    </div>
                    <div className='user-banner-info-details'>
                        <h1>{ user.type == 1 ? user.companyName : ( user.lastname == null && user.firstname == null ? "N/A" : user.lastname + " " + user.firstname ) }</h1>
                        { user.type == 1 && <p>({ user.lastname == null && user.firstname == null ? "N/A" : user.lastname + " " + user.firstname })</p> }
                    </div>
                </div>
                <nav className='user-banner-nav'>
                    <ul>
                        <li><NavLink exact to='/moncompte'>Mon profil</NavLink></li>
                        <li><NavLink to='/moncompte/mes-demandes.html'>Mes demandes</NavLink></li>
                        <li><NavLink to='/moncompte/mes-ventes.html'>Mes ventes</NavLink></li>
                        <li><NavLink to='/moncompte/parametres.html'>Paramètres</NavLink></li>
                    </ul>
                </nav>
            </div>
        </div>
    )
}
