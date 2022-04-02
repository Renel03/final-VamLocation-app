import React, { Component } from 'react'
import axios from 'axios'
import Context from '../contexts/Context'
import Helmet from 'react-helmet'
import Post from './elements/Post'
import Aside from './elements/Aside'

function nb(nb)
{
    return new Intl.NumberFormat('fr-FR').format(nb)
}

export default class Search extends Component {
    constructor(){
        super()
        this.state = {
            posts: [],
            meta: [],
            title: [],
            postsHasError: false,
            postsError: null,
            postsLoading: true,

            type: [],
            state: [],
            fuel: [],
            trademark: [],
            model: [],
            version: [],
            priceMin: "",
            priceMax: "",
            yearMin: "",
            yearMax: "",
            mileageMin: "",
            mileageMax: "",
            speedType: "",

            priceError: null,
            yearError: null,
            mileageError: null,

            years: [],
            models: [],
            modelsLoading: true,
            trademarks: [],
            trademarksLoading: true,
            versions: [],
            versionsLoading: true,
        }

        this.handleChange = this.handleChange.bind(this)
        this.searchUpdateSubmit = this.searchUpdateSubmit.bind(this)
    }

    handleChange(e) {
        const target = e.target
        const value = target.type === 'checkbox' ? target.checked : target.value
        const name = target.name
        this.setState({
            [name]: value    
        })
    }

    isLoading(){
        return this.state.postsLoading || this.state.trademarksLoading || this.state.modelsLoading || this.state.versionsLoading
    }
    
    componentDidMount(){
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')
        let tab = this.getQuery(this.props.location.search)
        this.setQuery(tab)
        this.setTitle(tab)
        this.fetchPosts()
        this.rangeYears()
        this.fetchTrademarks()
        this.fetchModels()
        this.fetchVersions()
    }
    
    componentDidUpdate(prevProps){
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
        }
        
        if(this.props.location && this.props.location.search !== prevProps.location.search){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
            let tab = this.getQuery(this.props.location.search)
            this.setQuery(tab)
            this.setTitle(tab)
            this.fetchPosts()
        }
    }

    rangeYears(){
        let years = []
        let current_year = parseInt((new Date()).getFullYear())
        for (let i = 0; i <= 100; i++) {
            years.push(current_year - i)
        }
        this.setState({years})
    }

    async fetchPosts(params=null){
        if(params==null){
            const { location: { search } } = this.props
            params = search
        }
        await axios     .get(`/api/v1/posts${params}`)
                        .then(({ data: data }) => {
                            this.setState({
                                posts: data.data,
                                meta: data.meta,
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {
                            this.setState({
                                postsLoading: false,
                            })
                            this.setTitle(this.getQuery(params))
                        })
    }

    getQuery(query){
        let tab = []
        if(query!=""){
            query = query.replace(/\?/,'')
            query = query.split('&')
            for(let i=0;i<query.length;i++){
                let q = query[i].split('=')
                let name = q[0].replace(/search\[/,'').replace(/\]/,'')
                let val = q[1]
                if(name == 'type' || name == 'state' || name == 'fuel' || name == 'trademark' || name == 'model' || name == 'version')
                    val = val.split(',')
                tab[name] = val
            }
        }
        return tab
    }

    setQuery(tab){
        this.setState({
            type: tab.type ? tab.type : [],
            state: tab.state ? tab.state : [],
            fuel: tab.fuel ? tab.fuel : [],
            trademark: tab.trademark ? tab.trademark : [],
            model: tab.model ? tab.model : [],
            version: tab.version ? tab.version : [],
            priceMin: tab.priceMin ? tab.priceMin : "",
            priceMax: tab.priceMax ? tab.priceMax : "",
            yearMin: tab.yearMin ? tab.yearMin : "",
            yearMax: tab.yearMax ? tab.yearMax : "",
            mileageMin: tab.mileageMin ? tab.mileageMin : "",
            mileageMax: tab.mileageMax ? tab.mileageMax : "",
            speedType: tab.speedType ? tab.speedType : "",
        })
    }

    setTitle(tab){
        let title = []
        title[0] = 'Recherche auto: annonces' + (tab.type && tab.type.length == 1 ? (tab.type[0] == '1' ? ' voiture' : (tab.type[0] == '2' ? ' moto': ' utilitaire')) : " auto" ) + ( tab.state && tab.state.length == 1 ? (tab.state[0] == '1' ? ' neuf' : ' occasion') : " neuf ou occasion" )
        title[1] = 'Annonces' + (tab.type && tab.type.length == 1 ? (tab.type[0] == '1' ? ' voiture' : (tab.type[0] == '2' ? ' moto': ' utilitaire')) : " auto" ) + ( tab.state && tab.state.length == 1 ? (tab.state[0] == '1' ? ' neuf' : ' occasion') : " neuf ou occasion" )
        title[2] = (this.state.meta.total_items == 0 ? "Aucune annonce" : (this.state.meta.total_items == 1 ? "Une annonce" : '<i>' + nb(this.state.meta.total_items) + '</i> annonces')) + ( tab.type && tab.type.length == 1 ? (tab.type[0] == '1' ? ' voiture' : (tab.type[0] == '2' ? ' moto': ' utilitaire')) : " auto" )            
        this.setState({
            title
        })
    }

    async fetchTrademarks(){
        await axios     .get('/api/v1/trademarks/options')
                        .then(({ data: data }) => {
                            this.setState({
                                trademarks: data,
                                trademarksLoading: false
                            })
                        }).then(err => {
                            this.setState({
                                trademarksLoading: false
                            })
                        })
    }
    
    async fetchModels(){
        await axios     .get('/api/v1/models/options')
                        .then(({ data: data }) => {
                            this.setState({
                                models: data,
                                modelsLoading: false
                            })
                        }).then(err => {
                            this.setState({
                                modelsLoading: false
                            })
                        })
    }
    
    async fetchVersions(){
        await axios     .get('/api/v1/versions/options')
                        .then(({ data: data }) => {
                            this.setState({
                                versions: data,
                                versionsLoading: false
                            })
                        }).then(err => {
                            this.setState({
                                versionsLoading: false
                            })
                        })
    }

    searchUpdateSubmit(e){
        e.preventDefault()

        const {
            type,
            state,
            fuel,
            trademark,
            model,
            version,
            priceMin,
            priceMax,
            yearMin,
            yearMax,
            mileageMin,
            mileageMax,
            speedType,
        } = this.state

        let priceErrorTemp = null;
        let yearErrorTemp = null;
        let mileageErrorTemp = null;

        this.setState({
            priceError: null,
            yearError: null,
            mileageError: null
        })

        if(priceMin != "" && priceMax != "" && priceMin >= priceMax){
            priceErrorTemp = "La valeur du prix max doit supérieur à celle du prix min."
            this.setState({ priceError: priceErrorTemp })
        }
        
        if(yearMin != "" && yearMax != "" && yearMin >= yearMax){
            yearErrorTemp = "La valeur de l'année max doit supérieur à celle de l'année min."
            this.setState({ yearError: yearErrorTemp })
        }
        
        if(mileageMin != "" && mileageMax != "" && mileageMin >= mileageMax){
            mileageErrorTemp = "La valeur du kilométrage max doit supérieur à celle du kilométrage min."
            this.setState({ mileageError: mileageErrorTemp })
        }

        let q = ""
        if(type.length > 0){
            let typeTemp = ""
            for(let i=0;i<type.length;i++){
                typeTemp += (i == 0 ? '' : ',') + type[i]
            }
            q += 'search[type]=' + typeTemp
        }

        if(state.length > 0){
            let stateTemp = ''
            for(let i=0;i<state.length;i++){
                stateTemp += (i == 0 ? '' : ',') + state[i]
            }
            q += (q != '' ? '&' : '') + 'search[state]=' + stateTemp
        }

        if(trademark.length > 0){
            let trademarkTemp = ''
            for(let i=0;i<trademark.length;i++){
                trademarkTemp += (i == 0 ? '' : ',') + trademark[i]
            }
            q += (q != '' ? '&' : '') + 'search[trademark]=' + trademarkTemp
        }
        
        if(model.length > 0){
            let modelTemp = ''
            for(let i=0;i<model.length;i++){
                modelTemp += (i == 0 ? '' : ',') + model[i]
            }
            q += (q != '' ? '&' : '') + 'search[model]=' + modelTemp
        }
        
        if(version.length > 0){
            let versionTemp = ''
            for(let i=0;i<version.length;i++){
                versionTemp += (i == 0 ? '' : ',') + version[i]
            }
            q += (q != '' ? '&' : '') + 'search[version]=' + versionTemp
        }
        
        if(fuel.length > 0){
            let fuelTemp = ''
            for(let i=0;i<fuel.length;i++){
                fuelTemp += (i == 0 ? '' : ',') + fuel[i]
            }
            q += (q != '' ? '&' : '') + 'search[fuel]=' + fuelTemp
        }

        if(priceMin != '')
            q += (q != '' ? '&' : '') + "search[priceMin]=" + priceMin
        
        if(priceMax != '')
            q += (q != '' ? '&' : '') + "search[priceMax]=" + priceMax
        
        if(yearMin != '')
            q += (q != '' ? '&' : '') + "search[yearMin]=" + yearMin
        
        if(yearMax != '')
            q += (q != '' ? '&' : '') + "search[yearMax]=" + yearMax
        
        if(mileageMin != '')
            q += (q != '' ? '&' : '') + "search[mileageMin]=" + mileageMin
        
        if(mileageMax != '')
            q += (q != '' ? '&' : '') + "search[mileageMax]=" + mileageMax
        
        if(speedType != '')
            q += (q != '' ? '&' : '') + "search[speedType]=" + speedType

        if(priceErrorTemp == null && yearErrorTemp == null && mileageErrorTemp == null){
            if(q != "")
                q = "?" + q
            this.props.history.push(`/rechercher.html${ q }`)
        }
    }

    render() {
        // console.log(this.state.title)
        return (
            this.isLoading() == false ? (
            <>
                <Helmet>
                    <title>{ this.state.title[0] } - AlloCar</title>
                    <meta name="robots" content="index,follow" />
                </Helmet>
                <section className="section pt-70 pb-40">
                    <div className='wp'>
                        <div className="row">
                            <div className="col-12 col-md-4 col-lg-3">
                                <div className="post-filter">
                                    <form className="form" onSubmit={ this.searchUpdateSubmit }>
                                        <h6>Type de véhicule</h6>
                                        <div className="_form-group inputs--check">
                                            <label>
                                                <input type="checkbox" name="type" value="1" defaultChecked={ this.state.type.includes('1') } onChange={ (e) => {
                                                    let type = this.state.type
                                                    if(e.target.checked){
                                                        type.push(e.target.value)
                                                        this.setState({type})
                                                    }else{
                                                        let pos = type.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < type.length){
                                                            type.splice(pos,1)
                                                            this.setState({type})
                                                        }
                                                    }
                                                } } />
                                                <span></span>
                                                <svg width="35" height="35" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M4 13.343c1.164 0 2.106.942 2.106 2.106 0 .043-.003.084-.005.125a2.214 2.214 0 0 1-.058.382 2.106 2.106 0 0 1-4.142-.32c-.005-.062-.011-.123-.011-.187l.002-.004A2.106 2.106 0 0 1 4 13.343zm14.971 0c1.164 0 2.107.942 2.107 2.106 0 .043-.003.084-.005.125a2.248 2.248 0 0 1-.058.382 2.107 2.107 0 0 1-4.142-.32c-.005-.062-.011-.123-.011-.187l.002-.004a2.106 2.106 0 0 1 2.107-2.102zM4 14.253c-.66 0-1.195.534-1.196 1.194 0 0-.002 0-.002.002 0 .036.003.07.007.106a1.196 1.196 0 0 0 2.351.182.762.762 0 0 0 .02-.108.973.973 0 0 0 .013-.11c0-.022.003-.046.003-.07 0-.66-.535-1.195-1.196-1.195zm14.972 0c-.66 0-1.196.534-1.197 1.194v.002c0 .036.003.07.006.106a1.196 1.196 0 0 0 2.372.074c.005-.036.01-.072.012-.11 0-.022.003-.046.003-.07 0-.66-.535-1.195-1.196-1.195zM9.497 6.447h.356c4.367.042 7.89 2.989 8.027 3.118l.096.09c.174.083.722.283 2.397.613 2.375.468 2.828 1.223 2.912 1.517.19.14.498.478.546.875.04.315.127.445.127.445l.042.052v2.235l-.004.04c-.129.594-2.006.831-2.227.827.022-.062.511-3.593-2.746-3.651-3.3-.06-2.918 3.478-2.897 3.54-3.113-.066-6.154-.095-9.22-.173.014-.062.26-3.317-2.734-3.427-3.08-.113-3.102 3.012-3.093 3.08-.586-.096-.57-.236-.824-.735-.436-.852-.213-2.007-.058-2.252.084-.133.127-.651.164-1.109.034-.405.072-.866.145-1.262.052-.277.164-.645.543-.645.04 0 .078.004.115.007.025.002.05.005.087.005 0 0 .013-.01.03-.057.11-.32.58-.719 1.182-1.205.148-.12.326-.263.4-.338-.028-.193-.022-.427.11-.59a.47.47 0 0 1 .38-.166c.031 0 .062.002.132.008.146 0 .438-.06.84-.142 1.039-.214 2.628-.727 5.528-.7zm.565.772l.06 2.47s5.245.133 5.944.104c-.898-2.314-6.004-2.574-6.004-2.574zm-.972-.01h-.082c-.44.004-2.562.062-3.818.87l-.015 1.557 3.945.045-.03-2.472z"></path></g></svg> Voiture
                                            </label>
                                            <label>
                                                <input type="checkbox" name="type" value="2" defaultChecked={ this.state.type.includes('2') } onChange={ (e) => {
                                                    let type = this.state.type
                                                    if(e.target.checked){
                                                        type.push(e.target.value)
                                                        this.setState({type})
                                                    }else{
                                                        let pos = type.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < type.length){
                                                            type.splice(pos,1)
                                                            this.setState({type})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                <svg width="35" height="35" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M1.282 15.498a2.595 2.595 0 0 1-.08-.613c0-.959.533-1.785 1.307-2.237l.427 1.038c-.214.16-.4.347-.508.614-.106.239-.132.506-.106.772l-1.04.426zm1.28.213c.16.214.347.4.613.507.24.106.507.133.774.106l.426 1.04c-.186.053-.4.08-.613.08-.96 0-1.787-.533-2.24-1.306l1.04-.427zm2.054.4l1.546-.4a2.458 2.458 0 0 1-1.147 1.386l-.4-.986zM3.575 13.42l-.426-1.04c.186-.053.399-.08.613-.08.613 0 1.174.214 1.653.587l-1.84.533zm14.8 3.144a2.334 2.334 0 0 1-.508-1.332l1.146.16a.972.972 0 0 0 .16.373l-.799.8zm1.226-.32c.24.134.48.214.772.214.294 0 .56-.08.774-.213l.8.799c-.426.346-.96.534-1.546.534-.56 0-1.12-.188-1.6-.534l.8-.8zm2.053-.426c.133-.24.213-.48.213-.773 0-.293-.08-.56-.213-.772l.8-.8c.347.426.533.96.533 1.546 0 .586-.213 1.119-.533 1.598l-.8-.799zm-.48-2.051c-.16-.081-.293-.16-.453-.188l-.907-.532c-.24-.133-.187-.506.08-.56a3.23 3.23 0 0 1 .507-.053c.586 0 1.146.214 1.573.533l-.8.8zM3.496 18.722c2.054.16 3.813-1.332 4.054-3.277.239-.08.48-.187.772-.267v.08c-.054.48-.213.96-.506 1.36-.08.106 0 .266.133.266h8.773v-.186c.026-.32.053-.987.08-1.546h.08c.054 1.839 1.573 3.384 3.44 3.438a3.58 3.58 0 0 0 3.679-3.571c0-1.546-.96-2.826-2.293-3.305l.32-.24s-.933-.48-1.573-.426c-.32.027-.586.107-.827.214 1.52-1.066 2.507-.747 2.827-1.173.266-.347-.88-1.679-1.733-2.638-.348-.374-.8-.773-1.254-1.12.027-.133 0-.746-.532-1.065l-.294.425c-.294-.186-.48-.319-.48-.319s-1.734.613-1.493.853l.986.879s-1.04.107-1.146.426c-.08.16.08.693.08.693H16.4a.608.608 0 0 0-.187-.212c-.08-.054-.48-.214-1.013-.428-.027-.106-.08-.186-.16-.213l-.268-.08a.206.206 0 0 0-.212.054c-.853-.267-1.866-.506-2.56-.4-.24.053-1.28.16-1.866 1.492l-2.48.107c-.533-.107-1.093-.293-1.6-.613-.133-.08-.213-.053-.267 0-.026-.053-.079-.08-.107-.133-.185-.267-.453-.453-.745-.586-.907-.373-.987-.853-1.094-.853-1.786 0-3.76.693-3.52 1.28.08.16.213.266.374.319L4.189 9.13c.4.132.719.426.906.826v.026c.32.746.906 1.36 1.6 1.786l.48.293-.507.373a3.744 3.744 0 0 0-2.88-1.303030c-2.133 0-3.866 1.786-3.785 3.944.079 1.892 1.599 3.464 3.492 3.677z"></path></g></svg> Moto
                                            </label>
                                            <label>
                                                <input type="checkbox" name="type" value="3" defaultChecked={ this.state.type.includes('3') } onChange={ (e) => {
                                                    let type = this.state.type
                                                    if(e.target.checked){
                                                        type.push(e.target.value)
                                                        this.setState({type})
                                                    }else{
                                                        let pos = type.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < type.length){
                                                            type.splice(pos,1)
                                                            this.setState({type})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                <svg width="35" height="35" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g fill="none" fillRule="evenodd"><path d="M0 0h24v24H0z"></path><path fill="currentColor" d="M19.63 15.114c.978 0 1.782.796 1.782 1.774 0 .984-.804 1.781-1.783 1.781a1.785 1.785 0 0 1-1.783-1.781c0-.978.796-1.774 1.783-1.774zm-12.42 0a1.778 1.778 0 1 1-1.782 1.774c0-.978.796-1.774 1.781-1.774zm12.42.803a.973.973 0 0 0-.972.97c0 .546.433.972.971.972s.972-.433.972-.971a.973.973 0 0 0-.972-.971zm-12.42 0a.97.97 0 1 0-.001 1.942c.532 0 .97-.433.97-.971a.976.976 0 0 0-.97-.971zm13.224-8.894v.883c0 .058.008.116.027.17l1.232 3.685a.273.273 0 0 0 .148.162l1.833.784A.54.54 0 0 1 24 13.2v3.151c0 .296-.24.537-.537.537h-.922c-.197-.159-.331-.265-.53-.424a2.35 2.35 0 0 0-.22-.676 2.434 2.434 0 0 0-2.162-1.32 2.426 2.426 0 0 0-2.39 1.99.527.527 0 0 1-.52.43H9.675c0-.109.092-.837-.45-1.45h6.619c.297 0 .537-.24.537-.537V7.56c0-.295.24-.536.537-.536h3.515zM5.266 15.438a2.405 2.405 0 0 0-.484 1.45H2.528l-1.836-.464v-.986h4.574zM15.547 5.331v9.545H0V5.331h15.547zm4.242 3.633h-2.261v2.912h3.231l-.97-2.912z"></path></g></svg> Utilitaire
                                            </label>
                                        </div>
                                        <hr />
                                        <h6>Etat</h6>
                                        <div className="_form-group inputs--check">
                                            <label>
                                                <input type="checkbox" name="state" value="1" defaultChecked={ this.state.state.includes('1') } onChange={ (e) => {
                                                    let state = this.state.state
                                                    if(e.target.checked){
                                                        state.push(e.target.value)
                                                        this.setState({state})
                                                    }else{
                                                        let pos = state.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < state.length){
                                                            state.splice(pos,1)
                                                            this.setState({state})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Neuf
                                            </label>
                                            <label>
                                                <input type="checkbox" name="state" value="2" defaultChecked={ this.state.state.includes('2') } onChange={ (e) => {
                                                    let state = this.state.state
                                                    if(e.target.checked){
                                                        state.push(e.target.value)
                                                        this.setState({state})
                                                    }else{
                                                        let pos = state.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < state.length){
                                                            state.splice(pos,1)
                                                            this.setState({state})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Occasion
                                            </label>
                                        </div>
                                        <hr />
                                        <h6>Energie</h6>
                                        <div className="_form-group inputs--check">
                                            <label>
                                                <input type="checkbox" name="fuel" value="essence" defaultChecked={ this.state.fuel.includes('essence') } onChange={ (e) => {
                                                    let fuel = this.state.fuel
                                                    if(e.target.checked){
                                                        fuel.push(e.target.value)
                                                        this.setState({fuel})
                                                    }else{
                                                        let pos = fuel.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < fuel.length){
                                                            fuel.splice(pos,1)
                                                            this.setState({fuel})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Essence
                                            </label>
                                            <label>
                                                <input type="checkbox" name="fuel" value="diesel" defaultChecked={ this.state.fuel.includes('diesel') } onChange={ (e) => {
                                                    let fuel = this.state.fuel
                                                    if(e.target.checked){
                                                        fuel.push(e.target.value)
                                                        this.setState({fuel})
                                                    }else{
                                                        let pos = fuel.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < fuel.length){
                                                            fuel.splice(pos,1)
                                                            this.setState({fuel})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Diesel
                                            </label>
                                            <label>
                                                <input type="checkbox" name="fuel" value="electric" defaultChecked={ this.state.fuel.includes('electric') } onChange={ (e) => {
                                                    let fuel = this.state.fuel
                                                    if(e.target.checked){
                                                        fuel.push(e.target.value)
                                                        this.setState({fuel})
                                                    }else{
                                                        let pos = fuel.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < fuel.length){
                                                            fuel.splice(pos,1)
                                                            this.setState({fuel})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Electrique
                                            </label>
                                            <label>
                                                <input type="checkbox" name="fuel" value="hybrid" defaultChecked={ this.state.fuel.includes('hybrid') } onChange={ (e) => {
                                                    let fuel = this.state.fuel
                                                    if(e.target.checked){
                                                        fuel.push(e.target.value)
                                                        this.setState({fuel})
                                                    }else{
                                                        let pos = fuel.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < fuel.length){
                                                            fuel.splice(pos,1)
                                                            this.setState({fuel})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Hybride
                                            </label>
                                            <label>
                                                <input type="checkbox" name="fuel" value="other" defaultChecked={ this.state.fuel.includes('other') } onChange={ (e) => {
                                                    let fuel = this.state.fuel
                                                    if(e.target.checked){
                                                        fuel.push(e.target.value)
                                                        this.setState({fuel})
                                                    }else{
                                                        let pos = fuel.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < fuel.length){
                                                            fuel.splice(pos,1)
                                                            this.setState({fuel})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                Autre
                                            </label>
                                        </div>
                                        <hr />
                                        <h6>Marque</h6>
                                        <div className="_form-group inputs--check">
                                        { this.state.trademarks.map((mark) => {
                                            return(
                                            <label key={ mark.slug }>
                                                <input type="checkbox" name="trademark" value={ mark.slug } defaultChecked={ this.state.trademark.includes(mark.slug) } onChange={ (e) => {
                                                    let trademark = this.state.trademark
                                                    if(e.target.checked){
                                                        trademark.push(e.target.value)
                                                        this.setState({trademark})
                                                    }else{
                                                        let pos = trademark.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < trademark.length){
                                                            trademark.splice(pos,1)
                                                            this.setState({trademark})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                { mark.name }
                                            </label>
                                            )
                                        }) }
                                        </div>
                                        <hr />
                                        <h6>Modèle</h6>
                                        <div className="_form-group inputs--check">
                                        { this.state.models.map((modl) => {
                                            return(
                                            <label key={ modl.slug }>
                                                <input type="checkbox" name="model" value={ modl.slug } defaultChecked={ this.state.model.includes(modl.slug) } onChange={ (e) => {
                                                    let model = this.state.model
                                                    if(e.target.checked){
                                                        model.push(e.target.value)
                                                        this.setState({model})
                                                    }else{
                                                        let pos = model.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < model.length){
                                                            model.splice(pos,1)
                                                            this.setState({model})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                { modl.name }
                                            </label>
                                            )
                                        }) }
                                        </div>
                                        <hr />
                                        <h6>Version</h6>
                                        <div className="_form-group inputs--check">
                                        { this.state.versions.map((vers) => {
                                            return(
                                            <label key={ vers.slug }>
                                                <input type="checkbox" name="version" value={ vers.slug } defaultChecked={ this.state.version.includes(vers.slug) } onChange={ (e) => {
                                                    let version = this.state.version
                                                    if(e.target.checked){
                                                        version.push(e.target.value)
                                                        this.setState({version})
                                                    }else{
                                                        let pos = version.indexOf(e.target.value)
                                                        if(pos >= 0 && pos < version.length){
                                                            version.splice(pos,1)
                                                            this.setState({version})
                                                        }
                                                    }
                                                } }/>
                                                <span></span>
                                                { vers.name }
                                            </label>
                                            )
                                        }) }
                                        </div>
                                        <hr />
                                        <h6>Prix (en Ariary)</h6>
                                        <div className="_form-group row">
                                            <label className='col-6'>
                                                <input type="number" min="100000" max="5000000000" placeholder='Prix min' name="priceMin" value={ this.state.priceMin } onChange={ this.handleChange }/>
                                            </label>
                                            <label className='col-6'>
                                                <input type="number" min="100000" placeholder='Prix max' max="5000000000" name="priceMax" value={ this.state.priceMax } onChange={ this.handleChange }/>
                                            </label>
                                        </div>
                                        { this.state.priceError !=null && <div className='form-error'>{ this.state.priceError }</div> }
                                        <hr />
                                        <h6>Année</h6>
                                        <div className="_form-group row">
                                            <label className='col-6'>
                                                <select name='yearMin' defaultValue={ this.state.yearMin } onChange={ this.handleChange }>
                                                    <option value="">Année min</option>
                                                    {this.state.years.map((v,i) => {return(
                                                    <option key={ i } value={ v }>{ v }</option>
                                                    )})}
                                                </select>
                                            </label>
                                            <label className='col-6'>
                                                <select name='yearMax' defaultValue={ this.state.yearMax } onChange={ this.handleChange }>
                                                    <option value="">Année max</option>
                                                    {this.state.years.map((v,i) => {return(
                                                    <option key={ i } value={ v }>{ v }</option>
                                                    )})}
                                                </select>
                                            </label>
                                        </div>
                                        { this.state.yearError !=null && <div className='form-error'>{ this.state.yearError }</div> }
                                        <hr />
                                        <h6>Kilométrage (en Km)</h6>
                                        <div className="_form-group row">
                                            <label className='col-6'>
                                                <input type="number" min="0" max="5000000" placeholder='Km min' name="mileageMin" value={ this.state.mileageMin } onChange={ this.handleChange }/>
                                            </label>
                                            <label className='col-6'>
                                                <input type="number" min="0" placeholder='Km max' max="5000000" name="mileageMax" value={ this.state.mileageMax } onChange={ this.handleChange }/>
                                            </label>
                                        </div>
                                        { this.state.mileageError !=null && <div className='form-error'>{ this.state.mileageError }</div> }
                                        <hr />
                                        <h6>Boîte de vitesse</h6>
                                        <div className="_form-group">
                                            <label>
                                                <input type="radio" name="speedType" defaultChecked={ this.state.speedType == 'automatic' } value="automatic" onChange={ this.handleChange }/>
                                                <span></span>
                                                Boîte automatique
                                            </label>
                                            <label>
                                                <input type="radio" name="speedType" defaultChecked={ this.state.speedType == 'mechanical' } value="mechanical" onChange={ this.handleChange }/>
                                                <span></span>
                                                Boîte mécanique
                                            </label>
                                        </div>
                                        <div className='post-filter-btn pt-10'>
                                            <button className='btn btn-primary font-weight-bold w-100'><i className='fa fa-search'></i> Rechercher</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div className="col-12 col-md-8 col-lg-6">
                                <h1 className="title">
                                    { this.state.title[1] }<br/>
                                    <span>{ this.state.title[2] }</span>
                                </h1>
                                <div className="posts">
                                { this.state.posts.map((post) => {
                                    return (
                                        <Post post={ post } key={ post.id }/>
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
            </>) : (
                <div className="loader"></div>
            )
        )
    }
}

Search.contextType = Context