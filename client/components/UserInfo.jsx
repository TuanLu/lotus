import React from 'react'
import {updateStateData} from 'actions'

class UserInfo extends React.Component {
  showUserName() {
    let {userInfo} = this.props.mainState;
    if(userInfo) {
      return userInfo.name || userInfo.username || userInfo.email;
    }
  }
  render() {
    
    return (
      <div className="admin-user-info">
        <div className="user-info" style={{margin: 10}}>
          <p>Xin chào, {" "}<b>{this.showUserName()}</b></p>
        </div>
        <button
          onClick={() => {
            this.props.dispatch(updateStateData({
              showLogin: true
            }));
          }} 
          className="ui button primary inverted teal">Đăng xuất</button>
      </div>
    );
  }
}

export default UserInfo