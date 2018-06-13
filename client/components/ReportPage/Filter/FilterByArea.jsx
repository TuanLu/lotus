import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

let areaOptions = [
  {
    key: 'all',
    value: 'all',
    text: 'Tất cả vùng miền'
  },
  {
    key: 'b',
    value: 'b',
    text: 'Miền Bắc'
  },
  {
    key: 't',
    value: 't',
    text: 'Miền Trung'
  },
  {
    key: 'n',
    value: 'n',
    text: 'Miền Nam'
  },
  {
    key: 'cod',
    value: 'cod',
    text: 'Cash On Delivery'
  },
]

class FilterByArea extends React.Component {  
  render() {
    return (
      <Dropdown 
        onChange={(event, data) => {
          if(this.props.mainState.filter.area == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              area: data.value
            }
          }));
        }}
        placeholder='Chọn Vùng Miền' 
        selection 
        value={this.props.mainState.filter.area}
        options={areaOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(FilterByArea);