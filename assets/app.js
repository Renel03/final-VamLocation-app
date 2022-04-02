import React from 'react'
import ReactDOM from 'react-dom'
import { BrowserRouter, Route, Switch, NavLink, Link } from 'react-router-dom'
import axios from 'axios'
import Home from './components/Home';
import Search from './components/Search';
import About from './components/About';
import Legal from './components/Legal';
import Context from './contexts/Context';
import Exception from './components/Exception';
import Contact from './components/Contact';
import Faq from './components/Faq';
import Tariff from './components/Tariff';
import Pros from './components/Pros';
import Rent from './components/Rent';
import Buy from './components/Buy';
import Sell from './components/Sell';
import Terms from './components/Terms';
import Privacy from './components/Privacy';
import Booking from './components/Booking';
import Account from './components/accounts/Account';
import Demand from './components/accounts/Demand';

export default class App extends React.Component {
    constructor() {
        super()

        this.updatedUser = (user) => {
            this.setState({
                user: user
            })
        }

        this.state = {
            user: null,
            updatedUser: this.updatedUser,
            loading: true,
            date: new Date(),
        }
    }

    componentDidMount(){
        this.fetchUser()
    }

    async fetchUser(){
        await axios     .get(`/api/v1/user`)
                        .then(({ data: data }) => {
                            this.setState({
                                user: data,
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {
                            this.setState({
                                loading: false,
                            })
                        })
    }

    render() {
        return (
            this.state.loading ? (
                <div className="loader"></div>
            ) : (
            <Context.Provider value={ this.state }>
                <BrowserRouter>
                    <header id="header">
                        <div className="wp d-flex align-items-center">
                            <div className="logo">
                                <NavLink exact to="/">
                                    <img src="/images/lecomparateurassurance.svg" alt=""/>
                                </NavLink>
                            </div>
                            <span className="flex-grow-1"></span>
                            <div className="auth d-flex align-items-center">
                                <ul>
                                    <li><Link to="/faq.html"><i className='fa fa-question-circle'></i> FAQ</Link></li>
                                    <li><Link to="/contact.html">Contact</Link></li>
                                    { this.state.user == null ? (
                                    <>
                                        <li>
                                            <a href="/connexion.html" className="btn btn-gray">Connexion</a>
                                        </li>
                                        <li>
                                            <a href="/inscription.html" className="btn btn-primary">Inscription</a>
                                        </li>
                                        </>
                                    ) : (
                                    <>
                                        <li>
                                            <a href="" className='notify'>
                                                <i className='fa fa-bell'></i>
                                                <span className='nb'>0</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="" className='mess'>
                                                <i className='fa fa-envelope'></i>
                                                <span className='nb'>0</span>
                                            </a>
                                        </li>
                                        <li className='_dropdown'>
                                            <button className='_dropdown-toggle user-avatar'>
                                                <img src="/images/avatar.jpg" alt="" />
                                            </button>
                                            <ul className='_dropdown-menu'>
                                                <li><Link to="/moncompte">Mon profil</Link></li>
                                                <li><Link to="/moncompte/mes-demandes.html">Mes demandes</Link></li>
                                                <li><Link to="/moncompte/mes-ventes.html">Mes ventes</Link></li>
                                                <li><a href="/admin">AlloCar Admin</a></li>
                                                <li><a href="/deconnexion.html">Déconnexion</a></li>
                                            </ul>
                                        </li>
                                    </>
                                    ) }
                                </ul>
                            </div>
                        </div>
                    </header>
                    <main id="main">
                        <Switch>
                            <Route exact path="/" component={Home}/>
                            <Route path="/rechercher.html" component={Search}/>
                            <Route path="/a-propos-d-allocar.html" component={About}/>
                            <Route path="/mentions-legales.html" component={Legal}/>
                            <Route path="/contact.html" component={Contact}/>
                            <Route path="/tarifs.html" component={Tariff}/>
                            <Route path="/pros.html" component={Pros}/>
                            <Route path="/location-de-voiture.html" component={Rent}/>
                            <Route path="/achat-de-vehicule.html" component={Buy}/>
                            <Route path="/vente-de-vehicule.html" component={Sell}/>
                            <Route path="/conditions-generales-d-utilisation.html" component={Terms}/>
                            <Route path="/politique-de-confidentialite.html" component={Privacy}/>
                            <Route path="/faq.html" component={Faq}/>
                            <Route path="/moncompte" component={Account} />
                            <Route path="/demandes.html" component={Demand} />
                            <Route path="/location.html" component={Booking}/>
                            <Route><Exception code="404"/></Route>
                        </Switch>
                    </main>
                    <footer id="footer">
                        <div className="footer-top">
                            <div className="wp">
                                <div className="row">
                                    <div className="col-12 col-sm-4 col-md-3">
                                        <h2>AlloCar</h2>
                                        <ul>
                                            <li><Link to="/a-propos-d-allocar.html">À propos d'AlloCar</Link></li>
                                            <li><Link to="/mentions-legales.html">Mentions légales</Link></li>
                                            <li><Link to="/contact.html">Contact</Link></li>
                                        </ul>
                                    </div>
                                    <div className="col-12 col-sm-4 col-md-3">
                                        <h2>Plus d'infos</h2>
                                        <ul>
                                            <li><Link to="/tarifs.html">Nos tarifs</Link></li>
                                            <li><Link to="/pros.html">Professionnels</Link></li>
                                            <li><Link to="/location-de-voiture.html">Location de voiture</Link></li>
                                            <li><Link to="/achat-de-vehicule.html">Achat de véhicule</Link></li>
                                            <li><Link to="/vente-de-vehicule.html">Vente de véhicule</Link></li>
                                            <li><Link to="/conditions-generales-d-utilisation.html">Conditions générales d'utilisation</Link></li>
                                            <li><Link to="/politique-de-confidentialite.html">Politique de confidentialité</Link></li>
                                            <li><a href="/blog">Blog</a></li>
                                        </ul>
                                    </div>
                                    <div className="col-12 col-sm-4 col-md-3">
                                        <h2>Aide</h2>
                                        <ul>
                                            <li><Link to="/faq.html">FAQ</Link></li>
                                        </ul>
                                    </div>
                                    <div className="col-12 col-sm-4 col-md-3">
                                        <h2>Nos applications</h2>
                                        <ul>
                                            <li>
                                                <ul className="apps">
                                                    <li><a href="" target="_blank"><img src="/images/google-play.png" alt=""/></a></li>
                                                    <li><a href="" target="_blank"><img src="/images/apple-app-store.png" alt=""/></a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="footer-bottom">
                            <div className='wp d-flex align-items-center justify-content-between'>
                                <div className='fb-left'>
                                    &copy; Copyright { this.state.date.getFullYear() } AlloCar. Tous droits réservés. <br/>Un site web de la société <a href="https://www.softlab.mg" target="_blank">Softlab</a> SARLU.
                                </div>
                                <div className='fb-right'>
                                    <ul className="media-social">
                                        <li>Suivez-nous :</li>
                                        <li><a href="" target="_blank"><i className="fab fa-facebook-f"></i></a></li>
                                        <li><a href="" target="_blank"><i className="fab fa-twitter"></i></a></li>
                                        <li><a href="" target="_blank"><i className="fab fa-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </footer>
                </BrowserRouter>
            </Context.Provider>
            )
        )
    }
}

ReactDOM.render(<App />, document.getElementById('root'))