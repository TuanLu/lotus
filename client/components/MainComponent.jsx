import React, {Component} from 'react'
import {connect} from 'react-redux'
//import Bar from './Chart/Bar'
import TableData from './TableData/TableData'
import UploadFile from './UploadFile'
import { Dimmer, Segment,Menu } from 'semantic-ui-react'
import {convertObjectsToArray} from 'ISD_API'
import {updateStateData} from 'actions'

class MainComponent extends Component{
  state = { activeItem: 'home' }

  handleItemClick = (e, { name }) => this.setState({ activeItem: name })
  render() {
    const orderData = this.props.mainState.orderData || {};
    let orderDataArr = convertObjectsToArray(orderData);
    const { activeItem } = this.state
    return (
      <div id="main" className="container ui">
        <Menu pointing secondary>
          <Menu.Item name='home' active={activeItem === 'home'} onClick={this.handleItemClick} />
          <Menu.Item name='messages' active={activeItem === 'messages'} onClick={this.handleItemClick} />
          <Menu.Item name='friends' active={activeItem === 'friends'} onClick={this.handleItemClick} />
          <Menu.Menu position='right'>
            <Menu.Item name='logout' active={activeItem === 'logout'} onClick={this.handleItemClick} />
          </Menu.Menu>
        </Menu>
        {orderDataArr.length ? 
        <TableData orderData={orderDataArr}/> 
        : 
        <div className="ui segment padded">
          <h1>Chọn file excel</h1>
          <UploadFile 
            url="http://erpapp/upload"
            done={(response) => {
              let resJson = JSON.parse(response);
              if(resJson.status == "success" && resJson.data) {
                this.props.dispatch(updateStateData({
                  orderData: resJson.data
                }));
              }
            }}/>
        </div>
        }
      </div>
    );
  }
};
export default connect((state) => {
  return {
    mainState: state.main.present,
    uiState: state.ui,
  }
})(MainComponent);
