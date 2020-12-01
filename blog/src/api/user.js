import request from '@/utils/request'
import qs from 'qs'
export function login(data) {
  data = qs.stringify(data)
  return request({
    url: '/api/v1/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/vue-admin-template/user/info',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/vue-admin-template/user/logout',
    method: 'post'
  })
}
