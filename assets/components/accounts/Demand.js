import axios from 'axios'
import React, { Component } from 'react'
import Helmet from 'react-helmet'
import Context from '../../contexts/Context'

export default class Demand extends Component {
    constructor(){
        super()

        this.state = {
            demands: [],
            meta: null,
            loading: true
        }
    }

    componentDidMount(){
        const { user } = this.context
        window.scrollTo(0, 0)
        document.getElementsByTagName("body")[0].classList.add('is-sticky')
        if(user == null)
            this.props.history.push('/')
        this.fetchDemands()
    }

    componentDidUpdate(prevProps){
        const { user } = this.context
        if(user == null)
            this.props.history.push('/')
        if(this.props.location && this.props.location.pathname !== prevProps.location.pathname){
            window.scrollTo(0, 0)
            document.getElementsByTagName("body")[0].classList.add('is-sticky')
        }
    }

    async fetchDemands(){
        const { user } = this.context
        await axios     .get(`/api/v1/demands?user_id=${user.id}`)
                        .then(({ data: res }) => {
                            this.setState({
                                demands: res.data,
                                meta: res.meta
                            })
                        })
                        .catch((err) => {
                            console.log(err.response.data)
                        })
                        .finally(() => {
                            this.setState({
                                loading: false
                            })
                        })
    }

    _dateFormat(date){
        let dateTemp = (String(date)).split('T')[0].split('-')
        let months = ['jav.', 'fév.', 'mar.', 'avr.', 'mai', 'juin', 'juil.', 'aoû.', 'sept.', 'oct.', 'nov.', 'déc.']
        return dateTemp[2] + " " + months[parseInt(dateTemp[1]) - 1] + " " + dateTemp[0]
    }

    render() {
        return (
            <>
                <Helmet>
                    <title>Mes demandes - AlloCar</title>
                    <meta name="robots" content="noindex,follow"/>
                </Helmet>

                { this.state.loading ? (
                    <div className='loader'></div>
                ) : (
                <>
                    <h1 className='title'>Mes demandes</h1>
                    <table className='table table-striped'>
                        <thead style={{ position: "sticky", top: "60px", backgroundColor: "#ffffff" }}>
                            <tr>
                                <th width='100px'>#</th>
                                <th width='140px'>Type de trajet</th>
                                <th>Lieux</th>
                                <th width="160px">Dates</th>
                                <th width='75px'>Etat</th>
                                <th width='84px' className='text-center'>Passagers</th>
                                <th width='160px'></th>
                            </tr>
                        </thead>
                        <tbody>
                            { this.state.meta != null && this.state.meta.total_items > 0 ? 
                            this.state.demands.map(demand => {
                            return (
                                <tr key={ demand.id }>
                                    <td>#{ demand.id }</td>
                                    <td>{ demand.tripType == 1 ? "Aller simple" : (demand.tripType == 2 ? "Aller-retour" : "Circuit") }</td>
                                    <td>
                                        <strong className='text-uppercase' style={{ fontStyle: "normal", fontSize: "12px", fontWeight: "400" }}>De:</strong> { demand.fromCity.name } ({ demand.fromCity.cp })<br/>
                                        <strong className='text-uppercase' style={{ fontStyle: "normal", fontSize: "12px", fontWeight: "400" }}>Destination:</strong> { demand.tripType != 3 ?  demand.toCity.name + ' (' + demand.toCity.cp + ')' : demand.tripType3Cities.map((city, i) => {
                                            return(
                                                <span key={ i }>{ city.name } ({ city.cp }){ demand.tripType3Cities.length > 1 && i != demand.tripType3Cities.length - 1 ? ', ' : '' }</span>
                                            )
                                        }) }
                                    </td>
                                    <td>
                                        <strong className='text-uppercase' style={{ fontStyle: "normal", fontSize: "12px", fontWeight: "400" }}>Départ:</strong> { this._dateFormat(demand.checkInAt) }<br/>
                                        { demand.checkOutAt != null ? <><strong className='text-uppercase' style={{ fontStyle: "normal", fontSize: "12px", fontWeight: "400" }}>Arrivée:</strong> { this._dateFormat(demand.checkOutAt) }</> : "" }
                                    </td>
                                    <td></td>
                                    <td className='text-center'>{ demand.nbPers }</td>
                                    <td></td>
                                </tr>
                            )
                            }) : (
                            <tr>
                                <td colSpan='7'>Aucun demande trouvé</td>
                            </tr>
                            ) }
                        </tbody>
                    </table>
                </>
                ) }
            </>
        )
    }
}

Demand.contextType = Context