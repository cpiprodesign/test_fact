export default  {
  data () {
    return {
      _data_usuario: null
    }
  },
  methods: {
    getUser () {
      if (this._data_usuario == null) {
        this._data_usuario = JSON.parse(getContentMeta('user_data'))
        return this._data_usuario
      } else
        return this._data_usuario
    },
    /**
     * [role_users array|string]
     *
     * @return  {[boolean]}  [return description]
     */
    hasRole (role_users) {
      console.log(role_users)
      if (_.isArray(role_users)) {
        return role_users.find(u => {
          return this.getUser().roles.find(r => u == r.slug)
        }) != undefined
        
      } else {
        return this.getUser().roles.find(r => role_users == r.slug) != undefined
      }
    },
    hasPermissionTo (permissions_user) {
      if (_.isArray(permissions_user)) {
        return permissions_user.find(u => {
          return this.getUser().permissions.find(r => u == r.slug)
        }) != undefined
        
      } else {
        return this.getUser().permissions.find(r => permissions_user == r.slug) != undefined
      }
    }
  }
}