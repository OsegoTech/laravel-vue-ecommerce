import axiosClient from "../axios.js";

export function getUser({commit}){
    return axiosClient.get('/user')
        .then(response => {
            commit('setUser', response.data)
            return response
        })
}

export function login({commit}, data){
    return axiosClient.post('/login', data)
        .then(({data})=>{
            commit('setUser', data);
            commit('setToken', data.token)
            return data
        })
}

export function logout({commit}) {
    return axiosClient.post('/logout')
        .then((response) => {
            commit('setToken', null)

            return response
        })
}

export function getProducts({commit}, {url= null}) {
    commit('setProducts', [true])
    url = url || '/product'
    return axiosClient.get(url)
        .then(res => {
            commit('setProducts', [false, res.data])
        })
        .catch(() => {
            commit('setProducts', [false])
        })
}
