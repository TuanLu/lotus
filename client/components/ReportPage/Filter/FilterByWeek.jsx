import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

const weekOptions = [];

for(let i = 1; i <= 52; i++) {
  weekOptions.push({
    key: i,
    value: i,
    text: `Tuần ${i}`
  });
}

class FilterByWeek extends React.Component {  
  render() {
    return (
      <Dropdown 
        compact
        onChange={(event, data) => {
          if(this.props.mainState.filter.week == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              week: data.value
            }
          }));
        }}
        placeholder='Chọn Tuần' 
        selection 
        value={this.props.mainState.filter.week}
        options={weekOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(FilterByWeek);