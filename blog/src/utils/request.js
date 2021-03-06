import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 5000 // request timeout
})

const codeMessage = {
  200: '服务器成功返回请求的数据。',
  201: '新建或修改数据成功。',
  202: '一个请求已经进入后台排队（异步任务）。',
  204: '删除数据成功。',
  400: '发出的请求有错误，服务器没有进行新建或修改数据的操作。',
  401: '用户没有权限（令牌、用户名、密码错误）。',
  403: '用户得到授权，但是访问是被禁止的。',
  404: '发出的请求针对的是不存在的记录，服务器没有进行操作。',
  406: '请求的格式不可得。',
  410: '请求的资源被永久删除，且不会再得到的。',
  422: '当创建一个对象时，发生一个验证错误。',
  500: '服务器发生错误，请检查服务器。',
  502: '网关错误。',
  503: '服务不可用，服务器暂时过载或维护。',
  504: '网关超时。',
}

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['Authorization'] = getToken()
    }
    config.headers['X-Requested-With'] = 'XMLHttpRequest'
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data
    // if the custom code is not 20000, it is judged as an error.
    // if (res.code !== 200 && response.status !== 200) {
    //   // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
    //   if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
    //     // to re-login
    //     MessageBox.confirm('You have been logged out, you can cancel to stay on this page, or log in again', 'Confirm logout', {
    //       confirmButtonText: 'Re-Login',
    //       cancelButtonText: 'Cancel',
    //       type: 'warning'
    //     }).then(() => {
    //       store.dispatch('user/resetToken').then(() => {
    //         location.reload()
    //       })
    //     })
    //   }
    //   return Promise.reject(new Error(res.message || 'Error'))
    // } else {
    //   return res
    // }
    return res
  },
  error => {
    // console.log(JSON.stringify(error)) // for debug
    if (error === undefined || error.code === 'ECONNABORTED') {
      Message.warning('服务请求超时')
      return Promise.reject(error)
    }
    const { response: { status, statusText, data: { msg = '服务器发生错误' } } } = error
    const { response } = error
    const { dispatch } = store
    const text = codeMessage[status] || statusText || msg
    const info = response.data
    if (status === 401 || info.code === 401) {
      dispatch({
        type: 'login/logout',
      })
      MessageBox.confirm('你已被登出，可以取消继续留在该页面，或者重新登录', '确定登出', {
        confirmButtonText: '重新登录',
        cancelButtonText: '取消',
        type: 'warning',
      }).then(() => {
        store.dispatch('LogOut').then(() => {
          location.reload() // 为了重新实例化vue-router对象 避免bug
        })
      })
    }
    if (status === 403) {
      dispatch(routerRedux.push('/exception/403'))
      // Notification.warning({
      //     title: '禁止',
      //     message: info.message,
      //     type: 'error',
      //     duration: 2 * 1000,
      // })
    }
    if (info.code === 30101) {
      dispatch(routerRedux.push('/exception/500'))
      // Notification.warning({
      //     title: '失败',
      //     message: info.message,
      //     type: 'error',
      //     duration: 2 * 1000,
      // })
    }
    if (response.status === 504) {
      dispatch(routerRedux.push('/exception/500'))
      // Message({
      //     message: '后端服务异常，请联系管理员！',
      //     type: 'error',
      //     duration: 5 * 1000,
      // })
    }
    Message.error(`${status}:${text}`)
    // throw error
    // return error
    return Promise.reject(error)
  }
)

export default service
