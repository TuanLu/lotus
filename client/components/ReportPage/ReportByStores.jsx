import React, {Component} from 'react'
import {connect} from 'react-redux'
import Bar from './Chart/Bar'
import {updateStateData} from 'actions'
import FilterByYear from './Filter/FilterByYear'
import FilterByProduct from './Filter/FilterByProduct'
import FilterByProvince from './Filter/FilterByProvince'
import FilterLabel from './Filter/FilterLabel'

class ReportByStores extends Component{
  render() {
    let {year, product, province} = this.props.mainState.filter;
    return (
      <React.Fragment>
        <div className="ui segment">
          <FilterLabel/>
          <FilterByYear/>
          {" | "}
          <FilterByProduct/>
          {" | "}
          <FilterByProvince/>
        </div>
        <div className="grid ui equal width">
          <div className="column">
            <Bar 
              url={`${ISD_BASE_URL}report?year=${year}&type=stores&product-id=${product}`}/>
          </div>
        </div>
        <div className="grid ui equal width">
          <div className="column">
            <Bar
              url={`${ISD_BASE_URL}report?year=${year}&type=area_quarter&product-id=${product}&province=${province}`}/>
          </div>
        </div>
        <div className="ui grid equal width">
          <div className="column">
            <Bar
               url={`${ISD_BASE_URL}report?year=${year}&type=area_month&product-id=${product}&province=${province}`}
            />
          </div>
        </div>     
      </React.Fragment>
    );
  }
};
export default connect((state) => {
  return {
    mainState: state.main.present,
    uiState: state.ui,
  }
})(ReportByStores);
