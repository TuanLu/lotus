import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

const quarterOptions = [
  {
    key: 1,
    value: 1,
    text: 'Quý 1'
  },
  {
    key: 2,
    value: 2,
    text: 'Quý 2'
  },
  {
    key: 3,
    value: 3,
    text: 'Quý 3'
  },
  {
    key: 4,
    value: 4,
    text: 'Quý 4'
  },
];

class FilterByQuarter extends React.Component {  
  render() {
    return (
      <Dropdown 
        compact
        onChange={(event, data) => {
          if(this.props.mainState.filter.quarter == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              quarter: data.value
            }
          }));
        }}
        placeholder='Chọn Quý' 
        selection 
        value={this.props.mainState.filter.quarter}
        options={quarterOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(FilterByQuarter);