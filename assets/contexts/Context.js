import React from 'react'

const Context = React.createContext({
    user: null,
    updatedUser: (user) => {},
})

export default Context