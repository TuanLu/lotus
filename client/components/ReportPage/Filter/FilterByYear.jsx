import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

const range = (start, end) => (
  Array.from(Array(end - start + 1).keys()).map(i => i + start)
);
let dynamicsYear = range(2016, (new Date()).getFullYear());

let yearOptions = dynamicsYear.map((year) => {
  return {
    key: year,
    value: year,
    text: 'Năm ' + year
  }
});

class Year extends React.Component {  
  render() {
    return (
      <Dropdown 
        compact
        onChange={(event, data) => {
          if(this.props.mainState.filter.year == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              year: data.value
            }
          }));
        }}
        placeholder='Chọn Năm' 
        selection 
        value={this.props.mainState.filter.year}
        options={yearOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(Year);