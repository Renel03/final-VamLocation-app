import axios from 'axios'
import React, { Component } from 'react'
import Helmet from 'react-helmet'
import { Link } from 'react-router-dom'
import Select from 'react-select'
import Context from '../contexts/Context'
import Aside from './elements/Aside'

function _dateFormat(date){
    let day = (new Date(date)).getDay()
    let dateTemp = (String(date)).split('T')[0].split('-')
    let days = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.']
    let months = ['jav.', 'fév.', 'mar.', 'avr.', 'mai', 'juin', 'juil.', 'aoû.', 'sept.', 'oct.', 'nov.', 'déc.']
    return days[day] + " " + dateTemp[2] + " " + months[parseInt(dateTemp[1]) - 1] + " " + dateTemp[0]
}

export default class Booking extends Component {
    constructor(){
        super()
        this.state = {
            demandTemp: null,
            loading: true,
            loadingCities: true,
            step: 1,
            isWithDriver: false,
            luggage: "",
            reason: "",
            description: "",
            tripType3Cities: [],
            firstname: "",
            lastname: "",
            email: "",
            password: "",
            phone: "",
            isAgree: false,
            isNewUser: 1,
            isFormSend: false,
            isAuth: 0,
            cities: [],
            messages: []
        }

        this.changeStep = this.changeStep.bind(this)
        this.handleChange = this.handleChange.bind(this)
        this.onSubmitForm = this.onSubmitForm.bind(this)
    }

    onSubmitForm(e){
        e.preventDefault()
        this.setState({
            isFormSend: true
        })
        const {
            isWithDriver,
            luggage,
            reason,
            description,
            tripType3Cities,
            firstname,
            lastname,
            email,
            password,
            phone,
            isNewUser,
            isAuth
        } = this.state

        let formData = new FormData()
        formData.append('rent[tripType3Cities]', tripType3Cities.length > 0 ? tripType3Cities : "" )
        formData.append('rent[isWithDriver]', isWithDriver)
        formData.append('rent[luggage]', luggage)
        formData.append('rent[reason]', reason)
        formData.append('rent[description]', description)

        formData.append('rent[firstname]', firstname)
        formData.append('rent[lastname]', lastname)
        formData.append('rent[phone]', phone)
        formData.append('rent[isNewUser]', isNewUser)
        formData.append('rent[isAuth]', isAuth)
        formData.append('rent[email]', email)
        formData.append('rent[plainPassword]', password)

        axios   .post(`/api/v1/booking/2`, formData)
                .then(({ data }) => {
                    if(data.success == false){
                        this.setState({
                            messages: data.messages,
                        })
                    }else{
                        window.location.href = `/profil.html`
                    }
                })
                .catch((err) => {
                    console.log(err.response.data)
                })
                .finally(() => {
                    this.setState({
                        isFormSend: false,
                        password: ""
                    })
                })
    }

    handleChange(e) {
        const target = e.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;
        this.setState({
            [name]: value    
        });
    }

    changeStep(i){
        let stp = this.state.step + i
        this.setState({
            step: stp
        })
    }

    isLoading(){
        return this.state.loading || this.state.loadingCities
    }

    componentDidMount(){
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')
        const { user } = this.context
        this.loadDemandTemp()
        this.loadCitiesOptions()
        this.setState({
            isAuth: user != null ? 1 : 0
        })
    }
    
    componentDidUpdate(prevProps){
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
            const { user } = this.context
            this.setState({
                isAuth: user != null ? 1 : 0
            })
        }
    }

    async loadDemandTemp(){
        const { match: { params } } = this.props
        await axios     .get('/api/v1/load-demand-temp')
                        .then(({ data }) => {
                            if(data.demand == null){
                                this.props.history.push(`/`)
                            }else{
                                this.setState({
                                    demandTemp: data.demand,
                                })
                            }
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

    async loadCitiesOptions(){
        await axios .get('/api/v1/cities/options')
                    .then(({ data: data}) => {
                        this.setState({
                            cities: data,
                        })
                    })
                    .catch((err) => {
                        console.log(err.response.data)
                    })
                    .finally(() => {
                        this.setState({
                            loadingCities: false,
                        })
                    })
    }

    render() {
        const { user } = this.context
        return (
            this.isLoading() ? (
                <div className='loader'></div>
            ) : (
            <>
                { this.state.isFormSend && <div className='loader'></div> }
                <Helmet>
                    <title>Location de voiture - AlloCar</title>
                    <meta name="robots" content="noindex,nofollow" />
                </Helmet>

                <section className="section pt-60 pb-40">
                    <div className='progressBar'>
                        <div className='wp'>
                            <div className='row'>
                                <div className='col-12 col-lg-9'>
                                    <ul className='steps d-flex align-items-start'>
                                        <li className='step active' data-i="1"><span>Trajets, dates, passagers</span></li>
                                        <li className={ `step active${ this.state.step == 1 ? ` current` : `` }` } data-i="2"><span>Estimation, infos complémentaires</span></li>
                                        <li className={ `step${ this.state.step == 2 ? ` active current` : `` }${ this.state.step == 3 ? ` active` : `` }` } data-i="3"><span>Coordonnées et compte</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className='wp'>
                        <div className='row'>
                            <div className='col-12 col-md-4 col-lg-3'>
                                <div className='demand-infos'>
                                    <div className='demand-infos-item'>
                                        <h6><span className='demand-infos-icon'><i className='fa fa-map-marker-alt'></i></span> Trajet</h6>
                                        <p><span><u>Type</u> :</span> { this.state.demandTemp.tripType == 1 ? "Aller simple" : (this.state.demandTemp.tripType == 2 ? "Aller-retour" : "Circuit") }</p>
                                        <p>
                                            <span><u>De</u> :</span> { this.state.demandTemp.fromCity.name } ({ this.state.demandTemp.fromCity.cp })
                                            { this.state.demandTemp.tripType != 3 && 
                                                <>
                                                    <br/><span><u>Destination</u> :</span> { this.state.demandTemp.toCity.name } ({ this.state.demandTemp.toCity.cp })
                                                </>
                                            }
                                        </p>
                                    </div>
                                    <hr/>
                                    <div className='demand-infos-item'>
                                        <h6><span className='demand-infos-icon'><i className='fa fa-calendar'></i></span> Dates</h6>
                                        <p>
                                            <span><u>Départ</u> :</span> { _dateFormat(this.state.demandTemp.checkInAt) }
                                            { this.state.demandTemp.tripType != 1 && 
                                            <>
                                                <br/><span><u>Retour</u> :</span> { _dateFormat(this.state.demandTemp.checkOutAt) }
                                            </>
                                            }
                                        </p>
                                    </div>
                                    <hr/>
                                    <div className='demand-infos-item'>
                                        <h6><span className='demand-infos-icon'><i className='fa fa-users'></i></span> Nombre de passagers</h6>
                                        <p>{ this.state.demandTemp.nbPers == 1 ? "Une personne" : this.state.demandTemp.nbPers + " personnes"  } </p>  
                                    </div>
                                </div>
                            </div>
                            <div className='col-12 col-md-8 col-lg-6'>
                                <form className='form pt-20' onSubmit={ this.onSubmitForm }>
                                    <div className={ `step-content${ this.state.step == 1 ? ` active` : `` }` }>
                                        <h6>Estimation</h6>
                                        <p>Prix estimé à partir de <span className='estimate-price'>2 000 000 Ar</span> (soit 400 000 Ar)</p>
                                        <div className='estimate-index'>Indice de confiance <span className='estimate-help'><i className='fa fa-question'></i><abbr>Indice de confiance : du plus faible (1 vert) au plus élevé (3 verts). Il reflète le nombre d'historiques de devis et d'informations dont dispose AlloCar pour réaliser son estimation.</abbr></span> : <span className='estimate-dots dot-1 _dot-2 _dot-3' title='Faible'><span></span></span></div>
                                        <br/>
                                        <h6>Autres infos</h6>
                                        { this.state.demandTemp.tripType == 3 && <div className='form-group'>
                                            <label>Sélectionnez les autres villes...</label>
                                            <Select placeholder="Code postal, ville, etc..." options={ this.state.cities } isMulti className="select-multiple" required defaultValue={ this.state.tripType3Cities } onChange={ (val) => {
                                                let tab = []
                                                val.map(elm => {
                                                    tab.push(elm.value)
                                                })
                                                this.setState({
                                                    tripType3Cities: tab
                                                })
                                            } }/>
                                            { this.state.messages.tripType3Cities && <div className="form-error">{ this.state.messages.tripType3Cities }</div> }
                                        </div> }
                                        <div className='form-group'>
                                            <label>
                                                <input type="checkbox" name="isWithDriver" value={ this.state.isWithDriver } onChange={ this.handleChange }/>
                                                <span></span>
                                                J'ai besoin de chauffeur
                                            </label>
                                            { this.state.messages.isWithDriver && <div className="form-error">{ this.state.messages.isWithDriver }</div> }
                                        </div>
                                        <div className='form-group'>
                                            <label htmlFor='luggage' className='required'>Bagages</label>
                                            <select name="luggage" id="luggage" defaultValue={ this.state.luggage } onChange={ this.handleChange }>
                                                <option value=""></option>
                                                <option value="1">Valises classiques</option>
                                                <option value="2">Grandes valises</option>
                                                <option value="3">Pas de bagage</option>
                                                <option value="4">Bagages volumieux</option>
                                            </select>
                                            { this.state.messages.luggage && <div className="form-error">{ this.state.messages.luggage }</div> }
                                        </div>
                                        <div className='form-group'>
                                            <label htmlFor='reason' className='required'>Motif de voyage</label>
                                            <select name="reason" id="reason" defaultValue={ this.state.reason } onChange={ this.handleChange }>
                                                <option value=""></option>
                                                <option value="1">Voyage d'affaire</option>
                                                <option value="2">Voyage sportive</option>
                                                <option value="3">Voyage vacance</option>
                                                <option value="4">Autres</option>
                                            </select>
                                            { this.state.messages.reason && <div className="form-error">{ this.state.messages.reason }</div> }
                                        </div>
                                        <br/>
                                        <h6>Décrivez en quelques mots votre voyage</h6>
                                        <div className='form-group'>
                                            <textarea name='description' defaultValue={ this.state.description } onChange={ this.handleChange }></textarea>
                                            { this.state.messages.description && <div className="form-error">{ this.state.messages.description }</div> }
                                        </div>
                                        <div className='pt-20 d-flex align-items-center'>
                                            <button type="button" disabled={ this.state.demandTemp.tripType == 3 && this.state.tripType3Cities.length == 0 || this.state.luggage == "" || this.state.reason == "" } className='btn btn-primary mr-4' onClick={ () => { this.changeStep(1) } }>Etape suivante</button>
                                            <Link to="/" className='btn_link'>Retour à l'étape précédente</Link>
                                        </div>
                                    </div>
                                    <div className={ `step-content${ this.state.step == 2 ? ` active` : `` }` }>
                                        <h6>Coordonnées et compte</h6>
                                        { this.state.isAuth == 1 ? (
                                        <>
                                            <div className='user-auth'>
                                                <div className='user-auth-avatar'>
                                                    <img src="/images/avatar.jpg" alt="" />
                                                </div>
                                                { user.lastname != null && user.firstname != null && <h3>{ user.lastname } { user.firstname }</h3> }
                                                <p>{ user.email }</p>
                                            </div>
                                            <div className='form-group pt-20 d-flex align-items-center'>
                                                <button type="submit" className='btn mr-4 btn-primary'>Continuer &gt;</button>
                                                <Link to="" className='btn_link' onClick={ (e) => { e.preventDefault(); this.changeStep(-1) } }>Retour à l'étape précédente</Link>
                                            </div>
                                            <div className='text-center'>
                                                <span style={{ fontSize: "16px" }}>Ce n'est pas vous ? <a href="" onClick={ (e) => {
                                                    e.preventDefault()
                                                    this.setState({
                                                        isNewUser: 1,
                                                        isAuth: 0
                                                    })
                                                } }>Créez un compte</a></span>
                                            </div>
                                        </>
                                        ) : (
                                        this.state.isNewUser == 1 ? (
                                        <>
                                            <div className='row'>
                                                <div className='form-group col-12 col-sm-6'>
                                                    <label className='required'>Nom</label>
                                                    <input type="text" name="lastname" required defaultValue={ this.state.lastname } onChange={ this.handleChange } />
                                                    { this.state.messages.lastname && <div className="form-error">{ this.state.messages.lastname }</div> }
                                                </div>
                                                <div className='form-group col-12 col-sm-6'>
                                                    <label className='required'>Prénom</label>
                                                    <input type="text" name="firstname" required defaultValue={ this.state.firstname } onChange={ this.handleChange }/>
                                                    { this.state.messages.firstname && <div className="form-error">{ this.state.messages.firstname }</div> }
                                                </div>
                                            </div>
                                            <div className='form-group'>
                                                <label className='required'>Téléphone</label>
                                                <input type="text" name="phone" required defaultValue={ this.state.phone } onChange={ this.handleChange }/>
                                            </div>
                                            <div className='form-group'>
                                                <label className='required'>Adresse e-mail</label>
                                                <input type="email" name="email" required defaultValue={ this.state.email } onChange={ this.handleChange } />
                                                { this.state.messages.email && <div className="form-error">{ this.state.messages.email }</div> }
                                            </div>
                                            <div className='form-group'>
                                                <label className='required'>Mot de passe</label>
                                                <div className="position-relative">
                                                    <input type="password" name="password" required id="password" defaultValue={ this.state.password } onChange={ this.handleChange }/>
                                                    <button type="button" className="show-password" data-id="#password"><i className="fa fa-eye"></i></button>
                                                </div>
                                                { this.state.messages.plainPassword && <div className="form-error">{ this.state.messages.plainPassword }</div> }
                                            </div>
                                            <div className='form-group'>
                                                <label>
                                                    <input type="checkbox" name="isAgree" value={ this.state.isAgree } onChange={ this.handleChange }/>
                                                    <span></span>
                                                    En soumettant ce formulaire j'accepte  les <a href="">conditions gnérales d'utilisation</a> et la <a href="">politique de confidentialité</a> d'AlloCar.
                                                </label>
                                            </div>
                                            <div className='form-group pt-20 d-flex align-items-center'>
                                                <button type="submit" className='btn btn-primary mr-4' disabled={ (this.state.isNewUser == 1 && (this.state.lastname == "" || this.state.firstname == "" || this.state.phone == "")) || this.state.email == "" || this.state.password == '' || this.state.isAgree == false }>Valider &gt;</button>
                                                <Link to="" className='btn_link' onClick={ (e) => { e.preventDefault(); this.changeStep(-1) } }>Retour à l'étape précédente</Link>
                                            </div>
                                            <div className='text-center'>
                                                <span style={{ fontSize: "16px" }}>Vous avez déjà un compte chez AlloCar ? <a href="" onClick={ (e) => {
                                                    e.preventDefault()
                                                    this.setState({
                                                        isNewUser: 0
                                                    })
                                                } }>Connectez-vous</a></span>
                                            </div>
                                        </>
                                        ) : (
                                        <>
                                            <div className='form-group'>
                                                <label className='required'>Adresse e-mail</label>
                                                <input type="email" name="email" required defaultValue={ this.state.email } onChange={ this.handleChange } />
                                                { this.state.messages.email && <div className="form-error">{ this.state.messages.email }</div> }
                                            </div>
                                            <div className='form-group'>
                                                <label className='required'>Mot de passe</label>
                                                <div className="position-relative">
                                                    <input type="password" name="password" required id="password2" defaultValue={ this.state.password } onChange={ this.handleChange }/>
                                                    <button type="button" className="show-password" data-id="#password2"><i className="fa fa-eye"></i></button>
                                                </div>
                                                { this.state.messages.plainPassword && <div className="form-error">{ this.state.messages.plainPassword }</div> }
                                            </div>
                                            <div className='form-group pt-20 d-flex align-items-center'>
                                                <button type="submit" className='btn mr-4 btn-primary' disabled={ this.state.email == "" || this.state.password == '' }>Valider &gt;</button>
                                                <Link to="" className='btn_link' onClick={ (e) => { e.preventDefault(); this.changeStep(-1) } }>Retour à l'étape précédente</Link>
                                            </div>
                                            <div className='text-center'>
                                                <span style={{ fontSize: "16px" }}>Vous n'avez pas un compte chez AlloCar ? <a href="" onClick={ (e) => {
                                                    e.preventDefault()
                                                    this.setState({
                                                        isNewUser: 1
                                                    })
                                                } }>Créez un compte</a></span>
                                            </div>
                                        </>
                                        )) }
                                    </div>
                                </form>
                            </div>
                            <div className='col-12 col-lg-3 pt-20'>
                                <Aside />
                            </div>
                        </div>
                    </div>
                </section>
            </>
            )
        )
    }
}

Booking.contextType = Context