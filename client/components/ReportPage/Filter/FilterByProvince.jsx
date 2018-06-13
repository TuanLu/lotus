import React from 'react'
import { Dropdown } from 'semantic-ui-react'
import {updateStateData} from 'actions'
import {connect} from 'react-redux'

class Province extends React.Component {  
  constructor(props) {
    super(props);
    this.state = {
      provinceOptions: [
        {
          key: 'all',
          value: 'all',
          text: 'Tất cả tỉnh thành'
        }
      ]
    }
  }
  componentDidMount() {
    if(!this.props.mainState.provinces.length) {
      fetch(ISD_BASE_URL + 'provinces')
      .then((response) => {
        return response.json();
      }).then((json) => {
        if(json.status && json.status == "error") {
          console.warn(json.message);
          return false;
        }
        if(json.data) {
          let provinceOptions = json.data.map((province) => {
            return {
              key: province.code,
              value: province.code,
              text: province.name
            }
          });
          //update province list to store
          if(provinceOptions.length) {
            this.props.dispatch(updateStateData({
              provinces: provinceOptions
            }));
          }
          this.setState({provinceOptions: [...this.state.provinceOptions, ...provinceOptions]});

        }
      }).catch((error) => {
        console.log('parsing failed', error)
      });
    } else {
      this.setState({provinceOptions: [...this.state.provinceOptions, ...this.props.mainState.provinces]});
    }
  }
  render() {
    return (
      <Dropdown 
        compact
        onChange={(event, data) => {
          if(this.props.mainState.filter.province == data.value) return false;
          this.props.dispatch(updateStateData({
            filter: {
              ...this.props.mainState.filter,
              province: data.value
            }
          }));
        }}
        placeholder='Chọn Tỉnh'  
        selection 
        search
        loading={this.state.provinceOptions.length == 1 ? true : false}
        value={this.props.mainState.filter.province}
        options={this.state.provinceOptions} />
    );
  }
}
export default connect((state) => {
  return {
    mainState: state.main.present,
  }
})(Province);