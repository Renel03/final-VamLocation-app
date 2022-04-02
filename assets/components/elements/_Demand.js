import React from 'react'

function _dateFormat(date){
    let dateTemp = (String(date)).split('T')[0].split('-')
    let months = ['jav.', 'fév.', 'mar.', 'avr.', 'mai', 'juin', 'juil.', 'aoû.', 'sept.', 'oct.', 'nov.', 'déc.']
    return dateTemp[2] + " " + months[parseInt(dateTemp[1]) - 1] + " " + dateTemp[0]
}

export default function _Demand({ demand }) {
    return (
        <div className='_demand'>
            <div className='_demand-id'>Demande #{ demand.id }</div>
            <div className='_demand-nb-pers'>{ demand.nbPers == 1 ? 'Une personne' : demand.nbPers + " personnes" }</div>
            <div className='_demand-city'>
                <h6>Trajet</h6>
                { demand.fromCity.name } ({ demand.fromCity.cp }){  demand.tripType != 3 ? <> - { demand.toCity.name } ({ demand.toCity.cp })</> : <> - { demand.tripType3Cities.map((city, i) => {
                    return(
                        i <= 1 ? <span key={ i }>{ city.name } ({ city.cp }){ i == 0 ? ', ' : '' }</span> : ( i == 2 ? ', ...' : '')
                    )
                }) }</> }
            </div>
            <div className='_demand-type'>
                <h6>Type</h6>
                { demand.tripType == 1 ? "Aller simple" : (demand.tripType == 2 ? "Aller-retour" : "Circuit") }
            </div>
            <div className='_demand-date'>
                <h6>Dates</h6>
                { _dateFormat(demand.checkInAt) }{ demand.checkOutAt != null ? <> - { _dateFormat(demand.checkOutAt) }</> : "" }
            </div>
        </div>
    )
}
