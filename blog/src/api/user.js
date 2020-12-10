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

export function getInfo() {
  return request({
    url: '/api/v1/users/info',
    method: 'get',
  })
}

export function logout() {
  return request({
    url: '/api/v1/logout',
    method: 'get'
  })
}
