import React, { Component } from 'react'
import { Link } from 'react-router-dom'
import Helmet from 'react-helmet'
import Select from 'react-select'
import axios from 'axios'
import Slider from "react-slick"
import Context from '../contexts/Context'
import _Post from './elements/_Post'
import _Demand from './elements/_Demand'
import '../styles/slick.css'
import 'react-day-picker/lib/style.css'
import DayPickerInput from 'react-day-picker/DayPickerInput'
import moment from 'moment'
import { formatDate, parseDate } from 'react-day-picker/moment'


export default class Home extends Component {
    constructor(){
        super()
        this.state = {
            loadingDemandTemp: true,

            rentTripType: "1",
            rentFromLabel: "",
            rentFrom: "",
            rentFromError: "",
            rentToLabel: "",
            rentTo: "",
            rentToError: "",
            rentCheckInDate: undefined,
            rentCheckOutDate: undefined,
            rentDateFromError: "",
            rentDateToError: "",
            rentNbPers: "",
            rentMessages: [],

            searchType: "1",
            searchFuel: "",
            searchTrademark: "",
            searchModel: "",
            searchPriceMax: "",

            trademarkOptions: [],
            modelOptions: [],
            
            cities: [],
            loadingCities: true,
            
            demands: [],
            loadingDemands: true,
            
            loading: false
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmitRentForm = this.handleSubmitRentForm.bind(this)
        this.handleSubmitBuyForm = this.handleSubmitBuyForm.bind(this)
        this.handleFromChange = this.handleFromChange.bind(this)
        this.handleToChange = this.handleToChange.bind(this)
    }

    isLoading(){
        return this.state.loadingCities || this.state.loadingDemandTemp || this.state.loadingDemands
    }

    showFromMonth() {
        const { rentCheckInDate, rentCheckOutDate } = this.state
        if(!rentCheckInDate) {
            return
        }
        if(moment(rentCheckOutDate).diff(moment(rentCheckInDate), 'months') < 2) {
            this.rentCheckOutDate.getDayPicker().showMonth(rentCheckInDate)
        }
    }
    
    handleFromChange(rentCheckInDate) {
        this.setState({ rentCheckInDate })
    }
    
    handleToChange(rentCheckOutDate) {
        this.setState({ rentCheckOutDate }, this.showFromMonth())
    }

    handleSubmitRentForm(e) {
        e.preventDefault()

        this.setState({
            rentFromError: "",
            rentToError: "",
            rentDateFromError: "",
            rentDateToError: "",
            rentMessages: []
        })

        const {
            rentTripType,
            rentFrom,
            rentTo,
            rentCheckInDate,
            rentCheckOutDate,
            rentNbPers
        } = this.state

        let rentFromErrorTemp = null
        let rentToErrorTemp = null
        let rentDateFromErrorTemp = null
        let rentDateToErrorTemp = null

        if(rentFrom == ""){
            rentFromErrorTemp = "D'où partez-vous ?"
            this.setState({
                rentFromError: rentFromErrorTemp
            })
        }
        
        if(rentTripType != "3" && rentTo == ""){
            rentToErrorTemp = 'Où allez-vous ?'
            this.setState({
                rentToError: rentToErrorTemp
            })
        }
        
        if(rentCheckInDate == undefined || rentCheckInDate == ""){
            rentDateFromErrorTemp = 'Saisissez la date de départ'
            this.setState({
                rentDateFromError: rentDateFromErrorTemp
            })
        }
        
        if(rentTripType != "1" && (rentCheckOutDate == "" || rentCheckOutDate == undefined)){
            rentDateToErrorTemp = 'Saisissez la date de retour'
            this.setState({
                rentDateToError: rentDateToErrorTemp
            })
        }

        if(rentFromErrorTemp != null || rentToErrorTemp != null || rentDateFromErrorTemp != null || rentDateToErrorTemp != null){
            return false
        }
        
        let formData = new FormData()
        formData.append("rent[tripType]", rentTripType)
        formData.append("rent[fromCity]", rentFrom)
        formData.append("rent[checkInAt]", rentCheckInDate.getDate() + "/" +  (rentCheckInDate.getMonth() + 1) + "/" + rentCheckInDate.getFullYear())
        formData.append("rent[nbPers]", rentNbPers)
        if(rentTripType != "3"){
            formData.append("rent[toCity]", rentTo)     
        }
        if(rentTripType != "1"){
            formData.append("rent[checkOutAt]", rentCheckOutDate.getDate() + "/" +  (rentCheckOutDate.getMonth() + 1) + "/" + rentCheckOutDate.getFullYear())
        }
        if(rentTripType == "3"){
            formData.append("rent[toCity]", "")     
        }
        if(rentTripType == "1"){
            formData.append("rent[checkOutAt]", "")
        }

        this.setState({
            loading: true
        })

        axios   .post('/api/v1/booking/1', formData)
                .then(({ data }) => {
                    if(data.success == false){
                        this.setState({
                            rentMessages: data.messages
                        })
                    }else{
                        this.props.history.push(`/location.html`)
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
    
    handleSubmitBuyForm(e) {
        e.preventDefault()
        let q = ""
        const {
            searchType,
            searchFuel,
            searchTrademark,
            searchModel,
            searchPriceMax
        } = this.state

        if(searchType != '')
            q += "search[type]=" + searchType
        if(searchFuel != '')
            q += (q != '' ? '&' : '') + "search[fuel]=" + searchFuel
        if(searchTrademark != '')
            q += (q != '' ? '&' : '') + "search[trademark]=" + searchTrademark
        if(searchModel != '')
            q += (q != '' ? '&' : '') + "search[model]=" + searchModel
        if(searchPriceMax != '')
            q += (q != '' ? '&' : '') + "search[priceMax]=" + searchPriceMax

        if(q != '')
            q = "?" + q

        this.props.history.push(`/rechercher.html${ q }`)
    }

    handleChange(e) {
        const target = e.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;
        this.setState({
            [name]: value    
        });
    }
    
    componentDidMount(){
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.remove('is-sticky')
        this.loadCitiesOptions()
        this.loadDemandTemp()
        this.fetchDemands()
        this.loadTrademarkOptions(this.state.searchType)
    }
    
    componentDidUpdate(prevProps){
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            document.getElementsByTagName("body")[0].classList.remove('is-sticky')
            window.scrollTo(0, 0)
        }
    }

    async loadDemandTemp(){
        await axios     .get('/api/v1/load-demand-temp')
                        .then(({ data }) => {
                            let demand = data.demand
                            let checkInAt = demand && demand.checkInAt ? new Date(demand.checkInAt) : undefined
                            let checkOutAt = demand && demand.checkOutAt ? new Date(demand.checkOutAt) : undefined
                            this.setState({
                                rentTripType: demand && demand.tripType ? demand.tripType : "1",
                                rentFromLabel: demand && demand.fromCity ? demand.fromCity.name + " " + demand.fromCity.cp : "",
                                rentFrom: demand && demand.fromCity ? demand.fromCity.id : "",
                                rentToLabel: demand && demand.toCity ? demand.toCity.name + " " + demand.toCity.cp : "",
                                rentTo: demand && demand.toCity ? demand.toCity.id : "",
                                rentCheckInDate: checkInAt,
                                rentCheckOutDate: checkOutAt,
                                rentNbPers: demand && demand.nbPers ? demand.nbPers : "",
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {
                            this.setState({
                                loadingDemandTemp: false,
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

    async loadTrademarkOptions(val){
        await axios     .get(`/api/v1/trademarks/options/${ val }`)
                        .then(({ data }) => {
                            this.setState({
                                trademarkOptions: data
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {})
    }
    
    async loadModelOptions(val){
        await axios     .get(`/api/v1/models/options/${ val }`)
                        .then(({ data }) => {
                            this.setState({
                                modelOptions: data
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {})
    }

    async fetchDemands(){
        axios   .get('/api/v1/demands?limit=10')
                .then(({ data : data }) => {
                    this.setState({
                        demands: data.data,
                    })
                })
                .catch((err) => {
                    console.log(err.response.data)
                })
                .finally(() => {
                    this.setState({
                        loadingDemands: false,
                    })
                })
    }

    render() {
        const { rentCheckInDate, rentCheckOutDate } = this.state
        const modifiers = { start: rentCheckInDate, end: rentCheckOutDate }
        const MONTHS = [
            'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre',
          ]
          const WEEKDAYS_LONG = [
            'Dimanche',
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
          ]
        const WEEKDAYS_SHORT = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']
        let postSettings = {
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 5,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 3
                    }
                },{
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 2
                    }
                },{
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        }
        
        let demandSettings = {
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 3,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 2
                    }
                },{
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        }

        return (
            this.isLoading() ? (
                <div className='loader'></div>
            ) : (
            <>
                <Helmet>
                    <title>Location de voiure, annonces de vente d'auto à Madagascar - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                    <meta name="description" content="Description..." />
                    <style>{`
                        .DayPicker-Day--selected:not(.DayPicker-Day--disabled):not(.DayPicker-Day--outside){
                            background-color:var(--color-primary)!important;color:var(--color-white)
                        }
                        .DayPicker-Day{
                            border-radius:0!important
                        }
                        .DayPicker-Day--start{
                            border-top-left-radius:50%!important;border-bottom-left-radius:50%!important
                        }
                        .DayPicker-Day--end{
                            border-top-right-radius:50%!important;border-bottom-right-radius:50%!important
                        }
                        .DayPickerInput-Overlay{
                            width:550px
                        }
                        .InputFromTo .DayPickerInput-Overlay {
                            margin-left:-8px
                        }
                        .InputFromTo-to .DayPickerInput-Overlay {
                            margin-left:-198px
                        }
                    `}</style>
                </Helmet>
                <section className="banner sticky">
                    <div className="wp">
                        <div className="banner-content">
                            <h1 className='banner-title'>
                                AlloCar vous accompagne à :<br/>
                                <span className='ticker'>
                                    <ul>
                                        <li>trouver une voiture à louer</li>
                                        <li>vendre une voiture ou un moto</li>
                                        <li>trouver une voiture à meilleur prix</li>
                                    </ul>
                                </span>
                                à Madagascar
                            </h1>
                            <p>Trouvez la bonne affaire parmi 26 926 428 petites annonces sur AlloCar.</p>
                            <ul className="d-flex align-items-end tab-search">
                                <li className="active tab-search-item" data-id="#rent">Location de voiture</li>
                                <li className="tab-search-item" data-id="#buy">Achat d'automobile</li>
                            </ul>
                            <form className="tab active _form" id="rent" onSubmit={ this.handleSubmitRentForm }>
                                <div className="_form-group" style={{ position: "relative", zIndex: "5" }}>
                                    <div className="label __label">
                                        <div className='d-flex'>
                                            <label className={ this.state.rentTripType == "1" ? 'active' : '' }>
                                                <input type="radio" required name="rentTripType" value="1" defaultChecked={ this.state.rentTripType == "1" } onChange={ (e) => {
                                                    this.handleChange(e)
                                                    this.setState({
                                                        rentCheckOutDate: undefined,
                                                    })
                                                } }/>
                                                <i></i>
                                                Aller simple
                                            </label>
                                            <label className={ this.state.rentTripType == "2" ? 'active' : '' }>
                                                <input type="radio" required name="rentTripType" value="2" defaultChecked={ this.state.rentTripType == "2" } onChange={ this.handleChange }/>
                                                <i></i>
                                                Aller-retour
                                            </label>
                                            <label className={ this.state.rentTripType == "3" ? 'active' : '' }>
                                                <input type="radio" required name="rentTripType" value="3" defaultChecked={ this.state.rentTripType == "3" } onChange={ this.handleChange }/>
                                                <i></i>
                                                Circuit
                                            </label>
                                        </div>
                                    </div>
                                    { this.state.rentMessages.tripType && <div className="_form-error">{ this.state.rentMessages.tripType }</div> }
                                </div>
                                <div className='row row-small align-items-start'>
                                    <div className='col-12 col-sm-8 col-md-9'>
                                        <div className="row row-small align-items-start">
                                            <div className={`${ this.state.rentTripType == '3' ? "col-12 col-sm-12 col-md-6" : "col-12 col-sm-6 col-md-3" }`} style={{ position: "relative", zIndex: "4" }}>
                                                <label className="label">
                                                    <Select name="rentFrom" placeholder={ this.state.rentFromLabel != "" ? this.state.rentFromLabel : "D'où partez-vous ?" } options={ this.state.cities } required defaultValue={ this.state.rentFrom } onChange={ (val) => {
                                                        this.setState({
                                                            rentFrom: val.value
                                                        })
                                                    } } />
                                                </label>
                                                { this.state.rentFromError != "" && <div className="_form-error">{ this.state.rentFromError }</div> }
                                                { this.state.rentMessages.fromCity && <div className="_form-error">{ this.state.rentMessages.fromCity }</div> }
                                            </div>
                                            <div className={`${ this.state.rentTripType == '3' ? 'd-none' : 'col-12 col-sm-6 col-md-3' }`} style={{ position: "relative", zIndex: "3" }}>
                                                <label className={`label`}>
                                                    <Select name="rentTo" id="rentTo" placeholder={ this.state.rentToLabel != "" ? this.state.rentToLabel : "Où allez-vous ?" } required={ this.state.rentTripType != '3' } isDisabled={ this.state.rentTripType == '3' } options={ this.state.cities } required defaultValue={ this.state.rentTo } onChange={ (val) => {
                                                        this.setState({
                                                            rentTo: val.value
                                                        })
                                                    } } />
                                                </label>
                                                { this.state.rentToError != "" && <div className="_form-error">{ this.state.rentToError }</div> }
                                                { this.state.rentMessages.toCity && <div className="_form-error">{ this.state.rentMessages.toCity }</div> }
                                            </div>
                                            <div className="col-12 col-sm-6 col-md-3" style={{ position: "relative", zIndex: "2" }}>
                                                <div className={`row row-date row-small align-items-start ${ this.state.rentTripType == '1' ? '' : 'row-full' }`}>
                                                    <div className={`${ this.state.rentTripType == '1' ? 'col-12' : 'col-6' }`} style={{ position: "relative", zIndex: "2" }}>
                                                        <div className="label InputFromTo">
                                                            <DayPickerInput
                                                                value={rentCheckInDate}
                                                                placeholder="Dép. (date)"
                                                                format="ddd DD/MM"
                                                                formatDate={ formatDate }
                                                                parseDate={ parseDate }
                                                                dayPickerProps={{
                                                                    selectedDays: [rentCheckInDate, rentCheckOutDate, { from: rentCheckInDate, to: rentCheckOutDate }],
                                                                    disabledDays: { before: new Date(), after: rentCheckOutDate },
                                                                    toMonth: rentCheckOutDate,
                                                                    modifiers: modifiers,
                                                                    numberOfMonths: 2,
                                                                    locale: "fr",
                                                                    firstDayOfWeek: 0,
                                                                    months: MONTHS,
                                                                    weekdaysLong: WEEKDAYS_LONG,
                                                                    weekdaysShort: WEEKDAYS_SHORT,
                                                                    onDayClick: () => {
                                                                        if(this.state.rentTripType != "1")
                                                                            this.rentCheckOutDate.getInput().focus()
                                                                    },
                                                                }}
                                                                onDayChange={ this.handleFromChange }
                                                            />
                                                        </div>
                                                        { this.state.rentDateFromError != "" && <div className="_form-error">{ this.state.rentDateFromError }</div> }
                                                        { this.state.rentMessages.checkInAt && <div className="_form-error">{ this.state.rentMessages.checkInAt }</div> }
                                                    </div>
                                                    <div className={`${ this.state.rentTripType == '1' ? 'd-none' : 'col-6' }`}>
                                                        <div className={`label InputFromTo-to`} style={{ position: "relative", zIndex: "1" }}>
                                                            <DayPickerInput
                                                                ref={el => (this.rentCheckOutDate = el)}
                                                                value={ rentCheckOutDate }
                                                                placeholder="Arr. (date)"
                                                                format="ddd DD/MM"
                                                                formatDate={ formatDate }
                                                                parseDate={ parseDate }
                                                                dayPickerProps={{
                                                                    selectedDays: [rentCheckInDate, rentCheckOutDate, { from: rentCheckInDate, to: rentCheckOutDate }],
                                                                    disabledDays: { before: rentCheckInDate },
                                                                    modifiers: modifiers,
                                                                    month: rentCheckInDate,
                                                                    fromMonth: rentCheckInDate,
                                                                    numberOfMonths: 2,
                                                                    locale: "fr",
                                                                    firstDayOfWeek: 0,
                                                                    months: MONTHS,
                                                                    weekdaysLong: WEEKDAYS_LONG,
                                                                    weekdaysShort: WEEKDAYS_SHORT
                                                                }}
                                                                disabled={ this.state.rentTripType == '1' }
                                                                onDayChange={ this.handleToChange }
                                                            />
                                                        </div>
                                                        { this.state.rentDateToError != "" && <div className="_form-error">{ this.state.rentDateToError }</div> }
                                                        { this.state.rentMessages.checkOutAt && <div className="_form-error">{ this.state.rentMessages.checkOutAt }</div> }
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="col-12 col-sm-4 col-md-3">
                                                <label className="label">
                                                    <input type="number" min="1" placeholder="Nombre de voyageurs" max="99" required name="rentNbPers" autoComplete="off" value={ this.state.rentNbPers } onChange={ this.handleChange }/>
                                                </label>
                                                { this.state.rentMessages.nbPers && <div className="_form-error">{ this.state.rentMessages.nbPers }</div> }
                                            </div>
                                        </div>
                                    </div>
                                    <div className='col-12 col-md-3'>
                                        <button type="submit" disabled={ this.state.loading || this.state.rentTripType == "" || this.state.rentFrom == "" || this.state.rentCheckInDate == "" || this.state.rentNbPers == "" }>C'est parti !</button>
                                    </div>
                                </div>
                            </form>
                            <form className="tab _form" id="buy" onSubmit={ this.handleSubmitBuyForm }>
                                <div className="_form-group">
                                    <div className="label __label">
                                        <div className='d-flex'>
                                            <label className={ this.state.searchType == "1" ? 'active' : '' }>
                                                <input type="radio" required name="searchType" value="1" defaultChecked={ this.state.searchType == "1" } onChange={ (e) => { 
                                                    this.handleChange(e)
                                                    let val = e.target.value
                                                    if(val == ""){
                                                        this.setState({
                                                            trademarkOptions: [],
                                                            modelOptions: []
                                                        })
                                                    }else{
                                                        this.loadTrademarkOptions(val)
                                                        this.setState({
                                                            modelOptions: []
                                                        })
                                                    }
                                                } }/>
                                                <i></i>
                                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M4 13.343c1.164 0 2.106.942 2.106 2.106 0 .043-.003.084-.005.125a2.214 2.214 0 0 1-.058.382 2.106 2.106 0 0 1-4.142-.32c-.005-.062-.011-.123-.011-.187l.002-.004A2.106 2.106 0 0 1 4 13.343zm14.971 0c1.164 0 2.107.942 2.107 2.106 0 .043-.003.084-.005.125a2.248 2.248 0 0 1-.058.382 2.107 2.107 0 0 1-4.142-.32c-.005-.062-.011-.123-.011-.187l.002-.004a2.106 2.106 0 0 1 2.107-2.102zM4 14.253c-.66 0-1.195.534-1.196 1.194 0 0-.002 0-.002.002 0 .036.003.07.007.106a1.196 1.196 0 0 0 2.351.182.762.762 0 0 0 .02-.108.973.973 0 0 0 .013-.11c0-.022.003-.046.003-.07 0-.66-.535-1.195-1.196-1.195zm14.972 0c-.66 0-1.196.534-1.197 1.194v.002c0 .036.003.07.006.106a1.196 1.196 0 0 0 2.372.074c.005-.036.01-.072.012-.11 0-.022.003-.046.003-.07 0-.66-.535-1.195-1.196-1.195zM9.497 6.447h.356c4.367.042 7.89 2.989 8.027 3.118l.096.09c.174.083.722.283 2.397.613 2.375.468 2.828 1.223 2.912 1.517.19.14.498.478.546.875.04.315.127.445.127.445l.042.052v2.235l-.004.04c-.129.594-2.006.831-2.227.827.022-.062.511-3.593-2.746-3.651-3.3-.06-2.918 3.478-2.897 3.54-3.113-.066-6.154-.095-9.22-.173.014-.062.26-3.317-2.734-3.427-3.08-.113-3.102 3.012-3.093 3.08-.586-.096-.57-.236-.824-.735-.436-.852-.213-2.007-.058-2.252.084-.133.127-.651.164-1.109.034-.405.072-.866.145-1.262.052-.277.164-.645.543-.645.04 0 .078.004.115.007.025.002.05.005.087.005 0 0 .013-.01.03-.057.11-.32.58-.719 1.182-1.205.148-.12.326-.263.4-.338-.028-.193-.022-.427.11-.59a.47.47 0 0 1 .38-.166c.031 0 .062.002.132.008.146 0 .438-.06.84-.142 1.039-.214 2.628-.727 5.528-.7zm.565.772l.06 2.47s5.245.133 5.944.104c-.898-2.314-6.004-2.574-6.004-2.574zm-.972-.01h-.082c-.44.004-2.562.062-3.818.87l-.015 1.557 3.945.045-.03-2.472z"></path></g></svg> Voiture
                                            </label>
                                            <label className={ this.state.searchType == "2" ? 'active' : '' }>
                                                <input type="radio" required name="searchType" value="2" defaultChecked={ this.state.searchType == "2" } onChange={ (e) => { 
                                                    this.handleChange(e)
                                                    let val = e.target.value
                                                    if(val == ""){
                                                        this.setState({
                                                            trademarkOptions: [],
                                                            modelOptions: []
                                                        })
                                                    }else{
                                                        this.loadTrademarkOptions(val)
                                                        this.setState({
                                                            modelOptions: []
                                                        })
                                                    }
                                                } }/>
                                                <i></i>
                                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M1.282 15.498a2.595 2.595 0 0 1-.08-.613c0-.959.533-1.785 1.307-2.237l.427 1.038c-.214.16-.4.347-.508.614-.106.239-.132.506-.106.772l-1.04.426zm1.28.213c.16.214.347.4.613.507.24.106.507.133.774.106l.426 1.04c-.186.053-.4.08-.613.08-.96 0-1.787-.533-2.24-1.306l1.04-.427zm2.054.4l1.546-.4a2.458 2.458 0 0 1-1.147 1.386l-.4-.986zM3.575 13.42l-.426-1.04c.186-.053.399-.08.613-.08.613 0 1.174.214 1.653.587l-1.84.533zm14.8 3.144a2.334 2.334 0 0 1-.508-1.332l1.146.16a.972.972 0 0 0 .16.373l-.799.8zm1.226-.32c.24.134.48.214.772.214.294 0 .56-.08.774-.213l.8.799c-.426.346-.96.534-1.546.534-.56 0-1.12-.188-1.6-.534l.8-.8zm2.053-.426c.133-.24.213-.48.213-.773 0-.293-.08-.56-.213-.772l.8-.8c.347.426.533.96.533 1.546 0 .586-.213 1.119-.533 1.598l-.8-.799zm-.48-2.051c-.16-.081-.293-.16-.453-.188l-.907-.532c-.24-.133-.187-.506.08-.56a3.23 3.23 0 0 1 .507-.053c.586 0 1.146.214 1.573.533l-.8.8zM3.496 18.722c2.054.16 3.813-1.332 4.054-3.277.239-.08.48-.187.772-.267v.08c-.054.48-.213.96-.506 1.36-.08.106 0 .266.133.266h8.773v-.186c.026-.32.053-.987.08-1.546h.08c.054 1.839 1.573 3.384 3.44 3.438a3.58 3.58 0 0 0 3.679-3.571c0-1.546-.96-2.826-2.293-3.305l.32-.24s-.933-.48-1.573-.426c-.32.027-.586.107-.827.214 1.52-1.066 2.507-.747 2.827-1.173.266-.347-.88-1.679-1.733-2.638-.348-.374-.8-.773-1.254-1.12.027-.133 0-.746-.532-1.065l-.294.425c-.294-.186-.48-.319-.48-.319s-1.734.613-1.493.853l.986.879s-1.04.107-1.146.426c-.08.16.08.693.08.693H16.4a.608.608 0 0 0-.187-.212c-.08-.054-.48-.214-1.013-.428-.027-.106-.08-.186-.16-.213l-.268-.08a.206.206 0 0 0-.212.054c-.853-.267-1.866-.506-2.56-.4-.24.053-1.28.16-1.866 1.492l-2.48.107c-.533-.107-1.093-.293-1.6-.613-.133-.08-.213-.053-.267 0-.026-.053-.079-.08-.107-.133-.185-.267-.453-.453-.745-.586-.907-.373-.987-.853-1.094-.853-1.786 0-3.76.693-3.52 1.28.08.16.213.266.374.319L4.189 9.13c.4.132.719.426.906.826v.026c.32.746.906 1.36 1.6 1.786l.48.293-.507.373a3.744 3.744 0 0 0-2.88-1.303030c-2.133 0-3.866 1.786-3.785 3.944.079 1.892 1.599 3.464 3.492 3.677z"></path></g></svg> Moto
                                            </label>
                                            <label className={ this.state.searchType == "3" ? 'active' : '' }>
                                                <input type="radio" required name="searchType" value="3" defaultChecked={ this.state.searchType == "3" } onChange={ (e) => { 
                                                    this.handleChange(e)
                                                    let val = e.target.value
                                                    if(val == ""){
                                                        this.setState({
                                                            trademarkOptions: [],
                                                            modelOptions: []
                                                        })
                                                    }else{
                                                        this.loadTrademarkOptions(val)
                                                        this.setState({
                                                            modelOptions: []
                                                        })
                                                    }
                                                } }/>
                                                <i></i>
                                                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M19.63 15.114c.978 0 1.782.796 1.782 1.774 0 .984-.804 1.781-1.783 1.781a1.785 1.785 0 0 1-1.783-1.781c0-.978.796-1.774 1.783-1.774zm-12.42 0a1.778 1.778 0 1 1-1.782 1.774c0-.978.796-1.774 1.781-1.774zm12.42.803a.973.973 0 0 0-.972.97c0 .546.433.972.971.972s.972-.433.972-.971a.973.973 0 0 0-.972-.971zm-12.42 0a.97.97 0 1 0-.001 1.942c.532 0 .97-.433.97-.971a.976.976 0 0 0-.97-.971zm13.224-8.894v.883c0 .058.008.116.027.17l1.232 3.685a.273.273 0 0 0 .148.162l1.833.784A.54.54 0 0 1 24 13.2v3.151c0 .296-.24.537-.537.537h-.922c-.197-.159-.331-.265-.53-.424a2.35 2.35 0 0 0-.22-.676 2.434 2.434 0 0 0-2.162-1.32 2.426 2.426 0 0 0-2.39 1.99.527.527 0 0 1-.52.43H9.675c0-.109.092-.837-.45-1.45h6.619c.297 0 .537-.24.537-.537V7.56c0-.295.24-.536.537-.536h3.515zM5.266 15.438a2.405 2.405 0 0 0-.484 1.45H2.528l-1.836-.464v-.986h4.574zM15.547 5.331v9.545H0V5.331h15.547zm4.242 3.633h-2.261v2.912h3.231l-.97-2.912z"></path></g></svg> Utilitaire
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div className="row row-small align-items-start">
                                    <div className="col-12 col-md-9">
                                        <div className='row row-small align-items-start'>
                                            <div className='col-12 col-sm-6 col-md-3'>
                                                <label className="label">
                                                    <select name="searchFuel" defaultValue={ this.state.searchFuel } onChange={ this.handleChange }>
                                                        <option value="">Energie</option>
                                                        <option value="essence">Essence</option>
                                                        <option value="diesel">Diesel</option>
                                                        <option value="electric">Electrique</option>
                                                        <option value="hybrid">Hybride</option>
                                                        <option value="other">Autre</option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div className='col-12 col-sm-6 col-md-3'>
                                                <label className="label">
                                                    <Select name="searchTrademark" placeholder="Marque" options={ this.state.trademarkOptions } required defaultValue={ this.state.searchTrademark } onChange={ (val) => {
                                                        this.setState({
                                                            searchTrademark: val.value
                                                        })
                                                        this.loadModelOptions(val.value)
                                                    } } />
                                                </label>
                                            </div>
                                            <div className='col-12 col-sm-6 col-md-3'>
                                                <label className="label">
                                                    <Select name="searchModel" placeholder="Modèle" options={ this.state.modelOptions } required defaultValue={ this.state.searchModel } onChange={ (val) => {
                                                        this.setState({
                                                            searchModel: val.value
                                                        })
                                                    } } />
                                                </label>
                                            </div>
                                            <div className='col-12 col-sm-6 col-md-3'>
                                                <label className="label">
                                                    <input type="number" min="100000" max="5000000000" placeholder="Prix Max (en Ariary)" name="searchPriceMax" autoComplete="off" value={ this.state.searchPriceMax } onChange={ this.handleChange }/>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-12 col-md-3">
                                        <button type="submit">Rechercher</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

                <section className="section bg-gray pt-70 pb-40">
                    <div className="container-fluid">
                        <h1 className="title text-center">Dernières annonces</h1>
                        <Slider {...postSettings}>
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                            <_Post />
                        </Slider>
                        <div className='pt-30 text-center'>
                            <Link to='/rechercher.html' className='btn btn-lg btn-primary'>Voir plus d'annonces</Link>
                        </div>
                    </div>
                </section>
                
                <section className="section pt-40 pb-40">
                    <div className="wp">
                        <h1 className="title text-center">Demandes de nos clients</h1>
                        <Slider {...demandSettings} className='_demands'>
                            { this.state.demands.map(demand => {
                                return(
                                    <_Demand demand={ demand } key={ demand.id }/>
                                )    
                            }) }
                        </Slider>
                        <div className='pt-30 text-center'>
                            <Link to='/rechercher.html' className='btn btn-lg btn-primary'>Voir plus de demandes</Link>
                        </div>
                    </div>
                </section>
                
                <section className="section bg-gray pt-40 pb-40">
                    <div className="wp">
                        <h1 className="title text-center">Nos dernières nouvelles</h1>
                        <div className='row _articles'>
                            <div className='col-12 col-sm-6 col-md-4'>
                                <div className='_article'>
                                    <div className='_article-media'>
                                        <a href="">
                                            <img src="/images/assurance-scooter.webp" alt=""/>
                                        </a>
                                    </div>
                                    <div className='_article-title'>
                                        <h3>
                                            <a href="">Animé par la volonté de vous assister dans votre recherche, nous avons mis ...</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div className='col-12 col-sm-6 col-md-4'>
                                <div className='_article'>
                                    <div className='_article-media'>
                                        <a href=''>
                                            <img src="/images/simulation-assurance-auto.webp" alt=""/>
                                        </a>
                                    </div>
                                    <div className='_article-title'>
                                        <h3>
                                            <a href="">Animé par la volonté de vous assister dans votre recherche, nous avons mis ...</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div className='col-12 col-sm-6 col-md-4'>
                                <div className='_article'>
                                    <div className='_article-media'>
                                        <a href=''>
                                            <img src="/images/mutuelle-famille.webp" alt=""/>
                                        </a>
                                    </div>
                                    <div className='_article-title'>
                                        <h3>
                                            <a href="">Animé par la volonté de vous assister dans votre recherche, nous avons mis ...</a>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className='pt-30 text-center'>
                            <Link to='/rechercher.html' className='btn btn-lg btn-primary'>Voir plus d'actualités</Link>
                        </div>
                    </div>
                </section>
                { this.state.loading && <div className='loader'></div> }
            </>
            )
        )
    }
}

Home.contextType = Context