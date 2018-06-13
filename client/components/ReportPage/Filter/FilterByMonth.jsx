import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

const monthOptions = [];

for(let i = 1; i <= 12; i++) {
  monthOptions.push({
    key: i,
    value: i,
    text: `Tháng ${i}`
  });
}

class FilterByMonth extends React.Component {  
  render() {
    return (
      <Dropdown 
        compact
        onChange={(event, data) => {
          if(this.props.mainState.filter.month == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              month: data.value
            }
          }));
        }}
        placeholder='Chọn Tháng' 
        selection 
        value={this.props.mainState.filter.month}
        options={monthOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(FilterByMonth);